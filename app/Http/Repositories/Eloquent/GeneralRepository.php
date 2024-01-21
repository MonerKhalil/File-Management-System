<?php

namespace App\Http\Repositories\Eloquent;

use App\Exceptions\MainException;
use App\HelperClasses\CraftData;
use App\HelperClasses\MessagesFlash;
use App\HelperClasses\MyApp;
use App\HelperClasses\StorageFiles;
use App\HelperClasses\TSetupAttribute;
use App\Models\BaseModel;
use App\Models\MediaManager;
use App\Notifications\MainNotification;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

/**
 * @method array preStoreBehaviour(array $data)
 * @method void postStoreBehaviour(Model $model, array $data)
 * @method array preUpdateBehaviour(Model $model, array $data)
 * @method void postUpdateBehaviour(Model $model, array $data)
 * @method void preDestroyBehaviour(Model $model)
 * @method void postDestroyBehaviour()
 */
abstract class GeneralRepository
{
    use TSetupAttribute;

    /**
     * @var
     */
    public $model;

    protected ?Closure $preStoreBehaviour = null;
    protected ?Closure $postStoreBehaviour = null;
    protected ?Closure $preUpdateBehaviour = null;
    protected ?Closure $postUpdateBehaviour = null;
    protected ?Closure $preDestroyBehaviour = null;
    protected ?Closure $postDestroyBehaviour = null;

    protected array $controllerFunctions = [];

    protected array $fillable = [];


    public function __construct()
    {
        $this->controllerFunctions = get_class_methods($this);
    }

    public abstract function model();

    public abstract function queryModel();

    /**
     * @param $data
     * @return mixed
     * @throws MainException
     * @author moner khalil
     */
    private function resolveMainData($data){
        foreach ($data as $key => $val){
            if (!is_null($val) && ( str_contains($key,StorageFiles::NAME_IMG) || str_contains($key,StorageFiles::NAME_File) )){
                if (is_file($val) || $val instanceof UploadedFile){
                    $data[$key] = $this->storeInMediaManager($val)->pdf_path;
                }else{
                    $item = (new MediaManagerRepository(app()))->queryModelWithActive()->where("id",$val)->first();
                    $data[$key] = $item->pdf_path ?? "";
                }
            }
            if (is_null($val)){
                unset($data[$key]);
            }
        }
        return $data;
    }
    /**
     * @param $data
     * @return mixed
     * @throws MainException
     * @author moner khalil
     */
    protected function resolveDataToCreate($data): mixed{
        $data = is_null($this->preStoreBehaviour) ? $data : $this->preStoreBehaviour($data);
        return $this->resolveMainData($data);
    }

    /**
     * @param $data
     * @param $oldModel
     * @return mixed
     * @throws MainException
     * @author moner khalil
     */
    protected function resolveDataToUpdate($data , $oldModel): mixed{
        $data = is_null($this->preUpdateBehaviour) ? $data : $this->preUpdateBehaviour($oldModel,$data);
        return $this->resolveMainData($data);
    }

    /**
     * @param $request
     * @return array
     * @throws MainException
     * @author moner khalil
     */
    protected function mainProcessExport($request,bool $isPdf = false): array
    {
        $request->validate([
            "ids" => ["sometimes","array"],
            "ids.*" => ["sometimes",Rule::exists($this->nameTable,"id")],
        ]);
        try {
            if (!is_null($request->export_empty) && !$isPdf) {
                $data = new Collection([]);
            }
            $head = $this->getInstance()->viewFieldsValidationFrontEnd();
            $head = $this->resolveHeadTable($head);
            if (!isset($data)){
                if (!isset($request->ids)){
                    $data = $this->get(true);
                }else{
                    $data = $this->get(true,function ($q) use ($request){
                        return $q->whereIn("id",$request->ids)->get();
                    });
                }
            }
            $users = MyApp::Classes()->getUsersForPermission("all_".$this->nameTable);
            $user = MyApp::Classes()->getUser();
            $model = $this->model;
            if ($model instanceof BaseModel && isset($model->getRoutes()["index"])){
                $route = route($model->getRoutes()["index"]);
            }else{
                $route = route("notification.print.table.show");
            }
            $dataNotify = [
                "model_type" => $this->model(),
                "table_name" => $this->nameTable,
                "user_id" => $user->id,
                "user_name" => $user->name,
                "date" => now(),
                "route_name" => $route,
            ];
            Notification::send($users,new MainNotification($dataNotify,"print"));
            return [
                "tableName" => $this->nameTable,
                "head" => $head,
                "body" => CraftData::many($data),
            ];
        }catch (\Exception $e){
            throw new MainException($e->getMessage());
        }
    }

    private function resolveHeadTable($head){
        $head = ignoreFieldsShow($head);
        if ($head['is_active']){
            unset($head['is_active']);
        }
        return $head;
    }

    public function storeInMediaManager($file,$showMessage = false,$objMediaManageRepo = null){
        $path = MyApp::Classes()->storageFiles->Upload($file,MediaManager::NAME_FOLDER. "/" .$this->nameTable);
        $ext = $file->getClientOriginalExtension();
        $dataMediaFiles = [];
        $dataMediaFiles['pdf_path'] = $path;
        $dataMediaFiles['file_name'] = $file->getClientOriginalName();
        $dataMediaFiles['file_size'] = $file->getSize();
        $dataMediaFiles['type'] = in_array($ext,MyApp::Classes()->storageFiles->getExImages(true)) ? "image" : "file";
        $dataMediaFiles['extension'] = $ext;
        $repo = is_null($objMediaManageRepo) ? (new MediaManagerRepository(app())) : $objMediaManageRepo;
        return $repo->create($dataMediaFiles,$showMessage,false);
    }

    public static function useFunStoreInMediaManager($file,$showMessage = false){
        return self::storeInMediaManager($file,$showMessage);
    }
}
