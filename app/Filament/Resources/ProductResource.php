<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationGroup = 'Production';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('qty')->label("QTY")->numeric()->minValue(1),
                Forms\Components\TextInput::make('category')->label("Jenis"),
                Forms\Components\TextInput::make('name')->label("Nama Produk")->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("qty")->label("QTY"),
                TextColumn::make("name")
                    ->searchable(),
                TextColumn::make("category")->sortable(),
                TextColumn::make("created_at")
                    ->label('Tanggal Dibuat')
                    ->dateTime('l, d F Y H:i:s')
                    ->sortable()
                    ->searchable(),
                TextColumn::make("updated_at")
                    ->label('Terakhir Diperbarui')
                    ->dateTime('l, d F Y H:i:s')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
        ];
    }
}
