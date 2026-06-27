ALTER TABLE unexca.tipos_usuario ADD COLUMN IF NOT EXISTS id_estatus INTEGER REFERENCES unexca.estatus(id_estatus) DEFAULT 1;
ALTER TABLE unexca.permisos ADD COLUMN IF NOT EXISTS id_estatus INTEGER REFERENCES unexca.estatus(id_estatus) DEFAULT 1;
