<?php

namespace App\HelperClasses;

use App\Exceptions\MainException;
use App\Http\Repositories\Eloquent\LanguageRepository;
use App\Models\BaseTranslationModel;
use App\Models\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LanguageProcess
{
    const CACHE_LANG = "__languages__";
    /**
     * @var mixed|HigherOrderBuilderProxy|null
     * @author moner khalil
     */
    public mixed $langLocal = null;

    /**
     * @var Builder[]|Collection
     */
    public $allLangs = null;

    /**
     * @var null
     */
    public $defaultLang = null;

    public function __construct()
    {
        if(! app()->runningInConsole()){
            $this->allLangs = Cache::remember("__languages__",86400,function (){
                return DB::table("languages")->where('is_active',1)->get();
            });
            $this->defaultLang = $this->allLangs->where("default",1)->first();
            $this->langLocal = $this->allLangs->where("code",app()->getLocale())->first();
            if (is_null($this->langLocal)){
                $this->langLocal = $this->allLangs->first();
            }
        }
    }

    public function setLangLocal($code){
        $this->langLocal = $this->allLangs?->where("code",$code)->first();
        if (is_null($this->langLocal)){
            $this->langLocal = $this->defaultLang;
        }
    }

    public function Lang($code){
        return $this->allLangs?->where("code",$code)->first();
    }

    public function getDefaultLang($code){
        $lang = $this->Lang($code);
        if (is_null($lang)){
            return $this->defaultLang;
        }
        return $lang;
    }

    /**
     * @return string
     */
    public function getFkTableTranslation(): string
    {
        return "main_id";
    }

    /**
     * @param $object
     * @param $attributes
     * @author moner khalil
     */
    public function createInTranslationTable($object, $attributes){
        if ($object instanceof BaseTranslationModel){
            $langDefault = MyApp::Classes()->languageProcess->defaultLang->id;
            $Data = [
                $this->getFkTableTranslation() => $object->id,
                "local_id" => $langDefault,
            ];
            foreach ($attributes as $key => $value){
                if (in_array($key,$object->attributesTranslations())){
                    $Data [$key] = $value;
                }
            }
            $object->translation()->create($Data);
        }
    }

    /**
     * @param $object
     * @param array $attributes
     * @return array
     * @throws MainException
     * @author moner khalil
     */
    public function updateInTranslationTable($object,array $attributes){
        if ($object instanceof BaseTranslationModel){
            $translation = [];
            $langDefault = $this->defaultLang->id;
            $local_id = $attributes['local_id'] ?? null;
            if (is_null($local_id)){
                throw new MainException(__("local translation error : id is not exist.."));
            }
            $translation['local_id'] = $local_id;
            if ($langDefault != $local_id){
                foreach ($attributes as $key => $value){
                    if (in_array($key,$object->attributesTranslations())){
                        $translation[$key] = $value;
                        unset($attributes[$key]);
                    }
                }
            }else{
                foreach ($attributes as $key => $value){
                    if (in_array($key,$object->attributesTranslations())){
                        $translation[$key] = $value;
                    }
                }
            }
            $objectTranslation = $object->translation()
                ->where($this->getFkTableTranslation(),$object->id)
                ->where('local_id',$local_id)
                ->first();
            if (is_null($objectTranslation)){
                $object->translation()->create([
                        $this->getFkTableTranslation() => $object->id,
                    ] + $translation);
            }else{
                $objectTranslation->update($translation);
            }
        }
        if (isset($attributes['local_id'])){
            unset($attributes['local_id']);
        }
        return $attributes;
    }

    /**
     * @param $object
     * @param array $attributes
     * @return array
     * @throws MainException
     * @author moner khalil
     */
    public function updateInTranslationTableSettings($object,array $attributes,$isTrans){
        if ($object instanceof BaseTranslationModel && $isTrans){
            $translation = [];
            $langDefault = $this->defaultLang->id;
            $local_id = $attributes['local_id'] ?? null;
            if (is_null($local_id)){
                throw new MainException(__("local translation error : id is not exist.."));
            }
            $translation['local_id'] = $local_id;
            if ($langDefault != $local_id){
                foreach ($attributes as $key => $value){
                    if ($isTrans){
                        $translation[$key] = $value;
                        unset($attributes[$key]);
                    }
                }
            }else{
                foreach ($attributes as $key => $value){
                    if ($isTrans){
                        $translation[$key] = $value;
                    }
                }
            }
            $objectTranslation = $object->translation()
                ->where($this->getFkTableTranslation(),$object->id)
                ->where('local_id',$local_id)
                ->first();
            if (is_null($objectTranslation)){
                $object->translation()->create([
                        $this->getFkTableTranslation() => $object->id,
                    ] + $translation);
            }else{
                $objectTranslation->update($translation);
            }
        }
        if (isset($attributes['local_id'])){
            unset($attributes['local_id']);
        }
        return $attributes;
    }

    public function getAllCodeLangs(){
        return array(
            'en' => 'English',
            'aa' => 'Afar',
            'ab' => 'Abkhazian',
            'af' => 'Afrikaans',
            'am' => 'Amharic',
            'ar' => 'Arabic',
            'as' => 'Assamese',
            'ay' => 'Aymara',
            'az' => 'Azerbaijani',
            'ba' => 'Bashkir',
            'be' => 'Byelorussian',
            'bg' => 'Bulgarian',
            'bh' => 'Bihari',
            'bi' => 'Bislama',
            'bn' => 'Bengali',
            'br' => 'Breton',
            'ca' => 'Catalan',
            'co' => 'Corsican',
            'cs' => 'Czech',
            'cy' => 'Welsh',
            'da' => 'Danish',
            'de' => 'German',
            'dz' => 'Bhutani',
            'el' => 'Greek',
            'es' => 'Spanish',
            'et' => 'Estonian',
            'eu' => 'Basque',
            'fa' => 'Persian',
            'fi' => 'Finnish',
            'fj' => 'Fiji',
            'fo' => 'Faeroese',
            'fr' => 'French',
            'fy' => 'Frisian',
            'ga' => 'Irish',
            'gd' => 'Scots/Gaelic',
            'gn' => 'Guarani',
            'gu' => 'Gujarati',
            'ha' => 'Hausa',
            'hi' => 'Hindi',
            'hr' => 'Croatian',
            'hu' => 'Hungarian',
            'hy' => 'Armenian',
            'in' => 'Indonesian',
            'is' => 'Icelandic',
            'it' => 'Italian',
            'iw' => 'Hebrew',
            'ja' => 'Japanese',
            'ka' => 'Georgian',
            'kk' => 'Kazakh',
            'kl' => 'Greenlandic',
            'km' => 'Cambodian',
            'kn' => 'Kannada',
            'ko' => 'Korean',
            'ku' => 'Kurdish',
            'ky' => 'Kirghiz',
            'la' => 'Latin',
            'ln' => 'Lingala',
            'lt' => 'Lithuanian',
            'lv' => 'Latvian/Lettish',
            'mg' => 'Malagasy',
            'mi' => 'Maori',
            'mk' => 'Macedonian',
            'ml' => 'Malayalam',
            'mn' => 'Mongolian',
            'mo' => 'Moldavian',
            'mr' => 'Marathi',
            'ms' => 'Malay',
            'mt' => 'Maltese',
            'my' => 'Burmese',
            'ne' => 'Nepali',
            'nl' => 'Dutch',
            'no' => 'Norwegian',
            'oc' => 'Occitan',
            'om' => '(Afan)/Oromoor/Oriya',
            'pa' => 'Punjabi',
            'pl' => 'Polish',
            'ps' => 'Pashto/Pushto',
            'pt' => 'Portuguese',
            'rm' => 'Rhaeto-Romance',
            'rn' => 'Kirundi',
            'ro' => 'Romanian',
            'ru' => 'Russian',
            'rw' => 'Kinyarwanda',
            'sd' => 'Sindhi',
            'sg' => 'Sangro',
            'sh' => 'Serbo-Croatian',
            'si' => 'Singhalese',
            'sk' => 'Slovak',
            'sl' => 'Slovenian',
            'sm' => 'Samoan',
            'sn' => 'Shona',
            'so' => 'Somali',
            'sq' => 'Albanian',
            'sr' => 'Serbian',
            'ss' => 'Siswati',
            'st' => 'Sesotho',
            'su' => 'Sundanese',
            'sv' => 'Swedish',
            'sw' => 'Swahili',
            'ta' => 'Tamil',
            'te' => 'Tegulu',
            'tg' => 'Tajik',
            'th' => 'Thai',
            'ti' => 'Tigrinya',
            'tk' => 'Turkmen',
            'tl' => 'Tagalog',
            'tn' => 'Setswana',
            'to' => 'Tonga',
            'tr' => 'Turkish',
            'tw' => 'Twi',
            'uk' => 'Ukrainian',
            'ur' => 'Urdu',
            'uz' => 'Uzbek',
            'vi' => 'Vietnamese',
            'wo' => 'Wolof',
            'xh' => 'Xhosa',
            'yo' => 'Yoruba',
            'zh' => 'Chinese',
            'zu' => 'Zulu'
        );
    }
}
