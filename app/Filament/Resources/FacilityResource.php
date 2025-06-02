<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacilityResource\Pages;
use App\Models\Facility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

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
                    ->directory('facilities')
                    ->image()
                    ->imagePreviewHeight('250')
                    ->maxSize(1024)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('description')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Action::make('lihatGambar')
                    ->label('Lihat Gambar')
                    ->icon('heroicon-o-photo')
                    ->infolist([
                        ImageEntry::make('image_url')
                            ->disk('s3')
                            ->columnSpanFull(),
                    ])->modalSubmitAction(false)
                    ->modalCancelAction(false),
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
}
