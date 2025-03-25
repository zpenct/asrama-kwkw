<?php

namespace App\Filament\Resources\RoomResource\Pages;

use App\Filament\Resources\RoomResource;
use App\Models\Room;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditRoom extends EditRecord
{
    protected static string $resource = RoomResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        DB::beginTransaction();
        try {
            $buildingId = $data['building_id'];
            $existingRooms = Room::where('building_id', $buildingId)->pluck('id')->toArray();
            $updatedRooms = [];

            foreach ($data['floors'] as $floor) {
                foreach ($floor['rooms'] as $room) {
                    $updatedRooms[] = [
                        'id' => $room['id'] ?? null, // Jika ada ID, berarti update; jika tidak, insert
                        'building_id' => $buildingId,
                        'floor_id' => $floor['id'],
                        'code' => $room['code'],
                        'updated_at' => now(),
                    ];
                }
            }

            // Hapus kamar yang tidak ada dalam update
            $idsToDelete = array_diff($existingRooms, array_column($updatedRooms, 'id'));
            Room::whereIn('id', $idsToDelete)->delete();

            // Bulk update atau insert
            Room::upsert($updatedRooms, ['id'], ['building_id', 'floor_id', 'code', 'updated_at']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
