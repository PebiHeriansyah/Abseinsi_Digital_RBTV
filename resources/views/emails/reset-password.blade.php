<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Sandi - Absensi RBTV</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f5fa;
            margin: 0;
            padding: 0;
            color: #435971;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f5fa;
            padding: 40px 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #696cff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
            line-height: 1.6;
        }
        .content h2 {
            color: #323259;
            font-size: 20px;
            margin-top: 0;
        }
        .btn-wrapper {
            text-align: center;
            margin: 35px 0;
        }
        .btn {
            display: inline-block;
            background-color: #696cff;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
        }
        .note {
            background-color: #fff5f5;
            color: #ff3e1d;
            padding: 15px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
            border-left: 4px solid #ff3e1d;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #a1acb8;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <table class="email-container" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td class="header">
                    <h1>Absensi RBTV</h1>
                </td>
            </tr>
            <tr>
                <td class="content">
                    <h2>Halo!</h2>
                    <p>Kami menerima permintaan untuk mereset kata sandi akun Anda. Jika Anda merasa tidak pernah memintanya, Anda bisa mengabaikan email ini dengan aman.</p>
                    
                    <p>Untuk membuat kata sandi baru, silakan klik tombol di bawah ini:</p>
                    
                    <div class="btn-wrapper">
                        <a href="{{ $url }}" class="btn">Reset Kata Sandi</a>
                    </div>
                    
                    <div class="note">
                        <strong>Catatan:</strong> Link reset kata sandi ini hanya berlaku selama 60 menit demi keamanan akun Anda.
                    </div>
                    
                    <p>Jika tombol di atas tidak berfungsi, Anda juga dapat menyalin dan menempelkan tautan berikut ke browser Anda:</p>
                    <p style="word-break: break-all; color: #696cff; font-size: 13px;">{{ $url }}</p>
                    
                    <br>
                    <p>Salam hangat,<br><strong>Tim Absensi RBTV</strong></p>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    <p>&copy; {{ date('Y') }} Absensi RBTV. Hak cipta dilindungi.</p>
                    <p>Pesan ini dikirim secara otomatis, mohon untuk tidak membalas email ini.</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
