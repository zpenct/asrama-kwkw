<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

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

                Tables\Columns\TextColumn::make('booking.room.code')->label('Kode Kamar')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('booking.user.name')->label('Pemesan')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y, H:i')->sortable(),
                Tables\Columns\TextColumn::make('paid_at')->dateTime('d M Y, H:i')->sortable(),
                Tables\Columns\TextColumn::make('amount')->label('Total Transaksi')->sortable()->formatStateUsing(function ($state) {
                    return 'Rp '.number_format($state, 0, ',', '.');
                }),
                Tables\Columns\TextColumn::make('status')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('lihatGambar')
                    ->label('Lihat Gambar')
                    ->icon('heroicon-o-photo')
                    ->infolist([
                        ImageEntry::make('payment_proof')
                            ->disk('s3')
                            ->columnSpanFull(),
                    ])->modalSubmitAction(false)
                    ->modalCancelAction(false),
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
            'index' => Pages\ListTransactions::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
