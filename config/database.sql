-- ═══════════════════════════════════════════════════════════════════════════
-- UNEXCA — SCHEMA BASE UNIFICADO
-- Base de datos: PostgreSQL | Schema: unexca
-- Autor: SIC – Sistema de Información y Control
-- ═══════════════════════════════════════════════════════════════════════════
-- NOTA: Ejecutar en orden. Todas las tablas usan el schema 'unexca'.
-- Antes de ejecutar: CREATE SCHEMA IF NOT EXISTS unexca;
-- ═══════════════════════════════════════════════════════════════════════════

CREATE SCHEMA IF NOT EXISTS unexca;

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  TABLAS BASE (sin dependencias)                                         │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.estatus (
    id_estatus SERIAL PRIMARY KEY,
    nombre_estatus VARCHAR(100) NOT NULL,
    descripcion TEXT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE unexca.sedes_unexca (
    id_sede SERIAL PRIMARY KEY,
    nombre_sede VARCHAR(100) NOT NULL,
    correo_institucional VARCHAR(100) UNIQUE NOT NULL,
    direccion TEXT
);

CREATE TABLE unexca.trayectos (
    id_trayecto SERIAL PRIMARY KEY,
    cod_trayecto VARCHAR(10) NOT NULL,
    descripcion TEXT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE unexca.periodo_academico (
    id_periodo SERIAL PRIMARY KEY,
    periodo VARCHAR(10) NOT NULL UNIQUE,
    fecha_inicio DATE NOT NULL,
    fecha_final DATE NOT NULL,
    estado BOOLEAN DEFAULT TRUE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT check_fechas CHECK (fecha_final > fecha_inicio)
);

CREATE TABLE unexca.secciones (
    id_seccion SERIAL PRIMARY KEY,
    cod_seccion VARCHAR(15) NOT NULL,
    capacidad_max INTEGER DEFAULT 40,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP,
    CONSTRAINT check_capacidad_max CHECK (capacidad_max >= 0 AND capacidad_max <= 40)
);

CREATE TABLE unexca.turnos (
    id_turno SERIAL PRIMARY KEY,
    turno VARCHAR(50) NOT NULL,
    descripcion TEXT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE unexca.aulas (
    id_aula SERIAL PRIMARY KEY,
    piso VARCHAR(15) NOT NULL,
    nro_aula VARCHAR(15) NOT NULL,
    nombre_aula VARCHAR(20) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE unexca.requisitos (
    id_requisito SERIAL PRIMARY KEY,
    nombre_requisito VARCHAR(100) NOT NULL,
    categoria_estudiante VARCHAR(50),
    descripcion TEXT,
    es_obligatorio BOOLEAN DEFAULT TRUE
);

CREATE TABLE unexca.modulos (
    id_modulo SERIAL PRIMARY KEY,
    nombre_modulo VARCHAR(50) UNIQUE NOT NULL,
    orden INTEGER DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE unexca.categorias_conf (
    id_categoria SERIAL PRIMARY KEY,
    nombre_categoria VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP
);

CREATE TABLE unexca.caracter_asignatura (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(60) NOT NULL,
    descripcion TEXT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE unexca.tipos_usuario (
    id_tipo SERIAL PRIMARY KEY,
    nombre_tipo VARCHAR(50) UNIQUE NOT NULL,
    descripcion TEXT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE unexca.cod_telefono (
    id_cod_telefono SERIAL PRIMARY KEY,
    descripcion TEXT,
    operadora VARCHAR(30),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE unexca.bancos (
    id_banco SERIAL PRIMARY KEY,
    nombre_banco VARCHAR(80) NOT NULL,
    descripcion TEXT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE unexca.metodo_pago (
    id_metodo SERIAL PRIMARY KEY,
    descripcion VARCHAR(60),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  TABLAS CON UNA DEPENDENCIA                                            │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.configuraciones (
    id SERIAL PRIMARY KEY,
    clave VARCHAR(100) NOT NULL UNIQUE,
    valor TEXT,
    descripcion TEXT,
    id_categoria INTEGER REFERENCES unexca.categorias_conf(id_categoria) ON DELETE CASCADE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP
);

CREATE TABLE unexca.permisos (
    id_permiso SERIAL PRIMARY KEY,
    nombre_permiso VARCHAR(100) UNIQUE NOT NULL,
    descripcion TEXT,
    id_modulo INTEGER REFERENCES unexca.modulos(id_modulo) ON DELETE CASCADE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE unexca.pnf (
    id_pnf SERIAL PRIMARY KEY,
    id_sede INTEGER REFERENCES unexca.sedes_unexca(id_sede) ON DELETE CASCADE,
    cod_pnf VARCHAR(20) NOT NULL,
    nombre_pnf VARCHAR(100) NOT NULL,
    descripcion TEXT,
    duracion_pnf INTEGER NOT NULL,
    unidad_total_creditos INTEGER NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE unexca.aranceles (
    id_arancel SERIAL PRIMARY KEY,
    id_estatus INTEGER REFERENCES unexca.estatus(id_estatus) ON DELETE CASCADE,
    descripcion VARCHAR(100) NOT NULL,
    monto DECIMAL(12,2) NOT NULL
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  PERSONAS, USUARIOS Y PERFILES                                         │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.datos_personas (
    id_persona SERIAL PRIMARY KEY,
    id_estatus INTEGER REFERENCES unexca.estatus(id_estatus) ON DELETE CASCADE,
    cedula_identidad INT UNIQUE NOT NULL,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    genero VARCHAR(30) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    correo_personal VARCHAR(150) UNIQUE NOT NULL,
    telefono_personal VARCHAR(20),
    direccion_habitacion TEXT,
    fecha_ingreso DATE NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP
);

CREATE TABLE unexca.usuarios (
    id_usuario SERIAL PRIMARY KEY,
    id_persona INTEGER UNIQUE REFERENCES unexca.datos_personas(id_persona) ON DELETE CASCADE,
    cedula VARCHAR(15) UNIQUE NOT NULL,
    correo_institucional VARCHAR(150) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    id_tipo INTEGER REFERENCES unexca.tipos_usuario(id_tipo) ON DELETE CASCADE,
    id_estatus INTEGER REFERENCES unexca.estatus(id_estatus) ON DELETE CASCADE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_login TIMESTAMP
);

CREATE TABLE unexca.perfiles (
    id_tipo INTEGER REFERENCES unexca.tipos_usuario(id_tipo) ON DELETE CASCADE,
    id_permiso INTEGER REFERENCES unexca.permisos(id_permiso) ON DELETE CASCADE,
    id_usuario INTEGER REFERENCES unexca.usuarios(id_usuario) ON DELETE CASCADE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP,
    PRIMARY KEY (id_tipo, id_permiso, id_usuario)
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  DOCENTES Y ESTUDIANTES                                                │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.datos_docentes (
    id_docente SERIAL PRIMARY KEY,
    id_persona INTEGER UNIQUE REFERENCES unexca.datos_personas(id_persona) ON DELETE CASCADE,
    id_pnf INTEGER REFERENCES unexca.pnf(id_pnf) ON DELETE CASCADE,
    id_sede INTEGER REFERENCES unexca.sedes_unexca(id_sede) ON DELETE CASCADE,
    fecha_ingreso DATE NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP
);

CREATE TABLE unexca.datos_estudiantes (
    id_estudiante SERIAL PRIMARY KEY,
    id_estatus INTEGER REFERENCES unexca.estatus(id_estatus) ON DELETE CASCADE,
    id_persona INTEGER UNIQUE REFERENCES unexca.datos_personas(id_persona) ON DELETE CASCADE,
    id_trayecto INTEGER REFERENCES unexca.trayectos(id_trayecto) ON DELETE CASCADE,
    id_pnf INTEGER REFERENCES unexca.pnf(id_pnf) ON DELETE CASCADE,
    id_sede INTEGER REFERENCES unexca.sedes_unexca(id_sede) ON DELETE CASCADE,
    fecha_ingreso DATE NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP
);

CREATE TABLE unexca.expediente_estudiante (
    id_expediente SERIAL PRIMARY KEY,
    id_persona INTEGER REFERENCES unexca.datos_personas(id_persona) ON DELETE CASCADE,
    cod_expediente VARCHAR(50) UNIQUE NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  ASIGNATURAS Y HORARIOS                                                │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.asignatura (
    id_asignatura SERIAL PRIMARY KEY,
    id_pnf INTEGER REFERENCES unexca.pnf(id_pnf) ON DELETE CASCADE,
    id_trayecto INTEGER REFERENCES unexca.trayectos(id_trayecto) ON DELETE CASCADE,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    unidades_credito INTEGER NOT NULL,
    id_caracter INTEGER REFERENCES unexca.caracter_asignatura(id) ON DELETE CASCADE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP
);

CREATE TABLE unexca.horarios (
    id_horario SERIAL PRIMARY KEY,
    id_asignatura INTEGER REFERENCES unexca.asignatura(id_asignatura) ON DELETE CASCADE,
    id_seccion INTEGER REFERENCES unexca.secciones(id_seccion) ON DELETE CASCADE,
    id_docente INTEGER REFERENCES unexca.datos_docentes(id_docente) ON DELETE CASCADE,
    id_aula INTEGER REFERENCES unexca.aulas(id_aula) ON DELETE CASCADE,
    id_turno INTEGER REFERENCES unexca.turnos(id_turno) ON DELETE CASCADE,
    id_trayecto INTEGER REFERENCES unexca.trayectos(id_trayecto) ON DELETE CASCADE,
    cod_horario VARCHAR(20) NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  INSCRIPCIONES                                                         │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.inscripcion_nue_ingreso (
    id_inscripcion SERIAL PRIMARY KEY,
    id_estudiante INTEGER REFERENCES unexca.datos_estudiantes(id_estudiante) ON DELETE CASCADE,
    id_periodo INTEGER REFERENCES unexca.periodo_academico(id_periodo) ON DELETE CASCADE,
    id_seccion INTEGER REFERENCES unexca.secciones(id_seccion) ON DELETE CASCADE,
    id_pnf INTEGER REFERENCES unexca.pnf(id_pnf) ON DELETE CASCADE,
    id_sede INTEGER REFERENCES unexca.sedes_unexca(id_sede) ON DELETE CASCADE,
    id_trayecto INTEGER REFERENCES unexca.trayectos(id_trayecto) ON DELETE CASCADE,
    id_estatus_inscripcion INTEGER REFERENCES unexca.estatus(id_estatus) ON DELETE CASCADE,
    fecha_formalizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT unico_estudiante_periodo UNIQUE(id_estudiante, id_periodo)
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  PAGOS                                                                 │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.pagos (
    id_pago SERIAL PRIMARY KEY,
    id_arancel INTEGER REFERENCES unexca.aranceles(id_arancel) ON DELETE CASCADE,
    id_estatus INTEGER REFERENCES unexca.estatus(id_estatus) ON DELETE CASCADE,
    id_persona INTEGER REFERENCES unexca.datos_personas(id_persona) ON DELETE CASCADE,
    id_banco INTEGER REFERENCES unexca.bancos(id_banco) ON DELETE CASCADE,
    id_metodo INTEGER REFERENCES unexca.metodo_pago(id_metodo) ON DELETE CASCADE,
    referencia_bancaria VARCHAR(100) UNIQUE NOT NULL,
    fecha_pago DATE NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  REQUISITOS DE ESTUDIANTES                                             │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.estudiante_requisitos (
    id SERIAL PRIMARY KEY,
    id_estudiante INTEGER REFERENCES unexca.datos_estudiantes(id_estudiante) ON DELETE CASCADE,
    id_requisito INTEGER REFERENCES unexca.requisitos(id_requisito) ON DELETE CASCADE,
    id_estatus INTEGER REFERENCES unexca.estatus(id_estatus) ON DELETE CASCADE,
    url_archivo TEXT,
    fecha_entrega TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    observaciones TEXT,
    actualizado_en TIMESTAMP
);
