<?php

namespace App\Filament\Resources\CateringProductResource\Pages;

use App\Filament\Resources\CateringProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCateringProducts extends ListRecords
{
    protected static string $resource = CateringProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
