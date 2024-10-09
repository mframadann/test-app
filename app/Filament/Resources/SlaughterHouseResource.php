<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SlaughterHouseResource\Pages;
use App\Filament\Resources\SlaughterHouseResource\RelationManagers;
use App\Models\SlaughterHouse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SlaughterHouseResource extends Resource
{
    protected static ?string $model = SlaughterHouse::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationLabel = "Rumah Potong";
    protected static ?string $breadcrumb = "Rumah Potong";
    protected static ?string $navigationGroup = 'Production';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('entry_time_to_slaughterhouse')->label("Tanggal & jam masuk rumah potong")->columnSpanFull(),
                Forms\Components\Select::make('product_id')->label("Produk")->relationship("product", "name")->columnSpanFull()->preload()->searchable()->createOptionForm([
                    Forms\Components\TextInput::make('qty')->label("QTY")->numeric()->minValue(1),
                    Forms\Components\TextInput::make('category')->label("Jenis"),
                    Forms\Components\TextInput::make('name')->label("Nama Produk")->columnSpanFull(),

                ]),
                Forms\Components\DateTimePicker::make('finish_time_of_slaughter')->hiddenOn("create")->label("Tanggal dan jam selesai potong")->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('entry_time_to_slaughterhouse')
                    ->label('Tanggal & Jam Masuk')
                    ->dateTime('l, d F Y H:i:s'),
                Tables\Columns\TextColumn::make('finish_time_of_slaughter')
                    ->label('Tanggal & Jam Selesai')
                    ->dateTime('l, d F Y H:i:s')
                    ->placeholder("--"),
                Tables\Columns\TextColumn::make('duration_of_slaughter')
                    ->label('Durasi Pemotongan')
                    ->placeholder("--")
                    ->formatStateUsing(function ($state) {
                        list($hours, $minutes, $seconds) = explode(':', $state);

                        $formattedDuration = [];

                        if ($hours > 0) {
                            $formattedDuration[] = "$hours jam";
                        }

                        if ($minutes > 0) {
                            $formattedDuration[] = "$minutes menit";
                        }

                        if ($seconds > 0) {
                            $formattedDuration[] = "$seconds detik";
                        }

                        return implode(' ', $formattedDuration) ?: '0 detik';
                    }),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Nama Produk'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSlaughterHouses::route('/'),

        ];
    }
}
