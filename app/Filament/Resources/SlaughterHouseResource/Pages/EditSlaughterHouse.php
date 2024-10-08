<?php

namespace App\Filament\Resources\SlaughterHouseResource\Pages;

use App\Filament\Resources\SlaughterHouseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSlaughterHouse extends EditRecord
{
    protected static string $resource = SlaughterHouseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
