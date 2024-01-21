<?php

namespace App\Imports;

use App\HelperClasses\MyApp;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class BaseImport implements ToCollection, WithHeadingRow, WithValidation
{
    private $repository;
    private $rules;

    public function __construct($repository)
    {
        $this->repository = $repository;
        $this->rules = $this->repository->getInstance()->validationBackEnd();
    }

    /**
     * @param $data
     * @param $index
     * @return array
     */
    public function prepareForValidation($data, $index): array
    {
        $temp = [];
        foreach ($data as $key => $value) {
            if (str_contains($key, "date")) {
                if (!is_numeric($value)) {
                    $temp[$key] = $value;
                } else {
                    $temp[$key] = Date::excelToDateTimeObject($value)->format('Y-m-d');
                }
            } else {
                $temp[$key] = $value;
            }
        }
        return $temp;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $item) {
            $temp = $this->passingNewDataDefaultToRealData($item->toArray());
            $this->repository->create($temp,false);
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $rules = [];
        unset($this->rules['notes']);
        foreach ($this->rules as $key => $rule) {
            $rules["*." . $key] = $rule;
        }
        return $rules;
    }

    /**
     * @param array $real_data
     * @return array
     * @author moner khalil
     */
    private function passingNewDataDefaultToRealData(array $real_data): array{
        if (Schema::hasColumn($this->repository->nameTable, "slug")) {
            $slug = "xxx";
            if (Schema::hasColumn($this->repository->nameTable, "name")) {
                $slug = $real_data["name"] ?? $slug;
            } else if (Schema::hasColumn($this->repository->nameTable, "title")) {
                $slug = $real_data["title"] ?? $slug;
            }
            $real_data["slug"] = MyApp::Classes()->stringProcess->uniqueSlug($slug, $this->repository->queryModel());
        }
        return $real_data;
    }

}
