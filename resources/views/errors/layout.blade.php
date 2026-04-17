<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('code') - @yield('title') | Portal Pelatih Pencak Silat</title>
    <link rel="icon" type="image/png" href="{{ asset('app/img/MASKOT.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        :root {
            --primary: #F97A16;
            --accent: #FFD700;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: linear-gradient(135deg, #212529 0%, #2c3034 50%, #343a40 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            color: #fff;
        }
        .error-container { text-align: center; padding: 2rem; max-width: 560px; width: 100%; }
        .error-code {
            font-size: 8rem;
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 0 20px rgba(249,122,22,0.4));
        }
        .mascot-img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            filter: drop-shadow(0 4px 12px rgba(249,122,22,0.3));
            margin-bottom: 1rem;
        }
        .error-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        .error-desc {
            color: #adb5bd;
            font-size: 0.95rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .btn-home {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border: none;
            color: #212529;
            font-weight: 700;
            padding: 0.7rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 15px rgba(249,122,22,0.3);
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(249,122,22,0.5);
            color: #212529;
        }
        .btn-back {
            background: transparent;
            border: 1px solid #495057;
            color: #adb5bd;
            font-weight: 500;
            padding: 0.7rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
            margin-left: 0.75rem;
        }
        .btn-back:hover { border-color: #888; color: #fff; }
        .app-brand { color: #6c757d; font-size: 0.8rem; margin-top: 2rem; }
    </style>
</head>
<body>
    <div class="error-container">
        <img src="{{ asset('app/img/MASKOT.png') }}" alt="Maskot" class="mascot-img">
        <div class="error-code">@yield('code')</div>
        <h1 class="error-title">@yield('title')</h1>
        <p class="error-desc">@yield('description')</p>
        <div>
            <a href="{{ url('/pelatih/dashboard') }}" class="btn-home">
                <i class="fas fa-home"></i> Ke Dashboard
            </a>
            <a href="javascript:history.back()" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <p class="app-brand">Portal Pelatih &mdash; Pencak Silat UNPER OPEN</p>
    </div>
</body>
</html>
