<?php

namespace App\Filament\Resources\CateringOrderResource\Pages;

use App\Filament\Resources\CateringOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCateringOrder extends ViewRecord
{
    protected static string $resource = CateringOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
