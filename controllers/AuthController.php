<?php
/**
 * Controlador de Autenticación
 * Login, logout y gestión de sesión
 */

require_once ROOT_PATH . '/helpers/Session.php';

class AuthController
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    /**
     * Redirige la raíz al dashboard o al login
     */
    public function inicio(): void
    {
        if (Session::estaAutenticado()) {
            redirect('dashboard');
        } else {
            redirect('login');
        }
    }

    /**
     * Muestra el formulario de login
     */
    public function mostrarLogin(): void
    {
        if (Session::estaAutenticado()) {
            redirect('dashboard');
            return;
        }

        renderViewNoLayout('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'flash'  => getFlash(),
        ]);
    }

    /**
     * Procesa el login
     */
    public function login(): void
    {
        $cedula = trim($_POST['cedula'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($cedula) || empty($password)) {
            setFlash('error', 'La cédula y la contraseña son obligatorias.');
            redirect('login');
            return;
        }

        // Buscar usuario con su tipo
        $sql = "SELECT u.*, tu.nombre_tipo,
                       dp.nombres, dp.apellidos
                FROM unexca.usuarios u
                JOIN unexca.tipos_usuario tu ON tu.id_tipo = u.id_tipo
                LEFT JOIN unexca.datos_personas dp ON dp.id_persona = u.id_persona
                WHERE u.cedula = :cedula
                LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':cedula' => $cedula]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            setFlash('error', 'Cédula o contraseña incorrectos.');
            redirect('login');
            return;
        }

        // Verificar contraseña (soporta tanto bcrypt como texto plano para migración)
        $passwordValido = false;
        if (password_get_info($usuario['password_hash'])['algo'] !== null && password_get_info($usuario['password_hash'])['algo'] !== 0) {
            $passwordValido = password_verify($password, $usuario['password_hash']);
        } else {
            // Compatibilidad con contraseñas en texto plano (migración)
            $passwordValido = ($password === $usuario['password_hash']);

            // Si coincide, actualizar a bcrypt
            if ($passwordValido) {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $updateStmt = $this->conexion->prepare(
                    "UPDATE unexca.usuarios SET password_hash = :hash WHERE id_usuario = :id"
                );
                $updateStmt->execute([':hash' => $hash, ':id' => $usuario['id_usuario']]);
            }
        }

        if (!$passwordValido) {
            setFlash('error', 'Cédula o contraseña incorrectos.');
            redirect('login');
            return;
        }

        // Actualizar último login
        $updateLogin = $this->conexion->prepare(
            "UPDATE unexca.usuarios SET ultimo_login = CURRENT_TIMESTAMP WHERE id_usuario = :id"
        );
        $updateLogin->execute([':id' => $usuario['id_usuario']]);

        // Crear sesión
        Session::login([
            'id_usuario'          => $usuario['id_usuario'],
            'cedula'              => $usuario['cedula'],
            'nombre'              => $usuario['nombres'] ?? $usuario['cedula'],
            'apellido'            => $usuario['apellidos'] ?? '',
            'correo_institucional'=> $usuario['correo_institucional'],
            'nombre_tipo'         => $usuario['nombre_tipo'],
            'id_tipo'             => $usuario['id_tipo'],
        ]);

        setFlash('success', '¡Bienvenido al SIC, ' . ($usuario['nombres'] ?? 'Usuario') . '!');
        redirect('dashboard');
    }

    /**
     * Cierra sesión
     */
    public function logout(): void
    {
        Session::logout();
        setFlash('success', 'Sesión cerrada exitosamente.');
        redirect('login');
    }
}
