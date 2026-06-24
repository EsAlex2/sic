<?php
/**
 * SIC — Sistema de Información y Control Académico
 * Archivo de configuración y conexión a la base de datos
 * UNEXCA — Universidad Nacional Experimental de la Gran Caracas
 */

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ─── Constantes del Sistema ─────────────────────────────────────────────
define('URL_BASE', '/sic');
define('ROOT_PATH', __DIR__);
define('VIEWS_PATH', ROOT_PATH . '/views');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// ─── Roles del Sistema ──────────────────────────────────────────────────
define('ROL_ADMIN', 'Administrador');
define('ROL_DOCENTE', 'Docente');
define('ROL_ESTUDIANTE', 'Estudiante');
define('ROL_CONTROL_ESTUDIOS', 'Control de Estudios');
define('ROL_FINANZAS', 'Finanzas');

define('ROLES_TODOS', [ROL_ADMIN, ROL_DOCENTE, ROL_ESTUDIANTE, ROL_CONTROL_ESTUDIOS, ROL_FINANZAS]);

// ─── Conexión a la Base de Datos ────────────────────────────────────────
class Conexion
{
    private string $host = "localhost";
    private string $db_name = "admin_sql";
    private string $db_user = "postgres";
    private string $db_password = "qwerty2801**";
    private string $db_port = '5432';
    private PDO $conexion;

    public function __construct()
    {
        try {
            $dsn = "pgsql:host={$this->host};port={$this->db_port};dbname={$this->db_name};";

            $this->conexion = new PDO($dsn, $this->db_user, $this->db_password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    public function getConexion(): PDO
    {
        return $this->conexion;
    }
}

// ─── Función Helper para Vistas ─────────────────────────────────────────
/**
 * Renderiza una vista con datos
 */
function renderView(string $view, array $data = [], string $layout = 'layouts/main'): void
{
    extract($data);
    $contentView = VIEWS_PATH . '/' . $view . '.php';

    if (!file_exists($contentView)) {
        http_response_code(404);
        echo "Vista no encontrada: {$view}";
        return;
    }

    ob_start();
    require $contentView;
    $content = ob_get_clean();

    if ($layout) {
        $layoutFile = VIEWS_PATH . '/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    } else {
        echo $content;
    }
}

/**
 * Renderiza una vista sin layout (para login, etc.)
 */
function renderViewNoLayout(string $view, array $data = []): void
{
    extract($data);
    $viewFile = VIEWS_PATH . '/' . $view . '.php';
    if (file_exists($viewFile)) {
        require $viewFile;
    }
}

/**
 * Genera una URL completa del sistema
 */
function url(string $path = ''): string
{
    return URL_BASE . '/' . ltrim($path, '/');
}

/**
 * Redirige a una URL del sistema
 */
function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

/**
 * Establece un mensaje flash en la sesión
 */
function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Obtiene y limpia el mensaje flash
 */
function getFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}
