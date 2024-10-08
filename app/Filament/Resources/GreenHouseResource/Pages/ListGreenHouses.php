<?php

namespace App\Filament\Resources\GreenHouseResource\Pages;

use App\Filament\Resources\GreenHouseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGreenHouses extends ListRecords
{
    protected static string $resource = GreenHouseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
