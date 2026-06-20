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