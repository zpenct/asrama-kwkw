<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
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
                Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn ($record) => $record->status === 'waiting_verification')
                    ->modalHeading('Verifikasi Bukti Pembayaran')
                    ->form([

                        ViewField::make('payment_proof_preview')
                            ->view('components.payment-proof-preview')
                            ->label('Bukti Pembayaran'),

                        Select::make('action')
                            ->label('Keputusan')
                            ->options([
                                'accept' => 'Terima Bukti',
                                'reject' => 'Tolak Bukti',
                            ])
                            ->required(),
                    ])
                    ->action(function (array $data, $record) {
                        if ($data['action'] === 'accept') {
                            $record->status = 'paid';
                            $record->paid_at = now();
                            $record->booking->update(['status' => 'booked']);
                        } else {
                            $record->status = 'rejected';
                            $record->booking->update(['status' => 'pending']);
                        }
                        $record->save();
                    }),
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
