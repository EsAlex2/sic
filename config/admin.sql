
CREATE TABLE admin.roles (
    id SERIAL PRIMARY KEY,
    rol_name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT
);

CREATE TABLE admin.estatus (
    id SERIAL PRIMARY KEY,
    status_name VARCHAR (100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin.modules (
    id SERIAL PRIMARY KEY,
    module_name VARCHAR(50) UNIQUE NOT NULL, 
    orders INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin.permissions (
    id SERIAL PRIMARY KEY,
    id_module INTEGER REFERENCES admin.modules(id) ON DELETE CASCADE,
    permissions_name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin.person (
    id SERIAL PRIMARY KEY,
    id_permissions INTEGER REFERENCES admin.estatus(id) ON DELETE CASCADE,
    identity INT UNIQUE NOT NULL,
    name1 VARCHAR(50) NOT NULL,
    name2 VARCHAR(50),
    lastname1 VARCHAR(50) NOT NULL,
    lastname2 VARCHAR(50),
    gender VARCHAR(30) NOT NULL,
    birthdate DATE NOT NULL,
    email1 VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR (20) NOT NULL,
    address TEXT,
    cod_person VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE TABLE admin.users (
    id SERIAL PRIMARY KEY,
    id_person INTEGER UNIQUE REFERENCES admin.person(id) ON DELETE CASCADE,
    identity VARCHAR(15) UNIQUE NOT NULL,
    id_roles INTEGER REFERENCES admin.roles(id) ON DELETE CASCADE,
    id_estatus INTEGER REFERENCES admin.estatus(id) ON DELETE CASCADE,
    email2 VARCHAR(150) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	last_login TIMESTAMP
);

CREATE TABLE admin.profiles (
    id_role INTEGER REFERENCES admin.roles(id) ON DELETE CASCADE,
    id_permissions INTEGER REFERENCES admin.permissions(id) ON DELETE CASCADE,
    id_users INTEGER REFERENCES admin.users(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP,
    PRIMARY KEY (id_role, id_permissions, id_users)
);

CREATE TABLE admin.cod_phone (
    id SERIAL PRIMARY KEY,
    description TEXT,
    operator VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin.bank (
    id SERIAL PRIMARY KEY,
    bank_name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin.pay_method (
    id SERIAL PRIMARY KEY,
    description VARCHAR(40),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin.payments (
    id SERIAL PRIMARY KEY,
    id_estatus INTEGER REFERENCES admin.estatus(id) ON DELETE CASCADE,
    id_users INTEGER REFERENCES admin.users(id) ON DELETE CASCADE,
    id_bank INTEGER REFERENCES admin.bank(id) ON DELETE CASCADE,
    id_pay_methond INTEGER REFERENCES admin.pay_method(id) ON DELETE CASCADE,
    id_phone INTEGER REFERENCES admin.cod_phone(id) ON DELETE CASCADE,
    referencia_bancaria VARCHAR(100) UNIQUE NOT NULL,
    fecha_pago DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
