<?php

namespace App\Livewire;

use App\Models\CateringOrder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Table;
use Filament\Tables;
use Livewire\Component;

class ListCateringOrders extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(CateringOrder::query()->where('user_id', auth()->user()->id))
            ->columns([
                TextColumn::make('user.email'),
                Tables\Columns\TextColumn::make('cateringStatus.name')
                    ->label('Order Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'NEW' => 'success',
                        'IN PROGRESS' => 'warning',
                        'COMPLETED' => 'info',
                        'CANCELLED' => 'danger',
                        'DECLINED' => 'gray',
                        'DELIVERED' => 'primary',
                        'REFUNDED' => 'red',
                        'FULFILLED' => 'green',

                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('order_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_time')
                    ->searchable(),
                Tables\Columns\TextColumn::make('closest_location.name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('delivery')
                    ->boolean(),
                Tables\Columns\IconColumn::make('setup')
                    ->boolean(),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->form([
                        TextInput::make('pickup_first_name')
                            ->label('Pickup Person')
                            ->required()
                            ->maxLength(255),
                    ]),
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.list-catering-orders');
    }
}
