<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pembayaran Anda</title>
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
            color: #4CAF50;
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
            background-color: #4CAF50;
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
            <h2>Pembayaran Anda Telah Diterima ✅</h2>
        </div>
        <div class="content">
            <p>Halo <span class="highlight">{{ $transaction->booking->user->name }}</span>,</p>

            <p>Terima kasih, pembayaran Anda telah berhasil diverifikasi.</p>

            <p>
                <strong>Detail Transaksi:</strong><br>
                • <strong>Kode Kamar:</strong> {{ $transaction->booking->room->code }}<br>
                • <strong>Total Pembayaran:</strong> Rp {{ number_format($transaction->amount, 0, ',', '.') }}<br>
                • <strong>Status:</strong> <span class="highlight" style="color:green;">Diterima</span>
            </p>

            <p>Untuk melanjutkan proses booking, silakan klik tombol di bawah ini untuk mengisi formulir:</p>

            <p>
                <a class="cta-button"
                    href="https://docs.google.com/document/d/1cXS7VdAe2_G6nYYlLe_GIIfi0_VOY61_/edit?usp=sharing&ouid=105487824535049138389&rtpof=true&sd=true"
                    target="_blank">
                    Isi Formulir Booking
                </a>
            </p>

            <p>📤 Setelah mengisi formulir, harap <strong>kirimkan formulir yg telah diisi </strong> tersebut ke
                WhatsApp kami di:</p>

            <p>
                <strong>📱 0852-8987-8823</strong> <br>
                <a href="https://wa.me/6285289878823" target="_blank">Klik di sini untuk buka WhatsApp</a>
            </p>

            @if ($notes)
                <p><strong>Catatan dari Admin:</strong></p>
                <blockquote style="background:#f9f9f9; padding:1rem; border-left:4px solid #4CAF50;">
                    {{ $notes }}
                </blockquote>
            @endif

            <p>Terima kasih atas kerjasamanya.</p>

            <p>Salam hangat,</p>
            <p><strong>Tim Administrasi Asrama</strong></p>
        </div>

        <div class="footer">
            Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
        </div>
    </div>
</body>

</html>
