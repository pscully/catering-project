<?php

namespace App\Forms;

use Filament\Forms\Components\TextInput;

class CateringCustomerDetailsSection
{
    public function build(): array
    {
        return [
            TextInput::make('first_name')
                ->label('First Name')
                ->required(),
            TextInput::make('last_name')
                ->label('Last Name')
                ->required(),
            TextInput::make('email')
                ->label('Email')
                ->required(),
            TextInput::make('phone_number')
                ->label('Phone')
                ->required(),
        ];
    }

}
