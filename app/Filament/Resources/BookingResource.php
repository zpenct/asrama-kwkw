<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('room.code')->label('Kode Kamar')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('Pemesan')->searchable(),
                Tables\Columns\TextColumn::make('total_guest')->label('Total Penghuni / Calon')->searchable(),
                Tables\Columns\TextColumn::make('lama_inap_bulan')
                    ->label('Lama Inap')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_menginap')
                    ->label('Tanggal Menginap')
                    ->getStateUsing(function ($record) {
                        $checkin = \Carbon\Carbon::parse($record->checkin_date)->translatedFormat('d M Y');
                        $checkout = \Carbon\Carbon::parse($record->checkout_date)->translatedFormat('d M Y');

                        return $checkin.' → '.$checkout;
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y, H:i')->sortable(),
                Tables\Columns\TextColumn::make('expired_at')->dateTime('d M Y, H:i')->sortable(),
                Tables\Columns\TextColumn::make('transaction.status')->label('Status Pembayaran')->searchable(),
                Tables\Columns\TextColumn::make('status')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListBookings::route('/'),
            // 'create' => Pages\CreateBooking::route('/create'),
            // 'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
