<?php

namespace App\Filament\Resources\BuildingResource\Pages;

use App\Filament\Resources\BuildingResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateBuilding extends CreateRecord
{
    protected static string $resource = BuildingResource::class;

    protected static bool $canCreateAnother = false;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Gedung berhasil ditambahkan!')
            ->body('ilahkan isi informasi lainnya untuk gedung ini');
    }
}
