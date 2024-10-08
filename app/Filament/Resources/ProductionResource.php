<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductionResource\Pages;
use App\Filament\Resources\ProductionResource\RelationManagers;
use App\Models\Production;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class ProductionResource extends Resource
{
    protected static ?string $model = Production::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationLabel = "Produksi";
    protected static ?string $breadcrumb = "Produksi";
    protected static ?string $navigationGroup = 'Production';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('printing_date')->label("Tanggal & Jam Di Sablon"),
                Forms\Components\DateTimePicker::make('deadline_for_returning_printing')->label("Tanggal harus kembali sablon"),
                Forms\Components\DateTimePicker::make('cmt_pickup_date')->label("Tanggal CMT ambil kerjaan"),
                Forms\Components\DateTimePicker::make('cmt_completion_date')->label("Tanggal CMT harus selesai"),
                Forms\Components\Select::make('product_id')->label("Produk")->relationship("product", "name")->columnSpanFull()->preload()->searchable()->createOptionForm([
                    Forms\Components\TextInput::make('qty')->label("QTY")->numeric()->minValue(1),
                    Forms\Components\TextInput::make('category')->label("Jenis"),
                    Forms\Components\TextInput::make('name')->label("Nama Produk")->columnSpanFull(),

                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('printing_date')
                    ->label('Tanggal & Jam Cetak')
                    ->dateTime('l, d F Y H:i:s')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('deadline_for_returning_printing')
                    ->label('Batas Waktu Pengembalian Cetak')
                    ->dateTime('l, d F Y H:i:s')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($record) {
                        Carbon::setLocale('id');
                        $formattedDate = Carbon::parse($record->deadline_for_returning_printing)->format('l, d F Y H:i:s');

                        $start = Carbon::parse($record->printing_date);
                        $end = Carbon::parse($record->deadline_for_returning_printing);
                        $hoursLeft = $start->diffInHours($end, false);

                        if ($hoursLeft <= 0) {
                            return "Expired pada {$formattedDate}";
                        }

                        return "{$formattedDate} - " . $start->diffForHumans($end, true) . ' lagi';
                    })
                    ->badge()
                    ->color(function ($record) {
                        Carbon::setLocale('id');

                        $start = Carbon::parse($record->printing_date);
                        $end = Carbon::parse($record->deadline_for_returning_printing);
                        $hoursLeft = $start->diffInHours($end, false);

                        if ($hoursLeft > 5) {
                            return 'success';
                        } elseif ($hoursLeft > 3) {
                            return 'warning';
                        } elseif ($hoursLeft > 0) {
                            return 'danger';
                        } else {
                            return 'danger';
                        }
                    })
                    ->sortable(),

                TextColumn::make('cmt_pickup_date')
                    ->label('Tanggal & Jam Ambil CMT')
                    ->dateTime('l, d F Y H:i:s')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('cmt_completion_date')
                    ->label('Tanggal & Jam Selesai CMT')
                    ->dateTime('l, d F Y H:i:s')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($record) {
                        Carbon::setLocale('id');
                        $formattedCompletionDate = Carbon::parse($record->cmt_completion_date)->format('l, d F Y H:i:s');
                        $formattedPickupDate = Carbon::parse($record->cmt_pickup_date)->format('l, d F Y H:i:s');

                        if ($record->cmt_pickup_date && $record->cmt_completion_date) {
                            $pickup = Carbon::parse($record->cmt_pickup_date);
                            $completion = Carbon::parse($record->cmt_completion_date);
                            $duration = $pickup->diffInHours($completion, false);

                            return "{$formattedCompletionDate} - Durasi: {$duration} jam";
                        }

                        return "{$formattedCompletionDate} - Durasi: Belum tersedia";
                    })
                    ->badge()
                    ->color(function ($record) {
                        if ($record->cmt_pickup_date && $record->cmt_completion_date) {
                            $pickup = Carbon::parse($record->cmt_pickup_date);
                            $completion = Carbon::parse($record->cmt_completion_date);
                            $duration = $pickup->diffInHours($completion, false);

                            if ($duration > 5) {
                                return 'success';
                            } elseif ($duration > 3) {
                                return 'warning';
                            } elseif ($duration > 0) {
                                return 'danger';
                            }
                        }

                        return 'danger'; // Default color jika tidak ada pickup date atau completion date
                    })
                    ->sortable(),
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
            'index' => Pages\ListProductions::route('/'),
        ];
    }
}
