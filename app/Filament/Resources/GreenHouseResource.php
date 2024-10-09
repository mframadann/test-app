<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GreenHouseResource\Pages;
use App\Filament\Resources\GreenHouseResource\RelationManagers;
use App\Http\Livewire\ReminderBadge;
use App\Models\GreenHouse;
use Carbon\CarbonInterval;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DoneAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class GreenHouseResource extends Resource
{
    protected static ?string $model = GreenHouse::class;
    protected static ?string $navigationLabel = "Rumah Kaca";
    protected static ?string $breadcrumb = "Rumah Kaca";
    protected static ?string $navigationGroup = 'Production';
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('spk_creation_date')->label("Tanggal & Jam Pembuatan SPK"),
                Forms\Components\DateTimePicker::make('deadline_for_slaughterhouse_entry')->label("Batas Waktu Masuk Rumah Potong"),
                Forms\Components\Select::make('product_id')->label("Produk")->relationship("product", "name")->columnSpanFull()->preload()->searchable()->createOptionForm([
                    Forms\Components\TextInput::make('qty')->label("QTY")->numeric()->minValue(1),
                    Forms\Components\TextInput::make('category')->label("Jenis"),
                    Forms\Components\TextInput::make('name')->label("Nama Produk")->columnSpanFull(),

                ]),
                Forms\Components\DateTimePicker::make('entry_time_to_slaughterhouse')->label("Waktu masuk rumah potong")->hiddenOn("create")->columnSpanFull(),
                Forms\Components\Select::make('is_done')->label("Status")->options([
                    true => "Selesai",
                    false => "Proses"
                ])->hiddenOn(["create"])->columnSpanFull(),


            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("spk_creation_date")
                    ->label('Tanggal & Jam pembuatan SPK')
                    ->dateTime('l, d F Y H:i:s')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('deadline_for_slaughterhouse_entry')
                    ->label('Batas Waktu Masuk Rumah Potong')
                    ->dateTime('l, d F Y H:i:s')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function (GreenHouse $record) {
                        $deadline = Carbon::parse($record->deadline_for_slaughterhouse_entry);
                        $formattedDate = $deadline->isoFormat('dddd, D MMMM YYYY HH:mm:ss');
                        $now = Carbon::now();


                        if ($deadline->isPast() && !$record->is_done) {
                            return "Expired pada {$formattedDate}";
                        }

                        if ($record->is_done) {
                            return "Selesai pada {$formattedDate}";
                        }

                        $timeLeftHumanReadable = $deadline->diffForHumans($now, true);
                        return "{$formattedDate} - {$timeLeftHumanReadable} lagi";
                    })
                    ->badge()
                    ->color(function (GreenHouse $record) {
                        $start = Carbon::parse($record->deadline_for_slaughterhouse_entry);
                        $end = Carbon::now();

                        if ($start->isPast() && !$record->is_done) {
                            return 'danger';
                        }

                        if ($record->is_done) {
                            return "success";
                        }

                        $hoursLeft = $start->diffInHours($end, false);
                        if ($hoursLeft > 5) {
                            return 'success';
                        } elseif ($hoursLeft > 3) {
                            return 'warning';
                        } elseif ($hoursLeft > 0) {
                            return 'danger';
                        } else {
                            return 'expired';
                        }
                    })
                    ->sortable(),
                TextColumn::make('is_done')
                    ->label('Status')
                    ->formatStateUsing(function (GreenHouse $record) {
                        return $record->is_done ? "Selesai" : "Proses";
                    })
                    ->badge()
                    ->color(function (GreenHouse $record) {
                        return $record->is_done ? "success" : "warning";
                    })
                    ->sortable(),
                TextColumn::make('entry_time_to_slaughterhouse')
                    ->label('Tanggal Masuk Rumah Potong')
                    ->dateTime('l, d F Y H:i:s')
                    ->sortable()
                    ->searchable()
                    ->placeholder("--"),
                Tables\Columns\TextColumn::make('required_duration')
                    ->label('Berapa lama waktu yg di perlukan')
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
                    })->placeholder("--"),
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
            'index' => Pages\ListGreenHouses::route('/'),
        ];
    }
}
