<?php

namespace App\Http\Repositories\Eloquent;

use App\Exceptions\MainException;
use App\Exports\BaseExport;
use App\HelperClasses\CraftData;
use App\HelperClasses\MessagesFlash;
use App\HelperClasses\MyApp;
use App\HelperClasses\StorageFiles;
use App\Http\Repositories\Interfaces\IBaseRepository;
use App\Imports\BaseImport;
use App\Models\BaseModel;
use App\Notifications\MainNotification;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

abstract class BaseRepository extends GeneralRepository implements IBaseRepository
{
    /**
     * @var \Illuminate\Container\Container
     */
    private $app;

    /**
     * @var string
     */
    public string $nameTable = "";

    public function __construct(App $app)
    {
        parent::__construct();
        $this->nameTable = $this->queryModel()->getQuery()->from;
        $this->app = $app;
        $this->makeModel();
    }

    public function getInstance()
    {
        return new $this->model;
    }

    public function makeModel(){
        try {
            $model = $this->app->make($this->model());
            return $this->model = $model;
        } catch (\Exception $e) {
            throw new MainException($e->getMessage());
        }
    }

    public function queryModelWithActive(){
        $query = $this->queryModel();
        $is_active = isset(request()->filter) && is_array(request()->filter) && isset(request()->filter["is_active"]);
        if (userCanShowActive($this->nameTable) && Schema::hasColumn($this->nameTable, "is_active") && $is_active){
            $query = $query->where("is_active",request()->filter["is_active"]);
        }else{
            $query = $query->where("is_active",true);
        }
        return $query;
    }

    /**
     * without search filter data and take items count
     * @param callable|null $callback
     * @param bool $withActive
     * @author moner khalil
     */
    public function takeData($count = 5, $order = "desc", bool $withActive = true, $withCraft = true,callable $callback = null){
        $countItems = request()->countItems;
        if (isset($countItems) && is_numeric($countItems) && $countItems > 0){
            $count = $countItems;
        }
        $queryBuilder = $withActive ? $this->queryModelWithActive() : $this->queryModel();
        if (!is_null($callback)){
            $queryBuilder = $callback($queryBuilder);
        }
        $data = $queryBuilder->take($count)->orderBy("updated_at",$order)->get();
        return $withCraft ? CraftData::many($data) : $data;
    }

    /**
     * without search filter data
     * @param callable|null $callback
     * @param bool $withActive
     * @author moner khalil
     */
    public function all(callable $callback = null,bool $withActive = true,$withCraft = true, $order = "desc")
    {
        $queryBuilder = $withActive ? $this->queryModelWithActive() : $this->queryModel();
        if (!is_null($callback)){
            $queryBuilder =  $callback($queryBuilder);
        }
        $queryBuilder = $queryBuilder->orderBy("updated_at",$order);
        return $withCraft ? CraftData::many($queryBuilder->get()) : $queryBuilder->get();
    }

    /**
     * within search filter data
     * @param bool|null $isAll
     * @param callable|null $callback
     * @author moner khalil
     */
    public function get(bool $isAll = null, callable $callback = null,bool $withActive = true,$withCraft = true,?string $nameDateFilter = null,bool $withTranslation = true)
    {
        $queryBuilder = $withActive ? $this->queryModelWithActive() : $this->queryModel();
        $data = MyApp::Classes()->Search
            ->getDataFilter($queryBuilder,null,$isAll,$nameDateFilter,$callback,$this->nameTable,$this->model);
        return $withCraft ? CraftData::many($data,$withTranslation) : $data;
    }

    /**
     * @param $data
     * @param bool $showMessage
     * @return mixed
     * @throws MainException
     * @author moner khalil
     */
    public function create($data , bool $showMessage = true , bool $withResolve = true): mixed{
        try {
            DB::beginTransaction();
            $this->setupAttrsToStore();
            $process = "create";
            $data = $withResolve ? $this->resolveDataToCreate($data) : $data;
            $item = $this->queryModel()->create($data);
            if (!is_null($this->postStoreBehaviour)){
                $this->postStoreBehaviour($item,$data);
            }
            MyApp::Classes()->languageProcess->createInTranslationTable($item,$data);
            MyApp::Classes()->logMain->logProcess($process,$item);
            if ($showMessage){
                MessagesFlash::Success($process);
            }
            DB::commit();
            return CraftData::single($item);
        }catch (\Exception $exception){
            DB::rollBack();
            throw new MainException($exception->getMessage());
        }
    }

