<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pembayaran Anda Ditolak</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 2rem;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .header {
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
        }

        .header h2 {
            color: #F44336;
            margin: 0;
        }

        .content p {
            line-height: 1.6;
        }

        .highlight {
            font-weight: bold;
        }

        .cta-button {
            display: inline-block;
            margin-top: 1.5rem;
            padding: 0.75rem 1.5rem;
            background-color: #F44336;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .footer {
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #888;
            border-top: 1px solid #e0e0e0;
            padding-top: 1rem;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h2>Pembayaran Anda Ditolak ❌</h2>
        </div>
        <div class="content">
            <p>Halo <span class="highlight">{{ $transaction->booking->user->name }}</span>,</p>

            <p>Mohon maaf, bukti pembayaran yang Anda kirimkan belum dapat kami terima karena tidak valid atau tidak
                sesuai.</p>

            <p>
                <strong>Detail Transaksi:</strong><br>
                • <strong>Kode Kamar:</strong> {{ $transaction->booking->room->code }}<br>
                • <strong>Total Pembayaran:</strong> Rp {{ number_format($transaction->amount, 0, ',', '.') }}<br>
                • <strong>Status:</strong> <span class="highlight" style="color:red;">Ditolak</span>
            </p>

            @if ($notes)
                <p><strong>Catatan dari Admin:</strong></p>
                <blockquote style="background:#f9f9f9; padding:1rem; border-left:4px solid #F44336;">
                    {{ $notes }}
                </blockquote>
            @endif


            <p>Silakan unggah ulang bukti pembayaran Anda melalui dashboard kami.</p>

            <p>📱 Jika Anda mengalami kesulitan atau butuh bantuan, jangan ragu untuk menghubungi admin kami melalui
                WhatsApp:</p>

            <p>
                <strong>0852-8987-8823</strong> <br>
                <a href="https://wa.me/6285289878823" target="_blank">Klik di sini untuk buka WhatsApp</a>
            </p>

            <p>Kami akan membantu Anda untuk memastikan proses booking berjalan lancar.</p>

            <p>Salam hormat,</p>
            <p><strong>Tim Administrasi Asrama</strong></p>
        </div>

        <div class="footer">
            Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
        </div>
    </div>
</body>

</html>
