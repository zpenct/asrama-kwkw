<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Jobs\SyncUsersJob;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('Sync Users')
                ->label('Sinkronisasi User')
                ->color('success')
                ->icon('heroicon-o-arrow-path')
                ->requiresConfirmation()
                ->action(function () {
                    // Dispatch Job untuk sinkronisasi user
                    dispatch(new SyncUsersJob);

                    // Tampilkan notifikasi sukses
                    Notification::make()
                        ->success()
                        ->title('Sinkronisasi Berjalan')
                        ->body('Proses sinkronisasi telah dimulai di background.')
                        ->send();
                }),
        ];
    }
}
