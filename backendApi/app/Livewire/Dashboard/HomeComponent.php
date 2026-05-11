<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;

class HomeComponent extends Component
{

    #[Layout('layouts.dashboard.app')]
    public function render() : View
    {
        return view('livewire.dashboard.home-component');
    }


}
