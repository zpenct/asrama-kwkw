<?php

namespace App\Filament\Resources\FacilityResource\Pages;

use App\Filament\Resources\FacilityResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateFacility extends CreateRecord
{
    protected static string $resource = FacilityResource::class;

    protected function getRedirectUrl(): string
    {
        return FacilityResource::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Fasilitas berhasil ditambahkan!');
    }
}
