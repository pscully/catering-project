<?php

namespace App\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;

class CateringPaymentDetailsSection
{

    public function build(): array
    {
        return [
            TextInput::make('card-holder-name'),
        ];
    }

}
