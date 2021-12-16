<?php

namespace App\View\Components;

use App\SystemDesignMessage;
use Illuminate\View\Component;

class DesignMessages extends Component
{

    public $designID;
    public $readOnly;

    /**
     * Create a new component instance.
     *
     * @param $designID
     * @param $readOnly
     */
    public function __construct($designID, $readOnly)
    {
        $this->designID = $designID;
        $this->readOnly = $readOnly;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $messages = SystemDesignMessage::with(['userType', 'files'])->where('system_design_id', $this->designID)->get();
        return view('components.design-messages', ["messages" => $messages, "id" => $this->designID, 'readOnly' => $this->readOnly]);
    }
}
