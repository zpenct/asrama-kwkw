<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Mail\TransactionAcceptedMail;
use App\Mail\TransactionRejectedMail;
use App\Models\Transaction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'waiting_verification')->count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'The number waiting for verification';
    }

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
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('booking.room.code')->label('Kode Kamar')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('booking.user.name')->label('Pemesan')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y, H:i')->sortable(),
                Tables\Columns\TextColumn::make('uploaded_at')->label('Uploaded At')->dateTime('d M Y, H:i')->sortable(),
                Tables\Columns\TextColumn::make('verified_at')->label('Verified At')->dateTime('d M Y, H:i')->sortable(),
                Tables\Columns\TextColumn::make('amount')->label('Total Transaksi')->sortable()->formatStateUsing(function ($state) {
                    return 'Rp '.number_format($state, 0, ',', '.');
                }),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'waiting_verification' => 'Menunggu Verifikasi',
                        'waiting_payment' => 'Menunggu Pembayaran',
                        'paid' => 'Dibayar',
                        'rejected' => 'Ditolak',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'waiting_verification' => 'warning', // kuning/oranye
                        'paid' => 'success', // hijau
                        'waiting_payment' => 'gray',
                        'rejected' => 'danger', // merah
                        default => 'gray',
                    })->searchable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->multiple()
                    ->options([
                        'waiting_verification' => 'Menunggu Verifikasi',
                        'waiting_payment' => 'Menunggu Pembayaran',
                        'paid' => 'Dibayar',
                        'rejected' => 'Ditolak',
                    ])->default(['waiting_verification']),
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

                        Textarea::make('notes')
                            ->label('Catatan untuk User')
                            ->placeholder('Tulis alasan penolakan atau catatan lainnya...'),
                    ])
                    ->action(function (array $data, $record) {
                        if ($data['action'] === 'accept') {
                            $record->status = 'paid';
                            $record->verified_at = now();
                            $record->booking->update(['status' => 'booked']);

                            Mail::to($record->booking->user->email)->send(new TransactionAcceptedMail($record, $data['notes'] ?? null));
                        } else {
                            $record->status = 'rejected';
                            $record->booking->update(['status' => 'pending']);
                            $record->verified_at = now();
                            Mail::to($record->booking->user->email)->send(new TransactionRejectedMail($record, $data['notes'] ?? null));
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
