<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #0F172A; --accent: #0D9488; --accent-dark: #0F766E;
            --bg: #F1F5F9; --text: #1E293B; --text-muted: #64748B; --border: #E2E8F0;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .login-card {
            background: #fff;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 24px 64px rgba(0,0,0,0.25);
        }
        .login-card .brand {
            display: flex; align-items: center; gap: 10px;
            font-weight: 700; font-size: 1.1rem; color: var(--text);
            margin-bottom: 1.5rem; justify-content: center;
        }
        .login-card .brand .logo-icon {
            width: 34px; height: 34px; background: var(--accent);
            border-radius: 9px; display: flex; align-items: center;
            justify-content: center; font-size: 0.75rem; font-weight: 800; color: #fff;
        }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-size: 0.78rem; font-weight: 600; margin-bottom: 0.3rem; color: var(--text); }
        .form-group input {
            width: 100%; padding: 0.6rem 0.75rem;
            border: 1.5px solid var(--border); border-radius: 8px;
            font-size: 0.85rem; font-family: inherit;
            transition: border-color 0.15s;
        }
        .form-group input:focus { outline: none; border-color: var(--accent); }
        .form-check { display: flex; align-items: center; gap: 0.5rem; font-size: 0.82rem; }
        .btn {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            width: 100%; padding: 0.6rem; border-radius: 8px; border: none;
            font-size: 0.82rem; font-weight: 600; font-family: inherit;
            cursor: pointer; background: var(--accent); color: #fff;
            transition: background 0.15s;
        }
        .btn:hover { background: var(--accent-dark); }
        .alert { padding: 0.7rem 1rem; border-radius: 8px; font-size: 0.8rem; margin-bottom: 1rem; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .back-link { text-align: center; margin-top: 1.2rem; font-size: 0.75rem; }
        .back-link a { color: var(--text-muted); text-decoration: none; }
        .back-link a:hover { color: var(--accent); }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">
            Login Admin Desa Nekmese
        </div>
        @if($errors->any())
        <div class="alert alert-error">{{ $errors->first('email') }}</div>
        @endif
        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label class="form-check">
                    <input type="checkbox" name="remember"> Ingat saya
                </label>
            </div>
            <button type="submit" class="btn">Masuk</button>
        </form>
        <div class="back-link"><a href="/"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a></div>
    </div>
</body>
</html>
