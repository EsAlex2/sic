<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Iniciar sesión en el Sistema de Información y Control Académico — UNEXCA">
    <title>Iniciar Sesión — SIC UNEXCA</title>
    <link rel="stylesheet" href="<?= url('public/css/main.css') ?>">
    <link rel="stylesheet" href="<?= url('public/css/auth.css') ?>">
</head>
<body>
    <div class="login-page">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <div class="login-logo">SIC</div>
                    <h1 class="login-title">Bienvenido</h1>
                    <p class="login-subtitle">Sistema de Información y Control Académico</p>
                </div>

                <?php if (!empty($flash)): ?>
                    <div class="alert alert-<?= $flash['type'] ?>" style="margin-bottom: 1.5rem;">
                        <span><?= htmlspecialchars($flash['message']) ?></span>
                        <button class="alert-close" onclick="this.parentElement.remove()">×</button>
                    </div>
                <?php endif; ?>

                <form class="login-form" method="POST" action="<?= url('login') ?>">
                    <div class="form-group">
                        <label class="form-label" for="cedula">Cédula de Identidad</label>
                        <div class="input-icon-wrap">
                            <span class="input-icon">🪪</span>
                            <input type="text"
                                   id="cedula"
                                   name="cedula"
                                   class="form-control"
                                   placeholder="V-12345678"
                                   required
                                   autocomplete="username"
                                   autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Contraseña</label>
                        <div class="input-icon-wrap">
                            <span class="input-icon">🔒</span>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="••••••••"
                                   required
                                   autocomplete="current-password">
                            <button type="button" class="password-toggle" onclick="togglePassword()">👁</button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg">
                        Iniciar Sesión
                    </button>
                </form>

                <div class="login-footer">
                    <p>Universidad Nacional Experimental<br>de la Gran Caracas</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const toggle = document.querySelector('.password-toggle');
            if (input.type === 'password') {
                input.type = 'text';
                toggle.textContent = '🙈';
            } else {
                input.type = 'password';
                toggle.textContent = '👁';
            }
        }
    </script>
</body>
</html>
