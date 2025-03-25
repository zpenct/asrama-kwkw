<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function upload(Transaction $transaction)
    {
        return view('transactions.upload', compact('transaction'));
    }

    public function submitUpload(Request $request, Transaction $transaction)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:2048', // 2MB max
        ]);

        // Simpan ke S3
        $path = $request->file('payment_proof')->store('payment_proofs', 's3');

        // Set file public (optional tergantung bucket policy)
        Storage::disk('s3')->setVisibility($path, 'public');

        // Simpan ke DB
        $transaction->update([
            'payment_proof' => $path,
            'uploaded_at' => now(),
            'status' => 'waiting_verification',
        ]);

        return redirect()->route('booking.show', $transaction->booking_id)
            ->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.');
    }
}
