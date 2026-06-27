<?php
/**
 * SIC — API Controller para Usuarios
 * Retorna JSON puro para pruebas con Postman / Insomnia
 */

class ApiUsuarioController
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
        
        // Asegurar que la respuesta siempre sea JSON
        header('Content-Type: application/json; charset=utf-8');
    }

    /**
     * Responde con formato JSON estandarizado
     */
    private function jsonResponse(bool $success, mixed $data, string $message = '', int $statusCode = 200): void
    {
        http_response_code($statusCode);
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data'    => $data
        ]);
        exit;
    }

    /**
     * GET /api/v1/usuarios
     */
    public function listar(): void
    {
        try {
            $stmt = $this->conexion->query("
                SELECT u.id_usuario, u.cedula, u.correo_institucional, 
                       tu.nombre_tipo as rol, e.nombre_estatus as estatus,
                       dp.nombres, dp.apellidos
                FROM unexca.usuarios u
                JOIN unexca.tipos_usuario tu ON tu.id_tipo = u.id_tipo
                JOIN unexca.estatus e ON e.id_estatus = u.id_estatus
                LEFT JOIN unexca.datos_personas dp ON dp.id_persona = u.id_persona
                ORDER BY u.id_usuario DESC
            ");
            $usuarios = $stmt->fetchAll();
            $this->jsonResponse(true, $usuarios, "Listado de usuarios recuperado exitosamente.");
        } catch (PDOException $e) {
            $this->jsonResponse(false, null, "Error al obtener usuarios: " . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/usuarios/{id}
     */
    public function obtener(int $id): void
    {
        try {
            $stmt = $this->conexion->prepare("
                SELECT u.id_usuario, u.cedula, u.correo_institucional, 
                       tu.nombre_tipo as rol, e.nombre_estatus as estatus,
                       dp.nombres, dp.apellidos
                FROM unexca.usuarios u
                JOIN unexca.tipos_usuario tu ON tu.id_tipo = u.id_tipo
                JOIN unexca.estatus e ON e.id_estatus = u.id_estatus
                LEFT JOIN unexca.datos_personas dp ON dp.id_persona = u.id_persona
                WHERE u.id_usuario = :id
            ");
            $stmt->execute([':id' => $id]);
            $usuario = $stmt->fetch();

            if (!$usuario) {
                $this->jsonResponse(false, null, "Usuario no encontrado.", 404);
            }

            $this->jsonResponse(true, $usuario, "Usuario recuperado exitosamente.");
        } catch (PDOException $e) {
            $this->jsonResponse(false, null, "Error al obtener usuario: " . $e->getMessage(), 500);
        }
    }

    /**
     * POST o PUT /api/v1/usuarios/{id}
     * Usamos POST o PUT simulado para edición
     */
    public function actualizar(int $id): void
    {
        // Leer datos JSON del body
        $json = file_get_contents('php://input');
        $data = json_decode($json, true) ?? [];

        // Si no mandan JSON, intentar por POST tradicional
        $id_tipo = $data['id_tipo'] ?? $_POST['id_tipo'] ?? null;
        $correo = $data['correo_institucional'] ?? $_POST['correo_institucional'] ?? null;
        $id_estatus = $data['id_estatus'] ?? $_POST['id_estatus'] ?? null;

        if (empty($id_tipo) && empty($correo) && empty($id_estatus)) {
            $this->jsonResponse(false, null, "No se enviaron datos para actualizar.", 400);
        }

        try {
            // Verificar si el usuario existe
            $check = $this->conexion->prepare("SELECT id_usuario FROM unexca.usuarios WHERE id_usuario = :id");
            $check->execute([':id' => $id]);
            if (!$check->fetch()) {
                $this->jsonResponse(false, null, "Usuario no encontrado.", 404);
            }

            // Construir query dinámicamente
            $fields = [];
            $params = [':id' => $id];

            if ($id_tipo) {
                $fields[] = "id_tipo = :id_tipo";
                $params[':id_tipo'] = $id_tipo;
            }
            if ($correo) {
                $fields[] = "correo_institucional = :correo";
                $params[':correo'] = $correo;
            }
            if ($id_estatus) {
                $fields[] = "id_estatus = :id_estatus";
                $params[':id_estatus'] = $id_estatus;
            }

            $setClause = implode(', ', $fields);
            $stmt = $this->conexion->prepare("UPDATE unexca.usuarios SET {$setClause} WHERE id_usuario = :id");
            $stmt->execute($params);

            $this->jsonResponse(true, ['id_usuario' => $id], "Usuario actualizado exitosamente.");
        } catch (PDOException $e) {
            $this->jsonResponse(false, null, "Error al actualizar: " . $e->getMessage(), 500);
        }
    }

    /**
     * DELETE /api/v1/usuarios/{id}
     * Soft-delete (desactivación)
     */
    public function desactivar(int $id): void
    {
        try {
            // Obtener el ID del estatus "Inactivo"
            $stmtEstatus = $this->conexion->query("SELECT id_estatus FROM unexca.estatus WHERE nombre_estatus = 'Inactivo' LIMIT 1");
            $estatusInactivo = $stmtEstatus->fetch();

            if (!$estatusInactivo) {
                $this->jsonResponse(false, null, "No se encontró el estatus 'Inactivo' en el sistema.", 500);
            }

            // Verificar si el usuario existe
            $check = $this->conexion->prepare("SELECT id_usuario, id_estatus FROM unexca.usuarios WHERE id_usuario = :id");
            $check->execute([':id' => $id]);
            $user = $check->fetch();

            if (!$user) {
                $this->jsonResponse(false, null, "Usuario no encontrado.", 404);
            }

            if ($user['id_estatus'] == $estatusInactivo['id_estatus']) {
                $this->jsonResponse(false, null, "El usuario ya se encuentra inactivo.", 400);
            }

            // Soft-delete
            $stmt = $this->conexion->prepare("UPDATE unexca.usuarios SET id_estatus = :id_estatus WHERE id_usuario = :id");
            $stmt->execute([
                ':id_estatus' => $estatusInactivo['id_estatus'],
                ':id' => $id
            ]);

            $this->jsonResponse(true, ['id_usuario' => $id, 'estatus_nuevo' => 'Inactivo'], "Usuario desactivado exitosamente (Soft Delete).");
        } catch (PDOException $e) {
            $this->jsonResponse(false, null, "Error al desactivar usuario: " . $e->getMessage(), 500);
        }
    }
}
