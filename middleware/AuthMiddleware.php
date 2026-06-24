<?php
/**
 * Middleware de Autenticación y Autorización
 */
class AuthMiddleware
{
    /**
     * Verifica que el usuario esté autenticado.
     * Si no lo está, redirige al login.
     */
    public static function verificar(): void
    {
        if (!Session::estaAutenticado()) {
            setFlash('error', 'Debes iniciar sesión para acceder a esta página.');
            redirect('login');
        }
    }

    /**
     * Verifica que el usuario tenga uno de los roles permitidos.
     * Si no tiene permiso, redirige al dashboard con un mensaje.
     */
    public static function verificarRol(array $rolesPermitidos): void
    {
        self::verificar();

        if (!Session::tieneRol($rolesPermitidos)) {
            setFlash('error', 'No tienes permisos para acceder a esta sección.');
            redirect('dashboard');
        }
    }

    // ─── Métodos de conveniencia para usar como middleware en rutas ──────

    public static function auth(): void
    {
        self::verificar();
    }

    public static function soloAdmin(): void
    {
        self::verificarRol([ROL_ADMIN]);
    }

    public static function soloDocente(): void
    {
        self::verificarRol([ROL_ADMIN, ROL_DOCENTE]);
    }

    public static function soloEstudiante(): void
    {
        self::verificarRol([ROL_ADMIN, ROL_ESTUDIANTE]);
    }

    public static function soloControlEstudios(): void
    {
        self::verificarRol([ROL_ADMIN, ROL_CONTROL_ESTUDIOS]);
    }

    public static function soloFinanzas(): void
    {
        self::verificarRol([ROL_ADMIN, ROL_FINANZAS]);
    }

    public static function docenteOAdmin(): void
    {
        self::verificarRol([ROL_ADMIN, ROL_DOCENTE]);
    }

    public static function academico(): void
    {
        self::verificarRol([ROL_ADMIN, ROL_CONTROL_ESTUDIOS, ROL_DOCENTE]);
    }
}
