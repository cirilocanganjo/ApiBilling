<?php

namespace App\Livewire\Dashboard;
use \Illuminate\Contracts\View\View;
use Livewire\Component;

class DashboardComponent extends Component
{

    public function mount ()
    {

    }

    public function render() : View
    {
        return view('livewire.dashboard.dashboard-component')->layout('layouts.app')
        ->with([

        ]);
    }
}
