<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Room Meeting - Sinergi Group</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #fcad02 0%, #eec890 100%);
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 2.8rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header p {
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
            opacity: 0.9;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            width: 100%;
        }

        .welcome-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .welcome-card:hover {
            transform: translateY(-10px);
        }

        .welcome-card h2 {
            color: #1e3c72;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        .welcome-card p {
            line-height: 1.6;
            margin-bottom: 25px;
            color: #555;
        }

        .features {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .features {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 25px;
    margin-top: 20px;
    flex-wrap: nowrap; /* supaya 1 baris saja */
}

.feature {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 15px 20px;
    width: 160px;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.feature:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.feature i {
    font-size: 26px;
    color: #1e3c72;
    margin-bottom: 8px;
    display: block;
}

.feature p {
    font-size: 0.95rem;
    margin: 0;
    font-weight: 500;
}


        .auth-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(45deg, #ff9900, #ff6600);
            color: white;
            border: none;
            box-shadow: 0 4px 8px rgba(255, 102, 0, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #ff7a00, #e65c00);
            transform: scale(1.05);
        }

        .btn-secondary {
            background: linear-gradient(45deg, #ffc266, #ff9900);
            color: white;
            border: none;
            box-shadow: 0 4px 8px rgba(255, 153, 0, 0.3);
        }

        .btn-secondary:hover {
            background: linear-gradient(45deg, #ffb347, #ff7a00);
            transform: scale(1.05);
        }


        .footer {
            margin-top: 40px;
            color: white;
            text-align: center;
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2.2rem;
            }

            .welcome-card {
                padding: 30px 20px;
            }

            .auth-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Booking Room Meeting</h1>
            <p>Sistem pemesanan ruang meeting untuk Sinergi Group</p>
        </div>

        <div class="card-container">
            <div class="welcome-card">
                <h2>Selamat Datang</h2>
                <p>Sistem booking ruang meeting Sinergi Group memudahkan Anda dalam merencanakan dan mengatur pertemuan dengan efisien. Dapatkan pengalaman terbaik dalam mengelola jadwal meeting Anda.</p>

                <div class="features">
    <div class="feature">
        <i>📅</i>
        <p>Jadwal Fleksibel</p>
    </div>
    <div class="feature">
        <i>🚀</i>
        <p>Proses Cepat</p>
    </div>
    <div class="feature">
        <i>✅</i>
        <p>Konfirmasi Real-time</p>
    </div>
</div>


                <div class="auth-buttons">
                    <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-secondary">Daftar</a>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>&copy; 2023 Sinergi Group. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
