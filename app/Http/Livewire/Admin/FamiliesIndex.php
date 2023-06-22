<?php

namespace App\Http\Livewire\Admin;

use App\Models\admin\Family;
use Livewire\Component;
use Livewire\WithPagination;

class FamiliesIndex extends Component
{
    public $search;

    protected $paginationTheme = 'bootstrap';

    use WithPagination;

    /*public function updatingSearch(){
        $this->resetPage();
    }*/

    public function render()
    {
        $families = Family::where('name','like','%'.$this->search.'%')->paginate(5);
        return view('livewire.admin.families-index', compact('families'));
    }
}
