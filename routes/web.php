<?php
/**
 * SIC — Definición de Rutas
 * Todas las rutas del sistema se definen aquí
 */

$router = Router::getInstance();

// ─── Rutas Públicas ─────────────────────────────────────────────────────
$router->get('/login', ['AuthController', 'mostrarLogin']);
$router->post('/login', ['AuthController', 'login']);
$router->get('/logout', ['AuthController', 'logout']);

// ─── Redirección raíz ───────────────────────────────────────────────────
$router->get('/', ['AuthController', 'inicio']);

// ─── Dashboard (todos los roles) ────────────────────────────────────────
$router->get('/dashboard', ['DashboardController', 'index'], [['AuthMiddleware', 'auth']]);

// ─── Rutas de Administrador ─────────────────────────────────────────────
$router->get('/admin/personas', ['PersonaController', 'listar'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/personas/crear', ['PersonaController', 'crear'], [['AuthMiddleware', 'soloAdmin']]);
$router->get('/api/personas/buscar/{id}', ['PersonaController', 'buscarAjax'], [['AuthMiddleware', 'soloAdmin']]);
$router->get('/admin/personas/ver/{id}', ['PersonaController', 'ver'], [['AuthMiddleware', 'soloAdmin']]);
$router->get('/admin/personas/editar/{id}', ['PersonaController', 'editar'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/personas/actualizar/{id}', ['PersonaController', 'actualizar'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/personas/eliminar/{id}', ['PersonaController', 'eliminar'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/personas/activar/{id}', ['PersonaController', 'activar'], [['AuthMiddleware', 'soloAdmin']]);

$router->get('/admin/usuarios', ['AdminController', 'usuarios'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/usuarios/crear', ['AdminController', 'crearUsuario'], [['AuthMiddleware', 'soloAdmin']]);
$router->get('/admin/usuarios/editar/{id}', ['AdminController', 'editarUsuario'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/usuarios/actualizar/{id}', ['AdminController', 'actualizarUsuario'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/usuarios/password/{id}', ['AdminController', 'cambiarPassword'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/usuarios/toggle/{id}', ['AdminController', 'toggleEstatus'], [['AuthMiddleware', 'soloAdmin']]);
// Estudiantes
$router->get('/admin/estudiantes', ['EstudiantesController', 'index'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/estudiantes/crear', ['EstudiantesController', 'crear'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/estudiantes/actualizar/{id}', ['EstudiantesController', 'actualizar'], [['AuthMiddleware', 'soloAdmin']]);
$router->get('/admin/estudiantes/ver/{id}', ['EstudiantesController', 'ver'], [['AuthMiddleware', 'soloAdmin']]);

// Docentes
$router->get('/admin/docentes', ['DocentesController', 'index'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/docentes/crear', ['DocentesController', 'crear'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/docentes/actualizar/{id}', ['DocentesController', 'actualizar'], [['AuthMiddleware', 'soloAdmin']]);

$router->get('/admin/periodos', ['AdminController', 'periodos'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/periodos/crear', ['AdminController', 'crearPeriodo'], [['AuthMiddleware', 'soloAdmin']]);
$router->get('/admin/cargas', ['AdminController', 'cargas'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/cargas/crear', ['AdminController', 'crearCarga'], [['AuthMiddleware', 'soloAdmin']]);

// Roles
$router->get('/admin/roles', ['RolesController', 'index'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/roles/crear', ['RolesController', 'crear'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/roles/actualizar/{id}', ['RolesController', 'actualizar'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/roles/toggle/{id}', ['RolesController', 'toggleEstatus'], [['AuthMiddleware', 'soloAdmin']]);

// Permisos
$router->get('/admin/permisos', ['PermisosController', 'index'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/permisos/crear', ['PermisosController', 'crear'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/permisos/actualizar/{id}', ['PermisosController', 'actualizar'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/permisos/toggle/{id}', ['PermisosController', 'toggleEstatus'], [['AuthMiddleware', 'soloAdmin']]);

// ─── Rutas de Docente ───────────────────────────────────────────────────
$router->get('/docente/mis-cargas', ['CargaController', 'misCargas'], [['AuthMiddleware', 'soloDocente']]);
$router->get('/docente/plan-evaluacion/{id}', ['PlanEvalController', 'mostrar'], [['AuthMiddleware', 'soloDocente']]);
$router->post('/docente/plan-evaluacion/{id}', ['PlanEvalController', 'guardar'], [['AuthMiddleware', 'soloDocente']]);
$router->get('/docente/calificaciones/{id}', ['CalificacionController', 'mostrar'], [['AuthMiddleware', 'soloDocente']]);
$router->post('/docente/calificaciones/{id}', ['CalificacionController', 'guardar'], [['AuthMiddleware', 'soloDocente']]);
$router->get('/docente/acta/{id}', ['ActaController', 'mostrar'], [['AuthMiddleware', 'soloDocente']]);
$router->post('/docente/acta/generar/{id}', ['ActaController', 'generar'], [['AuthMiddleware', 'soloDocente']]);

// ─── Rutas de Estudiante ────────────────────────────────────────────────
$router->get('/estudiante/notas', ['EstudianteController', 'misNotas'], [['AuthMiddleware', 'soloEstudiante']]);
$router->get('/estudiante/historico', ['HistoricoController', 'historico'], [['AuthMiddleware', 'soloEstudiante']]);
$router->get('/estudiante/indice', ['HistoricoController', 'indice'], [['AuthMiddleware', 'soloEstudiante']]);

// ─── Rutas de Control de Estudios ───────────────────────────────────────
$router->get('/control/inscripciones', ['InscripcionController', 'listar'], [['AuthMiddleware', 'soloControlEstudios']]);
$router->post('/control/inscripciones/crear', ['InscripcionController', 'crear'], [['AuthMiddleware', 'soloControlEstudios']]);
$router->get('/reparaciones', ['ReparacionController', 'listar'], [['AuthMiddleware', 'academico']]);
$router->post('/reparaciones/solicitar', ['ReparacionController', 'solicitar'], [['AuthMiddleware', 'soloControlEstudios']]);
$router->post('/reparaciones/calificar/{id}', ['ReparacionController', 'calificar'], [['AuthMiddleware', 'soloDocente']]);

// ─── Rutas de Finanzas ──────────────────────────────────────────────────
$router->get('/finanzas/pagos', ['FinanzasController', 'pagos'], [['AuthMiddleware', 'soloFinanzas']]);
$router->post('/finanzas/pagos/registrar', ['FinanzasController', 'registrarPago'], [['AuthMiddleware', 'soloFinanzas']]);
$router->get('/finanzas/aranceles', ['FinanzasController', 'aranceles'], [['AuthMiddleware', 'soloFinanzas']]);
$router->post('/finanzas/aranceles/crear', ['FinanzasController', 'crearArancel'], [['AuthMiddleware', 'soloFinanzas']]);
$router->get('/finanzas/solvencias', ['FinanzasController', 'solvencias'], [['AuthMiddleware', 'soloFinanzas']]);
$router->get('/finanzas/solvencias/{id}', ['FinanzasController', 'verificarSolvencia'], [['AuthMiddleware', 'soloFinanzas']]);

// ─── API RESTful ────────────────────────────────────────────────────────
// Nota: Para proteger la API, idealmente se usaría un middleware de tokens,
// pero por ahora reutilizamos la sesión o dejamos libre para probar en Postman (remover middleware para test puro)
$router->get('/api/v1/usuarios', ['ApiUsuarioController', 'listar']);
$router->get('/api/v1/usuarios/{id}', ['ApiUsuarioController', 'obtener']);
$router->put('/api/v1/usuarios/{id}', ['ApiUsuarioController', 'actualizar']);
$router->delete('/api/v1/usuarios/{id}', ['ApiUsuarioController', 'desactivar']);

