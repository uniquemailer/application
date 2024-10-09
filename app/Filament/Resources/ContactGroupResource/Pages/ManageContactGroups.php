<?php

namespace App\Filament\Resources\ContactGroupResource\Pages;

use App\Filament\Resources\ContactGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageContactGroups extends ManageRecords
{
    protected static string $resource = ContactGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
