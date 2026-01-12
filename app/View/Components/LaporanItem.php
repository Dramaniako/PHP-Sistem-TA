<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LaporanItem extends Component
{
    public $icon;
    public $filename;
    public $filesize;
    public $format;

    public function __construct($icon, $filename, $filesize, $format)
    {
        $this->icon = $icon;
        $this->filename = $filename;
        $this->filesize = $filesize;
        $this->format = $format;
    }

    public function render()
    {
        return view('components.laporan-item');
    }
}
