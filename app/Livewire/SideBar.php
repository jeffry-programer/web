<?php

namespace App\Livewire;

use App\Models\Table;
use Livewire\Component;

class SideBar extends Component
{
    public function render()
    {
        $tables = Table::where('type', 1)->orderBy('label', 'ASC')->get();
        $tables2 = Table::where('type', 2)->get();
        return view('livewire.side-bar', ['tables' => $tables, 'tables2' => $tables2]);
    }
}
