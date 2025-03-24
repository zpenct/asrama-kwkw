<?php
// app/Filament/Resources/UserResource.php
namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use App\Filament\Resources\UserResource\Pages;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Manajemen User';
    protected static ?string $label = 'Pengguna';

    public static function form(Form $form): Form
    {
        $user = Auth::user();

        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama User')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Masukkan Email')
                    ->email()
                    ->unique(User::class, 'email', ignoreRecord: true)
                    ->required(),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->placeholder('Masukkan Password')
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => !empty($state) ? $state : null)
                    ->required(fn($livewire) => $livewire instanceof Pages\CreateUser)
                    ->nullable(),

                // ONLY SUPERADMIN AND ADMIN CAN SEE THIS FIELD
                Select::make('role')
                    ->label('Role')
                    ->options([
                        'SUPERADMIN' => 'Superadmin',
                        'ADMIN' => 'Admin',
                        'MAHASISWA' => 'Mahasiswa',
                    ])
                    ->required()
                    ->hidden(fn() => $user->role === 'MAHASISWA'), // ROLE 2 (MAHASISWA) CAN'T SEE THIS FIELD

                Checkbox::make('is_first')
                    ->label('Wajib Reset Password Saat Pertama Login'),
            ]);
    }

    public static function table(Table $table): Table
    {

        $user = Auth::user();  // GET AUTHENTICATED USER

        return $table
            ->query(
                User::query()
                    ->when($user->role === 'MAHASISWA', fn($query) => $query->where('id', $user->id)) // ROLE 2 (MAHASISWA) CAN ONLY SEE THEIR OWN ACCOUNT
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama User')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'SUPERADMIN' => 'Superadmin',
                        'ADMIN' => 'Admin',
                        'MAHASISWA' => 'Mahasiswa',
                    })
                    ->badge(),
                // Tables\Columns\IconColumn::make('is_first')
                //     ->label('Reset Password?')
                //     ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ]);
        // ->bulkActions(
        //     $user->role == 'MAHASISWA' ? [] : [
        //         Tables\Actions\BulkActionGroup::make([
        //             Tables\Actions\DeleteBulkAction::make(),
        //         ]),
        //     ]
        // ); // ROLE 2 (MAHASISWA) CAN'T DELETE USER
    }

    public static function canCreate(): bool
    {
        $user = Filament::auth()->user();

        // ONLY SUPERADMIN AND ADMIN CAN CREATE USER
        return $user && $user->role === 'SUPERADMIN';
    }

    public static function canDelete($record): bool
    {
        $user = Filament::auth()->user();

        // ONLY SUPERADMIN AND ADMIN CAN DELETE USER
        return $user && $user->role !== 'SUPERADMIN';
    }

    public static function canViewAny(): bool
    {
        $user = Filament::auth()->user();

        return $user && $user->role === 'SUPERADMIN';
    }

    protected function getRedirectUrl(): string
    {
        return UserResource::getUrl('index');
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Filament::auth()->user();

        return $user && $user->role === 'SUPERADMIN';
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}