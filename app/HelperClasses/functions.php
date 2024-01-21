<?php

use App\HelperClasses\MessagesFlash;
use App\HelperClasses\MyApp;
use App\HelperClasses\StorageFiles;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

function getRequest($key = null){
    return is_null($key) ? \request() : \request($key);
}

function filterDataRequest(){
    return  !is_null(request('filter')) ? request('filter') : [];
}

function Errors($key)
{
    return Session::has(MessagesFlash::$Errors) && isset(Session::get(MessagesFlash::$Errors)[$key])
        ? Session::get(MessagesFlash::$Errors)[$key][0] : null;
}

function Error(){
    return Session::has(MessagesFlash::$err)
        ? Session::get(MessagesFlash::$err) : null;
}

function Success(){
    return Session::has(MessagesFlash::$suc)
        ? Session::get(MessagesFlash::$suc) : null;
}

function pathStorage($path)
{
    return asset($path);
}

function pathImageReal($path)
{
    $path = StorageFiles::getReplacePathToRealExist($path);
    return pathStorage($path);
}

if (!function_exists('user')) {
    /**
     * @return mixed
     */
    function user(): mixed
    {
        return MyApp::Classes()->getUser();
    }
}


if (!function_exists('getRequestUri')) {
    /**
     * @return mixed
     */
    function getRequestUri(): mixed
    {
        return Request::getRequestUri();
    }
}

if (!function_exists('urlIsApi')) {
    /**
     * @return mixed
     */
    function urlIsApi(): mixed
    {
        return Request::is('api/*') || Request::is('api');
    }
}

if (!function_exists('ignoreFieldsShow')) {
    /**
     * this function is getting the model values and remove the keys that passing in getFields function
     * inside config -> ignoreInTable -> and pluck those keys
     * @param $fields
     *
     * @return mixed
     * @author moner khalil
     */
    function ignoreFieldsShow($fields): mixed
    {
        $fieldsShow = $fields;
        $temp = $fieldsShow["config"]["ignoreFromShow"] ?? [];
        foreach ($temp as $ignoredKey) {
            unset($fieldsShow[$ignoredKey]);
        }
        if (isset($fieldsShow["config"])){
            unset($fieldsShow["config"]);
        }
        return $fieldsShow;
    }
}
if (!function_exists('ignoreFromSearch')) {
    /**
     * this function is getting the model values and remove the keys that passing in getFields function
     * inside config -> ignoreInTable -> and pluck those keys
     * @param $fields
     *
     * @return mixed
     * @author moner khalil
     */
    function ignoreFromSearch($fields): mixed
    {
        $fieldsShow = $fields;
        $temp = $fieldsShow["config"]["ignoreFromSearch"] ?? [];
        foreach ($temp as $ignoredKey) {
            unset($fieldsShow[$ignoredKey]);
        }
        if (isset($fieldsShow["config"])){
            unset($fieldsShow["config"]);
        }
        return $fieldsShow;
    }
}
if (!function_exists('ignoreFieldsCreate')) {
    /**
     * this function is getting the model values and remove the keys that passing in getFields function
     * inside config -> ignoreInTable -> and pluck those keys
     * @param $fields
     *
     * @return mixed
     * @author moner khalil
     */
    function ignoreFieldsCreate($fields)
    {
        $fieldsShow = $fields;
        $temp = $fieldsShow["config"]["ignoreFromCreate"] ?? [];
        foreach ($temp as $ignoredKey) {
            unset($fieldsShow[$ignoredKey]);
        }
        if (isset($fieldsShow["config"])){
            unset($fieldsShow["config"]);
        }

        return $fieldsShow;
    }
}
if (!function_exists('ignoreFieldsUpdate')) {
    /**
     * this function is getting the model values and remove the keys that passing in getFields function
     * inside config -> ignoreInTable -> and pluck those keys
     * @param $fields
     *
     * @return mixed
     * @author moner khalil
     */
    function ignoreFieldsUpdate($fields)
    {
        $fieldsShow = $fields;
        $temp = $fieldsShow["config"]["ignoreFromEdit"] ?? [];
        foreach ($temp as $ignoredKey) {
            unset($fieldsShow[$ignoredKey]);
        }
        if (isset($fieldsShow["config"])){
            unset($fieldsShow["config"]);
        }

        return $fieldsShow;
    }
}


if (!function_exists('fetchModel')) {
    /**
     * @param $repository
     * @return mixed
     */
    function fetchModel($repository): mixed
    {
        if (is_string($repository)) {
            return (new $repository(app()))->all();
        }
        return $repository;
    }
}

