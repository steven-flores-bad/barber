<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberShop - Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card login-card">
                <div class="barber-pole-border"></div>
                
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fa-solid fa-scissors fa-3x text-gold mb-3"></i>
                        <h2 class="fw-bold">BARBER<span class="text-gold">SHOP</span></h2>
                        <p class="text-muted">Gestión de Ventas y Servicios</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger bg-danger text-white border-0 py-2">
                            <ul class="mb-0 fs-7">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label text-muted">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-secondary border-0 text-white"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" placeholder="nombre@correo.com" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label text-muted">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-secondary border-0 text-white"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" id="password" placeholder="********" required>
                            </div>
                        </div>

                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                <label class="form-check-label text-muted fs-7" for="remember">Recordarme</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-gold w-100 py-2 mb-3">
                            <i class="fa-solid fa-right-to-bracket me-2"></i> Ingresar
                        </button>
                    </form>
                  {{-- 
                    <div class="text-center mt-4">
                        <p class="mb-0 text-muted fs-7">¿No tienes usuario corporativo? <a href="{{ route('register') }}" class="text-gold text-decoration-none fw-bold">Regístrate aquí</a></p>
                    </div> 
                        --}}

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>