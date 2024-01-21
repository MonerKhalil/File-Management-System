<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BaseExport implements FromView ,ShouldAutoSize
{
    private $head,$body,$blade,$data;

    public function __construct($head,$body,$blade = null,array $dataMore=null)
    {
        $this->head = $head;
        $this->body = $body;
        $this->blade = is_null($blade) ? "ViewXlsxTable.GeneralXlsxTable" : $blade ;
        $this->data = is_null($dataMore) ? [] : $dataMore ;
    }

    /**
     * @return View
     * @author moner khalil
     */
    public function view(): View
    {
        return \view($this->blade,$this->data + [
                "data" => [
                    "head" => $this->head,
                    "body" => $this->body,
                ],
            ]);
    }
}

