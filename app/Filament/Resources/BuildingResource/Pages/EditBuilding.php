<?php

namespace App\Filament\Resources\BuildingResource\Pages;

use App\Filament\Resources\BuildingResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditBuilding extends EditRecord
{
    protected static string $resource = BuildingResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $building = \App\Models\Building::find($data['id']);

        return [
            'id' => $building->id,
            'name' => $building->name,
            'type' => $building->type,
            'description' => $building->description,
            'image_url' => $building->image_url,
            'facilities' => $building->facilities->pluck('id')->toArray(),
            'floors' => $building->floors->map(fn ($floor) => [
                'floor' => $floor->floor,
                'max_capacity' => $floor->max_capacity,
                'price' => $floor->price,
            ])->toArray(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Gedung berhasil diedit!')
            ->body('Silahkan tambahkan kamar-kamar');
    }
}
