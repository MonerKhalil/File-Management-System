<?php

namespace Database\Seeders;

use App\HelperClasses\MyApp;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LaratrustUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $config = MyApp::Classes()->laratrustFileSeeder->file_laratrust_seeder_update()['role_structure'];
        $mapPermission = collect(MyApp::Classes()->laratrustFileSeeder->getPermissionsMapInFileMain());

        foreach ($config as $key => $modules) {

            // Create a new role
            $role = Role::firstOrCreate([
                'name' => $key,
                'display_name' => ucwords(str_replace('_', ' ', $key)),
                'description' => ucwords(str_replace('_', ' ', $key))
            ]);
            $permissions = [];

            $this->command->info('Creating Role ' . strtoupper($key));

            // Reading role permission modules
            foreach ($modules as $module => $value) {

                foreach (explode(',', $value) as $p => $perm) {

                    $permissionValue = $mapPermission->get($perm);

                    $permissions[] = Permission::firstOrCreate([
                        'name' => $permissionValue . '_' . $module,
                        'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                        'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                    ])->id;

                    $this->command->info('Creating Permission to ' . $permissionValue . ' for ' . $module);
                }
            }

            // Attach all permissions to the role
            $role->permissions()->sync($permissions);

            $this->command->info("Creating '{$key}' user");

            // Create default user for each role
            // $user = User::create([
            //     'name' => ucwords(str_replace('_', ' ', $key)),
            //     'email' => $key.'@app.com',
            //     'password' => bcrypt('password')
            // ]);

            // $user->attachRole($role);
      }
    }
}
