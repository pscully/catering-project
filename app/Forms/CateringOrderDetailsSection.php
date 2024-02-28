<?php

namespace App\Forms;

use App\Enums\cateringOrderTimes;
use App\Models\Location;
use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;

class CateringOrderDetailsSection
{
    public function build(): array
    {
        return [
            ToggleButtons::make('delivery')
                ->label('Add Delivery?')
                ->live()
                ->required()
                ->boolean()
                ->colors([
                    false => 'warning',
                    true => 'success',
                ])
                ->grouped(),
            ToggleButtons::make('setup')
                ->label('Add Setup?')
                ->boolean()
                ->colors([
                    '0' => 'warning',
                    '1' => 'success',
                ])
                ->grouped()
                ->hidden(fn(Get $get): bool => !$get('delivery')),
            TextInput::make('delivery_street')
                ->label('Street Address')
                ->hidden(fn(Get $get): bool => !$get('delivery')),
            TextInput::make('apartment_suite')
                ->label('Apartment / Suite')
                ->hidden(fn(Get $get): bool => !$get('delivery')),
            TextInput::make('delivery_city')
                ->label('City')
                ->hidden(fn(Get $get): bool => !$get('delivery')),
            TextInput::make('delivery_state')
                ->label('State')
                ->hidden(fn(Get $get): bool => !$get('delivery')),
            TextInput::make('delivery_zip')
                ->label('Zip Code')
                ->hidden(fn(Get $get): bool => !$get('delivery')),
            ToggleButtons::make('is_business')
                ->label('Is This A Business Address?')
                ->boolean()
                ->colors([
                    '0' => 'warning',
                    '1' => 'success',
                ])
                ->grouped()
                ->hidden(fn(Get $get): bool => !$get('delivery')),
            TextInput::make('business_name')
                ->label('Business Name')
                ->hidden(fn(Get $get): bool => !$get('delivery')),
            Select::make('closest_location')
                ->label('Closest Location')
                ->options(Location::pluck('name', 'id')->toArray())
                ->required(),
            DatePicker::make('order_date')
                ->label('Order Date')
                ->format('m/d/Y')
                ->helperText('Pickup or Delivery Date')
                ->after('today')
                ->required(),
            Select::make('order_time')
                ->label('Time')
                ->options(CateringOrderTimes::class)
                ->required()
                ->rules([fn(Get $get): Closure => function (string $attribute, mixed $value, Closure $fail) use ($get): void {
                    $tomorrow = Carbon::tomorrow()->startOfDay();
                    $selectedDate = Carbon::parse($get('order_date'))->startOfDay();

                    if ($selectedDate->equalTo($tomorrow) && strtotime($value) < strtotime('12:00 PM')) {
                        $fail('When placing a next day order, pickup time must be at 12:00 PM or later.');
                    }

                }]),
            TextInput::make('number_people')
                ->label('Number of People')
                ->numeric()
                ->step(1),
            TextInput::make('pickup_first_name')
                ->label('Optional Pickup First Name')
                ->helperText('Who will pickup the order?'),
            TextArea::make('notes')
                ->label('Notes')
                ->helperText('Any special instructions?'),
        ];
    }
}
