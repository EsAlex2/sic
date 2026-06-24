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

$router->get('/admin/usuarios', ['AdminController', 'usuarios'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/usuarios/crear', ['AdminController', 'crearUsuario'], [['AuthMiddleware', 'soloAdmin']]);
$router->get('/admin/periodos', ['AdminController', 'periodos'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/periodos/crear', ['AdminController', 'crearPeriodo'], [['AuthMiddleware', 'soloAdmin']]);
$router->get('/admin/cargas', ['AdminController', 'cargas'], [['AuthMiddleware', 'soloAdmin']]);
$router->post('/admin/cargas/crear', ['AdminController', 'crearCarga'], [['AuthMiddleware', 'soloAdmin']]);

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
