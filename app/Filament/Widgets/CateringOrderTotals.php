<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class CateringOrderTotals extends BaseWidget
{
    public ?Model $record = null;

    protected function getStats(): array
    {
        return [
            Stat::make('Order Total', "$".$this->record->total),
            Stat::make('Delivery Charge', "$".$this->record->delivery_fee),
            Stat::make('Setup Fee', $this->record->setup ? '$20' : '$0'),
        ];
    }
}
