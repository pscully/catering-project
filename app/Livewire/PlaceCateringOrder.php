<?php

namespace App\Livewire;

use App\Models\CateringOrder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PlaceCateringOrder extends Component
{
    public function render()
    {
        return view('livewire.place-catering-order');
    }
}
