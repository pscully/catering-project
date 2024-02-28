<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CateringOrderResource\Pages;
use App\Models\CateringOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CateringOrderResource extends Resource
{
    protected static ?string $model = CateringOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('catering_status_id')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\DatePicker::make('order_date'),
                Forms\Components\TextInput::make('order_time')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('closest_location')
                    ->maxLength(255),
                Forms\Components\TextInput::make('pickup_first_name')
                    ->maxLength(255),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('number_people')
                    ->numeric(),
                Forms\Components\Toggle::make('delivery'),
                Forms\Components\Toggle::make('setup')
                    ->required(),
                Forms\Components\TextInput::make('coffee_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('charge_id')
                    ->maxLength(255),
                Forms\Components\TextInput::make('pp_capture_id')
                    ->maxLength(255),
                Forms\Components\TextInput::make('total')
                    ->numeric(),
                Forms\Components\TextInput::make('refunded_sum')
                    ->numeric(),
                Forms\Components\FileUpload::make('image_filename')
                    ->image(),
                Forms\Components\DateTimePicker::make('notified_at'),
                Forms\Components\DateTimePicker::make('status_updated_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Customer Email')
                    ->sortable()
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('location.name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('delivery')
                    ->boolean(),
                Tables\Columns\IconColumn::make('setup')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCateringOrders::route('/'),
            'create' => Pages\CreateCateringOrder::route('/create'),
            'view' => Pages\ViewCateringOrder::route('/{record}'),
            'edit' => Pages\EditCateringOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
