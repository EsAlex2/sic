<?php
/**
 * SIC — Front Controller (Entry Point)
 * Todas las peticiones pasan por aquí gracias al .htaccess
 */

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/helpers/Router.php';
require_once __DIR__ . '/helpers/Session.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';

// Cargar rutas
require_once __DIR__ . '/routes/web.php';

// Despachar la petición
$router = Router::getInstance();
$router->dispatch();
