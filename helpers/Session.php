<?php
/**
 * Helper de Sesión — Gestión de autenticación y sesión de usuario
 */
class Session
{
    /**
     * Inicia sesión del usuario
     */
    public static function login(array $usuario): void
    {
        $_SESSION['usuario'] = [
            'id_usuario'    => $usuario['id_usuario'],
            'cedula'        => $usuario['cedula'],
            'nombre'        => $usuario['nombre'] ?? '',
            'apellido'      => $usuario['apellido'] ?? '',
            'correo'        => $usuario['correo_institucional'],
            'rol'           => $usuario['nombre_tipo'],
            'id_tipo'       => $usuario['id_tipo'],
        ];
        $_SESSION['login_time'] = time();
    }

    /**
     * Cierra sesión
     */
    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    /**
     * Verifica si el usuario está autenticado
     */
    public static function estaAutenticado(): bool
    {
        return isset($_SESSION['usuario']['id_usuario']);
    }

    /**
     * Obtiene datos del usuario en sesión
     */
    public static function obtenerUsuario(): ?array
    {
        return $_SESSION['usuario'] ?? null;
    }

    /**
     * Obtiene el ID del usuario en sesión
     */
    public static function obtenerIdUsuario(): ?int
    {
        return $_SESSION['usuario']['id_usuario'] ?? null;
    }

    /**
     * Obtiene el rol del usuario en sesión
     */
    public static function obtenerRol(): ?string
    {
        return $_SESSION['usuario']['rol'] ?? null;
    }

    /**
     * Verifica si el usuario tiene uno de los roles indicados
     */
    public static function tieneRol(array $roles): bool
    {
        $rolActual = self::obtenerRol();
        return $rolActual && in_array($rolActual, $roles, true);
    }

    /**
     * Obtiene el nombre completo del usuario
     */
    public static function nombreCompleto(): string
    {
        $usuario = self::obtenerUsuario();
        if (!$usuario) return 'Invitado';
        return trim(($usuario['nombre'] ?? '') . ' ' . ($usuario['apellido'] ?? ''));
    }
}