if (!function_exists('userHasPermission')) {
    /**
     * @param string $permission
     * @return mixed
     */
    function userHasPermission(string $permission): mixed
    {
//        return in_array($permission, MyApp::Classes()->getAllPermissionsUser());
        return true ;
    }
}

if (!function_exists('userCanShowActive')) {
    /**
     * @param string $table_name
     * @return mixed
     */
    function userCanShowActive(string $table_name): mixed
    {
//        return userHasPermission("active_".$table_name);
        return true ;
    }
}

if (!function_exists('checkIfFiledHasFilter')) {
    /**
     * check if there is any filter apply in custom filed, then return the applied value on this field.
     * @param $field
     * @param null $value
     * @return string
     * @author moner khalil
     */
    function checkIfFiledHasFilter($field, $value = null): mixed
    {
        if (!is_null($value)){
            $requestFilter = request()->all();
            if (isset($requestFilter['isClearRequest']) && $requestFilter['isClearRequest'] != "true"){
                if (isset(request()->all()['filter'][$field]) && request()->all()['filter'][$field] == $value){
                    return true;
                }
            }
            return false;
        }
        return (request()->all()['isClearRequest'] ?? null) != "true" ?? false ? request()->all()['filter'][$field] ?? request()->all()[$field] ?? '' : '';
    }
}
if (!function_exists('checkIfValidationRequired')) {
    /**
     * @description check field is validation front is required
     * @param string $validation
     * @param $type
     * @return bool
     * @author moner khalil
     */
    function checkIfValidationRequired(string $validation, $type): bool
    {
        $required = "required";
        if (is_array($type)){
            if (isset($type['select'])){
                return str_contains($type[0] ?? '',$required);
            }
            if (isset($type['relation'])){
                return str_contains($type['relation']['validation'] ?? '',$required);
            }
        }
        return str_contains($validation,$required);
    }
}

/**
 * @param $obj
 * @return bool
 */
function isObjTrans($obj): bool
{
    return $obj instanceof \App\Models\BaseTranslationModel;
}

/**
 * @param $object
 * @param $field
 * @return mixed
 * @author moner khalil
 */
function checkObjectInstanceofTranslation($object,$field): mixed
{
    if ($object instanceof \App\Models\BaseTranslationModel){
        if (in_array($field,$object->attributesTranslations())){
            return $object->attributeTranslation($field);
        }
    }
    return $object->{$field};
}

/**
 * @param $code
 * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
 * @author moner khalil
 */
function lang($code){
    return MyApp::Classes()->languageProcess->Lang($code);
}

/**
 * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
 * @author moner khalil
 */
function defaultLang(){
    return MyApp::Classes()->languageProcess->defaultLang;
}

/**
 * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
 * @author moner khalil
 */
function allLanguges(){
    return MyApp::Classes()->languageProcess->allLangs;
}

function translationSetting($object,$isTrans,$field = "value"){
    if ($object instanceof \App\Models\BaseTranslationModel){
        if (in_array($field,$object->attributesTranslations()) && $isTrans){
            return $object->attributeTranslation($field);
        }
    }
    return $object->{$field};
}

/**
 * @param $data
 * @return array|string|string[]|null
 */
function xss($data){
    $pattern = '/<a[^>]*href\s*=\s*["\']?\s*javascript\s*:\s*[^>"\']*["\']?[^>]*>|<form[^>]*action\s*=\s*["\']?\s*javascript\s*:\s*[^>"\']*["\']?[^>]*>|<img[^>]*src\s*=\s*["\']?\s*javascript\s*:\s*[^>"\']*["\']?[^>]*>/i';
    $data = preg_replace($pattern,'',$data);
    $data = preg_replace('/<script\b[^>]*>(.*?)<\/script>/i', '', $data);
    $data = preg_replace('/script\b[^>]*(.*?)\/script/i', '', $data);
    $data = preg_replace('/<code\b[^>]*>(.*?)<\/code>/i', '', $data);
    $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);

    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    return preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
}

function updateDotEnv($key, $newValue) {
    $path = base_path('.env');

    $oldValue = env($key);

    $newValue = (string) $newValue;

    if ($oldValue === $newValue || !is_string($newValue)) {
        return;
    }

    if (file_exists($path)) {
        file_put_contents(
            $path, str_replace(
                $key.'='.$oldValue,
                $key.'='.$newValue,
                file_get_contents($path)
            )
        );
    }
}

function getLangFile($nameFile = null){
    $file = $nameFile ?? "Main";
    $file .= ".json";
    $path = lang_path(app()->getLocale() . "\\FrontEnd\\".$file);
    return file_exists($path) ? file_get_contents($path) : null;
}
