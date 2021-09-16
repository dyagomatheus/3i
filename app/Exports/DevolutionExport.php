<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DevolutionExport implements FromView
{
    public $devolutions  = [];

    public function view(): View {
        $devolutions = $this->devolutions;

        return view("devolution.excel", compact('devolutions'));
    }
}
