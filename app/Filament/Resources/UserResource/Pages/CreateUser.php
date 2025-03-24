<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Support\Facades\Hash;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // HASH PASSWORD IF FILLED
        if (!empty($data['password']) && Hash::needsRehash($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // MAKE SURE THE ROLE IS NOT NULL
        $data['role'] = $data['role'] ?? 'MAHASISWA';
        $data['is_first'] = $data['is_first'] ?? 0;

        return User::create($data);
    }
}