<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IFBANK | Acesso Gerencial</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: #0f172a;
        }
        .login-shell {
            width: 100%;
            max-width: 430px;
        }
        .brand {
            text-align: center;
            color: #fff;
            margin-bottom: 22px;
        }
        .brand-mark {
            width: 58px;
            height: 58px;
            margin: 0 auto 12px;
            border-radius: 16px;
            background: #fff;
            color: #1e293b;
            display: grid;
            place-items: center;
            font-size: 24px;
            font-weight: 800;
            box-shadow: 0 12px 30px rgba(0,0,0,.22);
        }
        .brand h1 { margin: 0; font-size: 28px; letter-spacing: .5px; }
        .brand p { margin: 6px 0 0; color: #cbd5e1; font-size: 14px; }
        .card {
            background: #fff;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 24px 60px rgba(0,0,0,.28);
        }
        .card h2 { margin: 0 0 6px; font-size: 22px; }
        .subtitle { margin: 0 0 24px; color: #64748b; font-size: 14px; }
        label { display: block; margin: 0 0 7px; font-size: 14px; font-weight: 600; }
        input {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 12px 13px;
            font-size: 15px;
            outline: none;
        }
        input:focus { border-color: #334155; box-shadow: 0 0 0 3px rgba(51,65,85,.12); }
        .field { margin-bottom: 17px; }
        .error {
            margin: 0 0 18px;
            padding: 11px 12px;
            border-radius: 10px;
            background: #fef2f2;
            color: #b91c1c;
            font-size: 13px;
        }
        .remember { display: flex; align-items: center; gap: 8px; margin: 2px 0 20px; color: #475569; font-size: 13px; }
        .remember input { width: auto; }
        button {
            width: 100%;
            border: 0;
            border-radius: 10px;
            padding: 13px;
            background: #1e293b;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }
        button:hover { background: #0f172a; }
        .footer { margin-top: 18px; text-align: center; color: #94a3b8; font-size: 12px; }
    </style>
</head>
<body>
    <main class="login-shell">
        <div class="brand">
            <div class="brand-mark">IF</div>
            <h1>IFBANK</h1>
            <p>Painel de gerenciamento bancário</p>
        </div>

        <section class="card">
            <h2>Acesso gerencial</h2>
            <p class="subtitle">Entre com sua conta de Gerente Geral ou Gerente de Conta.</p>

            @if ($errors->any())
                <div class="error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label for="email">E-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                </div>

                <div class="field">
                    <label for="password">Senha</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password">
                </div>

                <label class="remember">
                    <input type="checkbox" name="remember">
                    <span>Manter sessão conectada</span>
                </label>

                <button type="submit">Entrar no painel</button>
            </form>
        </section>

        <div class="footer">IFBANK • Área interna</div>
    </main>
</body>
</html>
