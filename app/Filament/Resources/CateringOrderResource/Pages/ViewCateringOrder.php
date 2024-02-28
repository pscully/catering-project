<?php

namespace App\Filament\Resources\CateringOrderResource\Pages;

use App\Filament\Resources\CateringOrderResource;
use App\Filament\Widgets\CateringOrderTotals;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;


class ViewCateringOrder extends ViewRecord
{
    protected static string $resource = CateringOrderResource::class;

    protected function getHeaderActions(): array
    {
       return [
           Action::make('Accept Order')
               ->requiresConfirmation()
               ->color('success')
                ->action(fn () => dd($this->record->acceptOrder())),
           Action::make('Decline Order')
            ->color('danger'),
       ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
                CateringOrderTotals::make(),
        ];
    }
}