    /**
     * @param $data
     * @param int $idOldModel
     * @param bool $showMessage
     * @return mixed
     * @throws MainException
     * @author moner khalil
     */
    public function update($data,int $idOldModel, bool $showMessage = true): mixed{
        try {
            DB::beginTransaction();
            $this->setupAttrsToUpdate();
            $process = "update";
            $oldModel = $this->find($idOldModel,null,"id",true);
            $data = $this->resolveDataToUpdate($data,$oldModel);
            $data = MyApp::Classes()->languageProcess->updateInTranslationTable($oldModel,$data);
            $oldModel->update($data);
            if (!is_null($this->postUpdateBehaviour)){
                $this->postUpdateBehaviour($oldModel,$data);
            }
            MyApp::Classes()->logMain->logProcess($process,$oldModel);
            if ($showMessage){
                MessagesFlash::Success($process);
            }
            DB::commit();
            return CraftData::single($oldModel);
        }catch (\Exception $exception){
            DB::rollBack();
            throw new MainException($exception->getMessage());
        }
    }

    /**
     * @param $value
     * @param callable|null $callback
     * @param string $key
     * @param bool $isUpdate
     * @param bool $withFail
     * @param bool $withTranslation
     * @return mixed
     * @author moner khalil
     */
    public function find($value, callable $callback = null, string $key = "id",bool $isUpdate = false,bool $withFail = true,bool $withTranslation = true): mixed{
        $query = $this->queryModelWithActive();
        $query = $query->where($key,$value);
        if (!is_null($callback)){
            $query = $callback($query);
        }
        $final = $withFail ? $query->firstOrFail() : $query->first();
        return !$isUpdate ? CraftData::single($final,$withTranslation) : $final;
    }

    /**
     * @param int $idModel
     * @param bool $showMessage
     * @return mixed
     * @throws MainException
     * @author moner khalil
     */
    public function delete(int $idModel, bool $showMessage = true): bool{
        try {
            DB::beginTransaction();
            $this->setupAttrsToDelete();
            $process = "delete";
            $oldModel = $this->find($idModel);
            if (!is_null($this->preDestroyBehaviour)){
                $this->preDestroyBehaviour($oldModel);
            }
            MyApp::Classes()->DBProcess->deleteAllChildParent($this->nameTable,$idModel);
            $oldModel->delete();
            if (!is_null($this->postDestroyBehaviour)){
                $this->postDestroyBehaviour();
            }
            MyApp::Classes()->logMain->logProcess($process,$oldModel);
            if ($showMessage){
                MessagesFlash::Success($process);
            }
            DB::commit();
            return true;
        }catch (\Exception $exception){
            DB::rollBack();
            throw new MainException($exception->getMessage());
        }
    }

    /**
     * @param $request
     * @param bool $showMessage
     * @return mixed
     * @throws MainException
     * @author moner khalil
     */
    public function multiDestroy($request, bool $showMessage = true,$callbackWhere = null): bool{
        $request->validate([
            "ids" => ["required","array"],
            "ids.*" => ["required",Rule::exists($this->nameTable,"id")],
        ]);
        try {
            DB::beginTransaction();
            $process = "delete";
            $oldModel = $this->queryModelWithActive()->whereIn("id",$request->ids);
            if (!is_null($callbackWhere)){
                $oldModel = $callbackWhere($oldModel);
            }
            MyApp::Classes()->DBProcess->deleteAllChildParent($this->nameTable,$request->ids);
            $oldModel->delete();
            MyApp::Classes()->logMain->logProcess($process,["table"=>$this->nameTable]);
            if ($showMessage){
                MessagesFlash::Success($process);
            }
            DB::commit();
            return true;
        }catch (\Exception $exception){
            DB::rollBack();
            throw new MainException($exception->getMessage());
        }
    }

    /**
     * @param $idModel
     * @param bool $showMessage
     * @return bool
     * @throws MainException
     */
    public function forceDelete($idModel, bool $showMessage = true): bool
    {
        try {
            DB::beginTransaction();
            $this->setupAttrsToDelete();
            $process = "delete";
            $oldModel = $this->find($idModel);
            $temp_model = clone $oldModel;
            $temp_model = $temp_model->toArray();
            foreach ($temp_model as $key => $value) {
                if (!is_null($value) &&  ( str_contains($key,StorageFiles::NAME_IMG) || str_contains($key,StorageFiles::NAME_File) )) {
                    MyApp::Classes()->storageFiles->deleteFile($value);
                }
            }
            if (!is_null($this->preDestroyBehaviour)){
                $this->preDestroyBehaviour($oldModel);
            }
            MyApp::Classes()->DBProcess->checkTableIsAnyChild($this->nameTable,$idModel);
            $oldModel->forceDelete();
            if (!is_null($this->postDestroyBehaviour)){
                $this->postDestroyBehaviour();
            }
            MyApp::Classes()->logMain->logProcess($process,$oldModel);
            if ($showMessage){
                MessagesFlash::Success($process);
            }
            DB::commit();
            return true;
        }catch (\Exception $exception){
            DB::rollBack();
            throw new MainException($exception->getMessage());
        }
    }

