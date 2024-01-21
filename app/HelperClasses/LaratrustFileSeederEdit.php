<?php

namespace App\HelperClasses;

use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LaratrustFileSeederEdit
{
    public function MainProcess($Role, $Permissions)
    {
        $MainArray = $this->file_laratrust_seeder_update();
        if (isset($MainArray['role_structure'])) {
            $Permissions = Permission::query()->whereIn("id", $Permissions)->pluck("name")->toArray();

            $MainArray['role_structure'][$Role->name] = $this->getPermissions($Permissions);
        }
        File::put($this->path_laratrust_seeder(), json_encode($MainArray));
    }

    public function getPermissions($permissions)
    {
        $Temp = [];
        foreach ($permissions as $permission) {
            $tempPer = explode("_", $permission);
            $event = $tempPer[0];
            unset($tempPer[0]);
            $table = implode("_", $tempPer);
            $Temp[$table][] = $this->getKeyEvent($event);
        }
        $final = $Temp;
        foreach ($Temp as $key => $value) {
            if (is_array($value)) {
                $final[$key] = implode(",", $value);
            }
        }
        return $final;
    }

    private function getKeyEvent($event)
    {
        foreach ($this->getPermissionsMapInFileMain() as $key => $value) {
            if ($value === $event) {
                return $key;
            }
        }
        return "r";
    }

    public function path_laratrust_seeder()
    {
        return config_path('laratrust_seeder_update') . '.json';
    }

    public function file_laratrust_seeder_update()
    {
        return json_decode(File::get($this->path_laratrust_seeder()), true);
    }

    public function getPermissionsMapInFileMain()
    {
        return config("laratrust_seeder.permissions_map");
    }

    public function getAllNameTables()
    {
        $tables = DB::select('SHOW TABLES');

        return array_filter(array_map('current', $tables), function ($item) {
            return !in_array($item, [
                'oauth_access_tokens', 'oauth_auth_codes', 'oauth_clients', 'oauth_personal_access_clients'
                , 'telescope_entries', 'telescope_entries_tags', 'telescope_monitoring'
                , 'oauth_refresh_tokens', 'audits', 'failed_jobs', 'migrations', 'personal_access_tokens',
                'password_resets', 'notifications'
            ]);
        });
    }

    public function FinalPermissionsData($permissions,$tables): array
    {
        $Temp = [];
        foreach ($permissions as $permission) {
            $tempPer = explode("_", $permission->name);
            unset($tempPer[0]);
            $table = implode("_", $tempPer);
            if (in_array($table,$tables)){
                $Temp[$table][] = $permission;
            }else{
                $Temp["other"][] = $permission;
            }
        }
        return $Temp;
    }
}
