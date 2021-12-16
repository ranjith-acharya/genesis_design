<?php

namespace App\View\Components;

use App\Project;
use Illuminate\View\Component;

class DesignCostAddition extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $projectID;
    public $design;

    public function __construct($projectID, $design)
    {
        $this->projectID = $projectID;
        $this->design = $design;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $project = Project::with('designs.type')->find($this->projectID);
        return view('components.design-cost-addition', ["project" => $project, "design" => $this->design]);
    }
}
