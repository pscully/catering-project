<?php

namespace App\Forms;

use Filament\Forms\Components\TextInput;

class CateringMenuSection
{
public function build($cateringProducts): array
{
    $products = [];

    foreach ($cateringProducts as $product) {
        $products[] = TextInput::make($product->sku)
            ->label($product->name)
            ->placeholder('0')
            ->numeric()
            ->step(1)
            ->live()
            ->default(0);
    }

    return $products;
}
}
