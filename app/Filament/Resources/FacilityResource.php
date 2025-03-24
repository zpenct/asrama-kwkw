<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Facility;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FacilityResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FacilityResource\RelationManagers;

class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;

    protected static ?string $navigationIcon = 'heroicon-o-check';
    protected static ?string $navigationGroup = 'Manajemen Logistik';
    protected static ?string $label = 'Fasilitas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->placeholder('Contoh: AC')
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->placeholder('Contoh: 2 AC per kamar')
                    ->maxLength(255)
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image_url')
                    ->label('Gambar')
                    ->disk('s3')
                    ->visibility('private')
                    ->directory('buildings')
                    ->image()
                    ->imagePreviewHeight('250')
                    ->maxSize(1024)
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('description')->searchable(),
                Tables\Columns\TextColumn::make('image_url')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFacilities::route('/'),
            'create' => Pages\CreateFacility::route('/create'),
            'edit' => Pages\EditFacility::route('/{record}/edit'),
        ];
    }

    public static function getRedirectUrl(): string
    {
        return FacilityResource::getUrl('index');
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();

        // ONLY SUPERADMIN AND ADMIN CAN CREATE FACILITY
        return $user && $user->role !== "MAHASISWA";
    }

    public static function canDelete($record): bool
    {
        $user = Auth::user();

        // ONLY SUPERADMIN AND ADMIN CAN DELETE FACILITY
        return $user && $user->role !== "MAHASISWA";
    }
}