    /**
     * @param int $idModel
     * @param bool $showMessage
     * @return mixed
     * @throws MainException
     * @author moner khalil
     */
    public function active(int $idModel , bool $showMessage = true): mixed
    {
        try {
            $process = "update";
            $oldModel = $this->find($idModel,null,"id",true);
            $oldModel->update(['is_active' => !$oldModel->is_active,]);
            MyApp::Classes()->logMain->logProcess($process,$oldModel);
            if ($showMessage){
                MessagesFlash::Success($process);
            }
            return true;
        }catch (\Exception $exception){
            throw new MainException($exception->getMessage());
        }
    }

    /**
     * @param int $idModel
     * @param bool $showMessage
     * @return mixed
     * @throws MainException
     * @author moner khalil
     */
    public function activeRow(int $idModel , bool $showMessage = true): mixed
    {
        return $this->mainProcessFunctionActiveDeactivate($idModel,$showMessage,true);
    }

    /**
     * @param int $idModel
     * @param bool $showMessage
     * @return mixed
     * @throws MainException
     * @author moner khalil
     */
    public function deactivateRow(int $idModel , bool $showMessage = true): mixed
    {
        return $this->mainProcessFunctionActiveDeactivate($idModel,$showMessage,false);
    }

    /**
     * @param $request
     * @param bool $showMessage
     * @return mixed
     * @throws MainException
     * @author moner khalil
     */
    public function activeMulti($request , bool $showMessage = true): mixed
    {
        $request->validate([
            "ids" => ["required","array"],
            "ids.*" => ["required",Rule::exists($this->nameTable,"id")],
            "type" => ["required",Rule::in([0,1])],
        ]);
        try {
            $process = "update";

            $this->queryModelWithActive()->whereIn("id",$request->ids)->update(["is_active" => $request->type]);

            MyApp::Classes()->logMain->logProcess($process,[
                "table" => $this->nameTable,
                "ids" => $request->ids,
            ]);

            if ($showMessage){
                MessagesFlash::Success($process);
            }
            return true;
        }catch (\Exception $exception){
            throw new MainException($exception->getMessage());
        }
    }

    /**
     * @param $request
     * @return mixed
     * @throws MainException
     * @author moner khalil
     */
    public function import($request): mixed
    {
        $request->validate([
            "file" => ["required", "mimes:xlsx", "max:".MyApp::Classes()->storageFiles->getSizeFiles()],
        ]);
        try {
            DB::beginTransaction();
            Excel::import(new BaseImport($this), $request->file);
            DB::commit();
            MessagesFlash::Success("","import_file_success");
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new MainException($e->getMessage());
        }
    }

    /**
     * @param $request
     * @return mixed
     * @throws MainException
     * @author moner khalil
     */
    public function exportXLSX($request): mixed
    {
        $process = $this->mainProcessExport($request);
        try {
            return Excel::download(new BaseExport($process["head"],$process["body"]),$this->nameTable . '.xlsx');
        }catch (\Exception $e){
            throw new MainException($e->getMessage());
        }
    }

    /**
     * @param $request
     * @return mixed
     * @throws MainException
     * @author moner khalil
     */
    public function exportPDF($request): mixed
    {
        $data = $this->mainProcessExport($request,true);

        try {
            $pdf = PDF::loadView('ViewXlsxTable.PDF', [
                "data" => $data,
            ]);

            return $pdf->download($this->nameTable . '.pdf');
        }catch (\Exception $e){
            throw new MainException($e->getMessage());
        }
    }

    private function mainProcessFunctionActiveDeactivate(int $idModel , bool $showMessage = true,bool $active = true){
        try {
            $process = "update";
            $oldModel = $this->find($idModel,null,"id",true);
            $oldModel->update(['is_active' => $active,]);
            MyApp::Classes()->logMain->logProcess($process,$oldModel);
            if ($showMessage){
                MessagesFlash::Success($process);
            }
            return true;
        }catch (\Exception $exception){
            throw new MainException($exception->getMessage());
        }
    }
}
