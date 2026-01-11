<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .error-container {
            text-align: center;
            color: white;
            max-width: 600px;
            animation: fadeIn 0.6s ease-in;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .error-code {
            font-size: 150px;
            font-weight: 700;
            line-height: 1;
            text-shadow: 4px 4px 0 rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }
        
        .error-title {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .error-message {
            font-size: 18px;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .error-icon {
            font-size: 120px;
            margin-bottom: 20px;
            opacity: 0.8;
        }
        
        .btn-container {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        
        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .btn-secondary:hover {
            background: white;
            color: #667eea;
        }
        
        .search-box {
            margin-top: 30px;
            display: flex;
            gap: 10px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .search-box input {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            outline: none;
        }
        
        .search-box button {
            padding: 12px 25px;
            background: #764ba2;
            color: white;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-box button:hover {
            background: #5a3780;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">🔍</div>
        <div class="error-code">404</div>
        <h1 class="error-title">Oops! Halaman Tidak Ditemukan</h1>
        <p class="error-message">
            Maaf, halaman yang Anda cari tidak dapat ditemukan. Halaman mungkin telah dipindahkan atau tidak ada.
        </p>
        
       
</body>
</html>