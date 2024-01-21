<?php

namespace App\HelperClasses;

use App\Http\Repositories\Eloquent\SettingControlRepository;
use App\Http\Repositories\Eloquent\SettingRepository;
use App\Http\Repositories\Interfaces\ISettingControlRepository;
use App\Http\Repositories\Interfaces\ISettingRepository;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class MyApp
{
    public const RouteHome = "dashboard";

    public const PASSWORD = "123123123";

    public const PAGINATE_NAME_SETTING = "PAGINATE_DEFAULT";

    public const DEFAULT_PAGES_Count = 10;

    /**
     * @var MyApp|null
     * @author moner khalil
     */
    private static MyApp|null $app = null;

    /**
     * @var string
     * @author moner khalil
     */
    public string $localeSessionKey = "lang";

    /**
     * @var StorageFiles|null
     * @author moner khalil
     */
    public ?StorageFiles $storageFiles = null;

    /**
     * @var SearchModel|null
     * @author moner khalil
     */
    public ?SearchModel $Search = null;

    /**
     * @var StringProcess|null
     * @author moner khalil
     */
    public ?StringProcess $stringProcess = null;

    /**
     * @var LaratrustFileSeederEdit|null
     * @author moner khalil
     */
    public ?LaratrustFileSeederEdit $laratrustFileSeeder = null;

    /**
     * @var Json|null
     * @author moner khalil
     */
    public ?Json $json = null;

    /**
     * @var LogMain|null
     * @author moner khalil
     */
    public ?LogMain $logMain = null;

    public ?LanguageProcess $languageProcess = null;

    public ?DataBaseProcess $DBProcess = null;

    public ?ISettingControlRepository $ISettingControlRepository = null;

    public ?ISettingRepository $ISettingRepository = null;

    private $settingControlData = null,$settings = null;

    private array $allPermissionsUser = [];

    private function __construct()
    {
        $this->storageFiles = new StorageFiles();
        $this->Search = new SearchModel();
        $this->stringProcess = new StringProcess();
        $this->logMain = new LogMain();
        $this->laratrustFileSeeder = new LaratrustFileSeederEdit();
        $this->json = new Json();
        $this->languageProcess = new LanguageProcess();
        $this->DBProcess = new DataBaseProcess();
        $this->ISettingControlRepository = new SettingControlRepository(app());
        $this->ISettingRepository = new SettingRepository(app());
    }

    /**
     * @return MyApp
     * @author moner khalil
     */
    public static function Classes(): MyApp
    {
        if (is_null(self::$app)){
            $mainObj = new static();
            self::$app = $mainObj;
            $mainObj->setAllPermissionsUser();
        }
        return self::$app;
    }

    /**
     * @return Authenticatable|null
     */
    public function getUser(): ?Authenticatable
    {
        if (urlIsApi()){
            return \auth("user_api")->user();
        }
        return \auth()->user();
    }

    private function setAllPermissionsUser(): void {
        $user = $this->getUser();
        $this->allPermissionsUser = !is_null($user) ? $user->allPermissions()->pluck("name")->toArray() : [];
    }

    /**
     * @return array
     */
    public function getAllPermissionsUser(): array {
        return $this->allPermissionsUser;
    }

    /**
     * @param string $namePermission
     * @return Collection|array
     * @author moner khalil
     */
    public function getUsersForPermission(string $namePermission): Collection|array
    {
        return User::query()->whereHas("roles.permissions",function ($query) use ($namePermission){
            $query->where('name', $namePermission);
        })->get();
    }

    /**
     * @param bool $read
     * @param null $all
     * @return int
     */
    public function getCountNotifications(bool $read = false, $all = null): int
    {
        $user = auth()->user();
        if (!is_null($user)) {
            if (is_null($all)) {
                return $read ? $user->readNotifications()->count()
                    : $user->unreadNotifications()->count();
            }
            return $user->notifications()->count();
        }
        return 0;
    }

    public function fetchCrudGeneratorRoutes($isApi = false)
    {
        $routes = $this->getRouteJsonFile($isApi);
        $routes = json_decode($routes, true);

        foreach ($routes as $url => $controller) {
            $this->mainRoutes($url,$controller);
        }
    }

    /**
     * @return string[]
     */
    public function routesALLCrud(): array
    {
        return [
            'index' => 'index',
            'create' => 'create',
            'store' => 'store',
            'show' => 'show',
            'edit' => 'edit',
            'update' => 'update',
            'destroy' => 'destroy',
            'force_delete' => 'forceDestroy',
            'delete.multi' => 'multiDestroy',
            'export.xlsx' => 'exportXLSX',
            'export.pdf' => 'exportPDF',
            'import' => 'import',
            'active' => 'active',
            'active.multi' => 'activeMulti',
        ];
    }

    public function getRouteJsonFile($isApi = false): string
    {
        return File::get($this->getRouteJsonPath($isApi));
    }

    public function getRouteJsonPath($isApi = false): string
    {
        return $isApi ? base_path() . "/routes/api.json" : base_path() . "/routes/web.json";
    }

    public function mainRoutes($url,$controller){
        Route::resource($url,$controller);
        Route::prefix($url)
            ->name($url.".")
            ->controller($controller)
            ->group(function (){
                Route::put("active/row/{id}","activeRow")->name("active.row");
                Route::put("deactivate/row/{id}","deactivateRow")->name("deactivate.row");
                Route::put("active_deactivate/{id}","active")->name("active");
                Route::put("active/multi/models","activeMulti")->name("active.multi");
                Route::post("import","import")->name("import");
                Route::get("export/xlsx","exportXLSX")->name("export.xlsx");
                Route::get("export/pdf","exportPDF")->name("export.pdf");
                Route::delete("force_delete/{id}","forceDestroy")->name("force_delete");
                Route::delete("delete/multi","multiDestroy")->name("delete.multi");
            });
    }

    public function settingControl($key){
        if (is_null($this->settingControlData)){
            $this->settingControlData = $this->ISettingControlRepository->all();
        }
        return $this->settingControlData->where("key",$key)->first();
    }

    public function setting($key){
        if (is_null($this->settings)){
            $this->settings = $this->ISettingRepository->all()->map(function ($item){
                $item->value = checkObjectInstanceofTranslation($item,"value");
                return $item;
            });
        }
        return $this->settings->where("key",$key)->first();
    }
}
