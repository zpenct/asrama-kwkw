<?php

namespace App\Filament\Resources\RoomResource\Pages;

use App\Filament\Resources\RoomResource;
use App\Models\Floor;
use App\Models\Room;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class CreateRoom extends CreateRecord
{
    protected static string $resource = RoomResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        DB::beginTransaction();
        try {
            $building_id = $data['building_id'];
            $floorIds = [];
            $roomIds = [];
            $newRoom = null;

            foreach ($data['floors'] as &$floor) {
                $floorId = $floor['id'] ?? null;

                if (! empty($floor['id'])) {
                    Floor::where('id', $floor['id'])->update([
                        'floor' => $floor['floor'],
                        'max_capacity' => $floor['max_capacity'],
                        'price' => (string) $floor['price'],
                        'image_url' => is_array($floor['image_url']) ? $floor['image_url'][0] ?? null : $floor['image_url'],
                    ]);
                } else {
                    $newFloor = Floor::create([
                        'building_id' => $building_id,
                        'floor' => $floor['floor'],
                        'max_capacity' => $floor['max_capacity'],
                        'price' => (string) $floor['price'],
                        'image_url' => is_array($floor['image_url']) ? $floor['image_url'][0] ?? null : $floor['image_url'],
                    ]);
                    $floorId = $newFloor->id;
                    $floor['id'] = $newFloor->id;
                }

                $floorIds[] = $floorId;

                if (! isset($floor['rooms']) || empty($floor['rooms'])) {
                    continue;
                }

                foreach ($floor['rooms'] as &$room) {
                    if (! isset($room['code']) || empty($room['code'])) {
                        continue;
                    }

                    if (! isset($room['floor_id']) || empty($room['floor_id'])) {
                        $room['floor_id'] = $floorId;
                    }

                    if (! empty($room['id'])) {
                        $updatedRoom = Room::where('id', $room['id'])->update([
                            'code' => $room['code'],
                        ]);
                        $roomIds[] = $room['id'];
                    } else {
                        $newRoom = Room::create([
                            'building_id' => $building_id,
                            'floor_id' => $room['floor_id'],
                            'code' => $room['code'],
                        ]);

                        $room['id'] = $newRoom->id;
                        $roomIds[] = $newRoom->id;
                    }
                }
            }

            Floor::where('building_id', $building_id)
                ->whereNotIn('id', array_filter($floorIds))
                ->delete();

            Room::where('building_id', $building_id)
                ->whereNotIn('id', array_filter($roomIds))
                ->whereNull('deleted_at')
                ->forceDelete();

            DB::commit();

            // Notifikasi sukses
            Notification::make()
                ->title('Berhasil!')
                ->body('Data lantai dan kamar berhasil diperbarui.')
                ->success()
                ->send();

            if ($newRoom) {
                return $newRoom;
            } elseif (! empty($roomIds)) {
                return Room::find($roomIds[0]);
            } else {
                throw new \Exception('Tidak ada kamar yang dibuat atau di-update.');
            }
        } catch (QueryException $e) {
            DB::rollBack();

            if ($e->getCode() == '23000' && str_contains($e->getMessage(), 'Duplicate entry')) {
                preg_match("/Duplicate entry '([^']+)' for key 'rooms.rooms_code_floor_id_unique'/", $e->getMessage(), $matches);
                $duplicateEntry = $matches[1] ?? 'Kode kamar tidak diketahui';

                Notification::make()
                    ->title('Gagal Menyimpan!')
                    ->body("Kode kamar <strong>{$duplicateEntry}</strong> sudah ada dalam lantai ini. Silakan gunakan kode lain.")
                    ->danger()
                    ->send();
            } else {
                Notification::make()
                    ->title('Gagal Menyimpan!')
                    ->body('Terjadi kesalahan: '.$e->getMessage())
                    ->danger()
                    ->send();
            }

            return new Room;
        }
    }

    protected function getRedirectUrl(): string
    {
        return RoomResource::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return '';
    }

    protected function getCreatedNotificationMessage(): ?string
    {
        return '';
    }
}
