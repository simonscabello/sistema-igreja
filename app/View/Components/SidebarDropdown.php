<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SidebarDropdown extends Component
{
    public function __construct(
        public string $title,
        public string $icon
    ) {}

    public function render()
    {
        return view('components.sidebar-dropdown');
    }
}
