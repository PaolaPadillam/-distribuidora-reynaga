<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - Distribuidora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #0052d4, #4364f7, #6fb1fc);
            background-size: cover;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            width: 350px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
        }

        .login-box img {
            width: 80px;
            margin-bottom: 15px;
        }

        .login-box h3 {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.8);
            border: none;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 1rem;
        }

        .btn-login {
            background: #007bff;
            border: none;
            color: white;
            font-weight: 600;
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #0056b3;
        }

        .extra-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            color: #e0e0e0;
        }

        .extra-options a {
            color: #e0e0e0;
            text-decoration: none;
        }

        .extra-options a:hover {
            text-decoration: underline;
        }

        .error {
            color: #ffb3b3;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="login-box">
    <img src="{{ asset('imagenes/logo.png') }}" alt="Logo Distribuidora">
    <h3>Distribuidora de Abarrotes</h3>

    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    <form action="/login" method="POST">
        @csrf
        <div class="mb-3">
            <input type="text" name="usuario" class="form-control" placeholder="Usuario" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
        </div>



        <button type="submit" class="btn-login">Iniciar Sesión</button>
    </form>
</div>

</body>
</html>
