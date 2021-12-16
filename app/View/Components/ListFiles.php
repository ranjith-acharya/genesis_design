<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ListFiles extends Component
{

    public $files;
    public $path;

    /**
     * Create a new component instance.
     *
     * @param $files
     * @param $path
     */
    public function __construct($files, $path)
    {
        $this->files = $files;
        $this->path = $path;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.list-files', ["files" => $this->files, "path" => $this->path]);
    }
}
