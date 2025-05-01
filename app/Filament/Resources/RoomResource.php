<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Models\Building;
use App\Models\Floor;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationGroup = 'Manajemen Logistik';

    protected static ?string $label = 'Kamar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pilih Gedung')
                    ->description('Silakan pilih gedung terlebih dahulu')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        Forms\Components\Select::make('building_id')
                            ->label('Gedung')
                            ->options(Building::all()->pluck('name', 'id'))
                            ->reactive()
                            ->searchable()
                            ->required()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (! $state) {
                                    $set('floors', []);

                                    return;
                                }

                                $set(
                                    'floors',
                                    Floor::where('building_id', $state)
                                        ->with(['rooms' => fn ($query) => $query->select('id', 'floor_id', 'code')])
                                        ->get()
                                        ->sortBy('floor')
                                        ->map(fn ($floor) => [
                                            'id' => $floor->id,
                                            'floor' => $floor->floor,
                                            'max_capacity' => $floor->max_capacity,
                                            'price' => (int) $floor->price,
                                            'rooms' => $floor->rooms
                                                ->sortBy('updated_at')
                                                ->map(fn ($room) => [
                                                    'id' => $room->id,
                                                    'floor_id' => $room->floor_id,
                                                    'code' => $room->code,
                                                ])->values()->toArray(),
                                        ])
                                        ->values()
                                        ->toArray()
                                );
                            }),

                    ]),

                Forms\Components\Repeater::make('floors')
                    ->label('Daftar Lantai')
                    ->deletable(true)
                    ->addable(true)
                    ->collapsible()
                    ->hidden(fn (callable $get) => empty($get('building_id')))
                    ->itemLabel(fn (array $state): ?string => "Lantai {$state['floor']} - Kapasitas: {$state['max_capacity']}, Harga: Rp".number_format($state['price'] ?? 0))
                    ->schema([
                    Forms\Components\Hidden::make('id'),
                    Forms\Components\Hidden::make('building_id')
                            ->default(fn (callable $get) => $get('../../building_id')),
                    Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('floor')
                                    ->label('Nomor Lantai')
                                    ->integer()
                                    ->required(),
                                Forms\Components\TextInput::make('max_capacity')
                                    ->label('Kapasitas Maksimum/Kamar')
                                    ->integer()
                                    ->minValue(1)
                                    ->required(),
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga per Kamar/Tahun/Orang')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->minValue(0)
                                    ->required()
                                    ->step(0.01),
                                // Forms\Components\FileUpload::make('image_url')
                                //   ->label('Gambar Kamar pada Lantai')
                                //   ->disk('s3')
                                //   ->visibility('private')
                                //   ->directory('floors')
                                //   ->image()
                                //   ->required()
                                //   ->columnSpanFull(),
                            ])->columns(3),

                    Forms\Components\Section::make('Daftar Kamar')
                            ->description(fn ($state) => 'Total kamar: '.(isset($state['rooms']) ? count($state['rooms']) : 0))
                            ->collapsible()
                            ->schema([
                                Forms\Components\Repeater::make('rooms')
                                    ->label('Tambah Kamar')
                                    ->schema([
                                        Forms\Components\Hidden::make('id'),

                                        Forms\Components\TextInput::make('code')
                                            ->label('Kode Kamar')
                                            ->placeholder('Contoh: J-05')
                                            ->required()
                                            ->rules(function (callable $get) {
                                                $roomId = $get('id');
                                                $floorId = $get('../../id') ?? null;
                                                $buildingId = $get('../../building_id') ?? null;

                                                return [
                                                    'required',
                                                    Rule::unique('rooms', 'code')
                                                        ->where(
                                                            fn ($query) => $query
                                                                ->where('floor_id', $floorId)
                                                                ->where('building_id', $buildingId)
                                                        )
                                                        ->ignore($roomId),
                                                ];
                                            }),

                                    ])
                                    ->addable()
                                    ->deletable(),
                            ]),
                ])
                    ->columnSpanFull()
                    ->deleteAction(
                        fn (FormAction $action): FormAction => $action
                            ->requiresConfirmation()
                            ->modalHeading('Hapus Lantai?')
                            ->modalDescription(fn (array $state): string => 'Terdapat '.(isset($state['rooms']) ? count($state['rooms']) : 0).
                              ' kamar pada lantai ini. Menghapus lantai ini akan menghapus semua kamar tersebut.')
                            ->modalSubmitActionLabel('Ya, Hapus Lantai')
                    )
                    ->default(function (callable $get) {
                        $building_id = $get('building_id');
                        if (! $building_id) {
                            return [];
                        }

                        return Floor::where('building_id', $building_id)
                            ->with('rooms')
                            ->get()
                            ->toArray();
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->sortable(),
                Tables\Columns\TextColumn::make('building.name')->label('Gedung')->sortable(),
                Tables\Columns\TextColumn::make('floor.floor')->label('Lantai')->sortable(),
                Tables\Columns\TextColumn::make('floor.price')->label('Harga/Tahun')->sortable()->formatStateUsing(function ($state) {
                    return 'Rp '.number_format($state, 0, ',', '.');
                }),
                Tables\Columns\TextColumn::make('floor.max_capacity')->label('Kapasitas')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime('d M Y, H:i')->sortable(),
            ])
            ->actions([
                TableAction::make('lihatGambar')
                    ->label('Lihat Gambar')
                    ->icon('heroicon-o-photo')
                    ->infolist([
                        ImageEntry::make('floor.image_url')
                            ->disk('s3')
                            ->columnSpanFull(),
                    ])->modalSubmitAction(false) // Hilangkan tombol Submit
                    ->modalCancelAction(false), // Hilangkan tombol Cancel,
        ])
            ->filters([
                Tables\Filters\SelectFilter::make('building_id')
                    ->label('Gedung')
                    ->options(Building::pluck('name', 'id'))
                    ->attribute('building_id'),
        ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
        ];
    }
}
