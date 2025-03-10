<?php

namespace App\Filament\Resources;

use Log;
use Filament\Forms;
use App\Models\Building;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\BuildingResource\Pages;

class BuildingResource extends Resource
{
	protected static ?string $model = Building::class;

	protected static ?string $navigationIcon = 'heroicon-o-building-office';
	protected static ?string $navigationGroup = 'Manajemen Logistik';
	protected static ?string $label = 'Gedung';

	public static function form(Form $form): Form
	{
		return $form
			->schema([
				Forms\Components\Wizard::make([
					// Step 1: Informasi Umum
					Forms\Components\Wizard\Step::make('Informasi Umum')
						->description('Informasi Umum Gedung')
						->schema([
							Forms\Components\TextInput::make('name')
								->label('Nama Gedung')
								->required(),
							Forms\Components\Select::make('type')
								->label('Tipe Gedung')
								->options([
									'PUTRA' => 'Putra',
									'PUTRI' => 'Putri',
								])
								->required(),
							Forms\Components\RichEditor::make('description')
								->label('Deskripsi')
								->required(),
							Forms\Components\FileUpload::make('image_url')
								->label('Gambar Gedung')
								->disk('s3')
								->visibility('private')
								->directory('buildings')
								->image()
								->required(),
						])
						->afterStateUpdated(function ($state, callable $set) {
							if (!isset($state['id'])) {
								// Pastikan image_url bukan array
								$imagePath = is_array($state['image_url']) ? $state['image_url'][0] ?? null : $state['image_url'];

								if ($imagePath) {
									$building = \App\Models\Building::create([
										'name' => $state['name'],
										'description' => $state['description'],
										'type' => $state['type'],
										'image_url' => $imagePath,
									]);
									$set('id', $building->id);
								}
							}
						}),

					// Step 2: Fasilitas
					Forms\Components\Wizard\Step::make('Fasilitas')
						->description('Fasilitas yang tersedia')
						->schema([
							Forms\Components\CheckboxList::make('facilities')
								->label('Pilih Fasilitas')
								->relationship('facilities', 'name')
								->columns(2),
						])
						->afterStateUpdated(function ($state, callable $get) {
							$buildingId = $get('id');
							if ($buildingId) {
								$building = \App\Models\Building::find($buildingId);
								$building->facilities()->sync($state['facilities']);
							}
						})
						->visible(fn(callable $get) => !empty($get('id'))),

					// Step 3: Detail Lantai
					Forms\Components\Wizard\Step::make('Detail Lantai')
						->description('Tambahkan lantai dan kapasitas')
						->schema([
							Forms\Components\Repeater::make('floors')
								->label('Daftar Lantai')
								->relationship('floors')
								->schema([
									Forms\Components\TextInput::make('floor')
										->label('Lantai')
										->required()
										->afterStateHydrated(function (callable $set, callable $get) {
											$existingFloors = count($get('floors') ?? []);
											$set('floor', $existingFloors + 1);
										}),
									Forms\Components\TextInput::make('max_capacity')
										->label('Kapasitas Maksimum')
										->numeric()
										->required()
										->default(0),
									Forms\Components\TextInput::make('price')
										->label('Harga per Tahun')
										->numeric()
										->required()
										->default(0),
								])->columns(3)
								->collapsible(true)
								->defaultItems(1)
								->itemLabel(fn(array $state): ?string => 'Lantai ' . $state['floor'] ?? null)
								->addable(true)
						])

						->visible(fn(callable $get) => !empty($get('id'))),
				])->columnSpanFull(),
			]);
	}

	public static function table(Table $table): Table
	{
		return $table
			->columns([
				\Filament\Tables\Columns\TextColumn::make('name')->label('Nama Gedung')->searchable(),
				\Filament\Tables\Columns\TextColumn::make('type')->label('Tipe')->searchable(),
				\Filament\Tables\Columns\TextColumn::make('floors_count')->label('Jumlah Lantai')->counts('floors')->sortable(),
				\Filament\Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->counts('floors')->sortable(),
				\Filament\Tables\Columns\TextColumn::make('rooms_count')->label('Jumlah kamar')->counts('rooms')->sortable(),

			])
			->actions([
				\Filament\Tables\Actions\EditAction::make(),
				\Filament\Tables\Actions\DeleteAction::make()
			]);
	}

	protected function getRedirectUrl(): string
	{
		return BuildingResource::getUrl('index');
	}

	public static function getPages(): array
	{
		return [
			'index' => Pages\ListBuildings::route('/'),
			'create' => Pages\CreateBuilding::route('/create'),
			'edit' => Pages\EditBuilding::route('/{record}/edit'),
		];
	}
}
