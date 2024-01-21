<?php

namespace App\Services;

class RoleTreeService
{
    public function mainProcessReadFiles($pathFile = null){
        $result = [];
        $pathFile = $pathFile ?? config_path("role_permission.php");
        $data = require $pathFile;

        foreach ($data as $item) {
            $keys = explode('.', $item);
            $lastKey = array_pop($keys);
            $current = &$result;

            foreach ($keys as $key) {
                if (!isset($current[$key])) {
                    $current[$key] = [];
                }
                $current = &$current[$key];
            }

            if (!empty($lastKey)) {
                $current[] = $lastKey;
            }
        }
        return $result;
    }
}
