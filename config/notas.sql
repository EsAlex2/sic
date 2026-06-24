-- ═══════════════════════════════════════════════════════════════════════════
-- UNEXCA — SISTEMA ACADÉMICO DE NOTAS
-- Escala: 0–20 puntos | Nota aprobatoria: >= 10 | 3 Cortes evaluativos
-- Incluye: Módulo de Exámenes de Reparación
-- Requisito: Ejecutar database.sql primero
-- ═══════════════════════════════════════════════════════════════════════════

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  1. PENSUM ACADÉMICO                                                   │
-- │  Agrupa las asignaturas que pertenecen a cada PNF en cada trayecto.    │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.pensum (
    id_pensum SERIAL PRIMARY KEY,
    id_pnf INTEGER REFERENCES unexca.pnf(id_pnf) ON DELETE CASCADE,
    id_trayecto INTEGER REFERENCES unexca.trayectos(id_trayecto) ON DELETE CASCADE,
    id_asignatura INTEGER REFERENCES unexca.asignatura(id_asignatura) ON DELETE CASCADE,
    es_electiva BOOLEAN DEFAULT FALSE,
    id_estatus INTEGER REFERENCES unexca.estatus(id_estatus),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(id_pnf, id_trayecto, id_asignatura)
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  2. PRELACIONES (Prerrequisitos entre asignaturas)                     │
-- │  Define qué asignaturas debe aprobar un estudiante antes de inscribir  │
-- │  otra asignatura.                                                      │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.prelaciones (
    id_prelacion SERIAL PRIMARY KEY,
    id_asignatura INTEGER REFERENCES unexca.asignatura(id_asignatura) ON DELETE CASCADE,
    id_asignatura_requisito INTEGER REFERENCES unexca.asignatura(id_asignatura) ON DELETE CASCADE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT no_auto_prelacion CHECK (id_asignatura <> id_asignatura_requisito),
    UNIQUE(id_asignatura, id_asignatura_requisito)
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  3. CARGA ACADÉMICA                                                    │
-- │  Asigna un docente a una asignatura en una sección para un período.    │
-- │  Es la unidad central del sistema de notas.                            │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.carga_academica (
    id_carga SERIAL PRIMARY KEY,
    id_periodo INTEGER REFERENCES unexca.periodo_academico(id_periodo) ON DELETE CASCADE,
    id_docente INTEGER REFERENCES unexca.datos_docentes(id_docente) ON DELETE CASCADE,
    id_asignatura INTEGER REFERENCES unexca.asignatura(id_asignatura) ON DELETE CASCADE,
    id_seccion INTEGER REFERENCES unexca.secciones(id_seccion) ON DELETE CASCADE,
    id_sede INTEGER REFERENCES unexca.sedes_unexca(id_sede) ON DELETE CASCADE,
    id_estatus INTEGER REFERENCES unexca.estatus(id_estatus),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP,
    UNIQUE(id_periodo, id_docente, id_asignatura, id_seccion)
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  4. INSCRIPCIÓN DE ASIGNATURAS                                         │
-- │  Registra que un estudiante está inscrito en una carga académica.      │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.inscripcion_asignatura (
    id_inscripcion_asig SERIAL PRIMARY KEY,
    id_estudiante INTEGER REFERENCES unexca.datos_estudiantes(id_estudiante) ON DELETE CASCADE,
    id_carga INTEGER REFERENCES unexca.carga_academica(id_carga) ON DELETE CASCADE,
    id_estatus INTEGER REFERENCES unexca.estatus(id_estatus),
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(id_estudiante, id_carga)
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  5. TIPOS DE EVALUACIÓN                                                │
-- │  Catálogo de tipos: parcial, quiz, taller, exposición, proyecto, etc.  │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.tipos_evaluacion (
    id_tipo_eval SERIAL PRIMARY KEY,
    nombre VARCHAR(60) NOT NULL UNIQUE,
    descripcion TEXT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  6. PLAN DE EVALUACIÓN (3 Cortes)                                      │
-- │  El docente define las evaluaciones de su carga académica.             │
-- │  Cada evaluación pertenece a un corte (1, 2 o 3).                     │
-- │  La suma de porcentajes por corte y global debe validarse en la app.   │
-- │                                                                        │
-- │  Distribución estándar UNEXCA:                                        │
-- │    Corte 1: 30%  |  Corte 2: 30%  |  Corte 3: 40%                    │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.plan_evaluacion (
    id_plan SERIAL PRIMARY KEY,
    id_carga INTEGER REFERENCES unexca.carga_academica(id_carga) ON DELETE CASCADE,
    id_tipo_eval INTEGER REFERENCES unexca.tipos_evaluacion(id_tipo_eval),
    nombre_evaluacion VARCHAR(100) NOT NULL,
    porcentaje DECIMAL(5,2) NOT NULL,
    fecha_evaluacion DATE,
    nro_corte INTEGER NOT NULL DEFAULT 1,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP,
    CONSTRAINT check_porcentaje CHECK (porcentaje > 0 AND porcentaje <= 100),
    CONSTRAINT check_corte CHECK (nro_corte BETWEEN 1 AND 3)
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  7. CALIFICACIONES                                                     │
-- │  Nota obtenida por cada estudiante en cada evaluación.                │
-- │  Rango: 0.00 – 20.00                                                  │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.calificaciones (
    id_calificacion SERIAL PRIMARY KEY,
    id_inscripcion_asig INTEGER REFERENCES unexca.inscripcion_asignatura(id_inscripcion_asig) ON DELETE CASCADE,
    id_plan INTEGER REFERENCES unexca.plan_evaluacion(id_plan) ON DELETE CASCADE,
    nota DECIMAL(4,2) NOT NULL DEFAULT 0.00,
    observacion TEXT,
    calificado_por INTEGER REFERENCES unexca.datos_docentes(id_docente),
    fecha_calificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT check_nota CHECK (nota >= 0 AND nota <= 20),
    UNIQUE(id_inscripcion_asig, id_plan)
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  8. ACTA DE NOTAS                                                      │
-- │  Documento oficial que cierra las calificaciones de una sección.       │
-- │  Una vez cerrada, las notas no pueden modificarse sin auditoría.       │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.actas_notas (
    id_acta SERIAL PRIMARY KEY,
    id_carga INTEGER REFERENCES unexca.carga_academica(id_carga) ON DELETE CASCADE,
    id_periodo INTEGER REFERENCES unexca.periodo_academico(id_periodo),
    cod_acta VARCHAR(30) UNIQUE NOT NULL,
    fecha_cierre DATE NOT NULL,
    id_estatus INTEGER REFERENCES unexca.estatus(id_estatus),
    observaciones TEXT,
    cerrada_por INTEGER REFERENCES unexca.usuarios(id_usuario),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  9. NOTAS FINALES                                                      │
-- │  Nota definitiva del estudiante en cada asignatura.                   │
-- │  Se calcula: SUM(nota × porcentaje / 100) de todas las evaluaciones.  │
-- │  Aprobado: >= 10.00 | Reprobado: < 10.00                              │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.notas_finales (
    id_nota_final SERIAL PRIMARY KEY,
    id_acta INTEGER REFERENCES unexca.actas_notas(id_acta) ON DELETE CASCADE,
    id_inscripcion_asig INTEGER REFERENCES unexca.inscripcion_asignatura(id_inscripcion_asig) ON DELETE CASCADE,
    nota_definitiva DECIMAL(4,2) NOT NULL DEFAULT 0.00,
    id_estatus INTEGER REFERENCES unexca.estatus(id_estatus),
    observacion TEXT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT check_nota_def CHECK (nota_definitiva >= 0 AND nota_definitiva <= 20),
    UNIQUE(id_acta, id_inscripcion_asig)
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  10. AUDITORÍA DE NOTAS                                                │
-- │  Registra CADA cambio de nota con el usuario responsable y motivo.    │
-- │  Crítico para transparencia institucional y procesos de revisión.     │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.auditoria_notas (
    id_auditoria SERIAL PRIMARY KEY,
    id_calificacion INTEGER REFERENCES unexca.calificaciones(id_calificacion),
    nota_anterior DECIMAL(4,2),
    nota_nueva DECIMAL(4,2),
    motivo TEXT NOT NULL,
    modificado_por INTEGER REFERENCES unexca.usuarios(id_usuario),
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  11. HISTÓRICO ACADÉMICO                                               │
-- │  Récord acumulado del estudiante. Un registro por asignatura cursada. │
-- │  Si el estudiante repite, se crea un nuevo registro con intento + 1.  │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.historico_academico (
    id_historico SERIAL PRIMARY KEY,
    id_estudiante INTEGER REFERENCES unexca.datos_estudiantes(id_estudiante) ON DELETE CASCADE,
    id_periodo INTEGER REFERENCES unexca.periodo_academico(id_periodo),
    id_asignatura INTEGER REFERENCES unexca.asignatura(id_asignatura),
    nota_definitiva DECIMAL(4,2) NOT NULL,
    unidades_credito INTEGER NOT NULL,
    id_estatus INTEGER REFERENCES unexca.estatus(id_estatus),
    intento INTEGER DEFAULT 1,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(id_estudiante, id_periodo, id_asignatura)
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  12. ÍNDICE ACADÉMICO                                                  │
-- │  Promedio ponderado por período y acumulado.                           │
-- │  Fórmula: SUM(nota × UC) / SUM(UC)                                    │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.indice_academico (
    id_indice SERIAL PRIMARY KEY,
    id_estudiante INTEGER REFERENCES unexca.datos_estudiantes(id_estudiante) ON DELETE CASCADE,
    id_periodo INTEGER REFERENCES unexca.periodo_academico(id_periodo),
    promedio_periodo DECIMAL(4,2) NOT NULL DEFAULT 0.00,
    promedio_acumulado DECIMAL(4,2) NOT NULL DEFAULT 0.00,
    creditos_aprobados INTEGER DEFAULT 0,
    creditos_cursados INTEGER DEFAULT 0,
    calculado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(id_estudiante, id_periodo)
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  13. MÓDULO DE REPARACIÓN                                              │
-- │  Permite al estudiante que reprobó una asignatura presentar un         │
-- │  examen de reparación. La nota máxima obtenible es 10 puntos.         │
-- │                                                                        │
-- │  Reglas de negocio:                                                    │
-- │    - Solo aplica si nota_definitiva >= 05.00 y < 10.00                │
-- │    - El estudiante tiene derecho a 1 reparación por asignatura/período│
-- │    - Si aprueba (>= 10), la nota final se registra como 10.00         │
-- │    - Si reprueba el examen de reparación, mantiene la nota original   │
-- │    - El pago del arancel de reparación debe estar conciliado           │
-- └─────────────────────────────────────────────────────────────────────────┘

CREATE TABLE unexca.examenes_reparacion (
    id_reparacion SERIAL PRIMARY KEY,
    id_inscripcion_asig INTEGER REFERENCES unexca.inscripcion_asignatura(id_inscripcion_asig) ON DELETE CASCADE,
    id_periodo INTEGER REFERENCES unexca.periodo_academico(id_periodo),
    id_docente_evaluador INTEGER REFERENCES unexca.datos_docentes(id_docente),
    id_pago INTEGER REFERENCES unexca.pagos(id_pago),
    nota_reparacion DECIMAL(4,2) NOT NULL DEFAULT 0.00,
    nota_original DECIMAL(4,2) NOT NULL,
    nota_final_post_reparacion DECIMAL(4,2),
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_examen DATE,
    id_estatus INTEGER REFERENCES unexca.estatus(id_estatus),
    observacion TEXT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP,
    CONSTRAINT check_nota_reparacion CHECK (nota_reparacion >= 0 AND nota_reparacion <= 20),
    CONSTRAINT check_nota_original CHECK (nota_original >= 0 AND nota_original < 10),
    UNIQUE(id_inscripcion_asig, id_periodo)
);

-- ┌─────────────────────────────────────────────────────────────────────────┐
-- │  VISTAS ÚTILES                                                         │
-- └─────────────────────────────────────────────────────────────────────────┘

-- Vista: Notas por corte de un estudiante en una carga académica
CREATE OR REPLACE VIEW unexca.v_notas_por_corte AS
SELECT
    ia.id_estudiante,
    ca.id_carga,
    ca.id_asignatura,
    ca.id_periodo,
    pe.nro_corte,
    ROUND(SUM(c.nota * pe.porcentaje / 100), 2) AS nota_corte,
    SUM(pe.porcentaje) AS porcentaje_total_corte
FROM unexca.calificaciones c
JOIN unexca.plan_evaluacion pe ON pe.id_plan = c.id_plan
JOIN unexca.inscripcion_asignatura ia ON ia.id_inscripcion_asig = c.id_inscripcion_asig
JOIN unexca.carga_academica ca ON ca.id_carga = ia.id_carga
GROUP BY ia.id_estudiante, ca.id_carga, ca.id_asignatura, ca.id_periodo, pe.nro_corte
ORDER BY ia.id_estudiante, pe.nro_corte;

-- Vista: Nota definitiva calculada por estudiante y asignatura
CREATE OR REPLACE VIEW unexca.v_nota_definitiva_calculada AS
SELECT
    ia.id_inscripcion_asig,
    ia.id_estudiante,
    ca.id_asignatura,
    a.nombre AS nombre_asignatura,
    ca.id_periodo,
    ROUND(SUM(c.nota * pe.porcentaje / 100), 2) AS nota_calculada,
    CASE
        WHEN ROUND(SUM(c.nota * pe.porcentaje / 100), 2) >= 10.00 THEN 'Aprobado'
        WHEN ROUND(SUM(c.nota * pe.porcentaje / 100), 2) >= 5.00 THEN 'Reprobado (Aplica Reparación)'
        ELSE 'Reprobado'
    END AS condicion
FROM unexca.calificaciones c
JOIN unexca.plan_evaluacion pe ON pe.id_plan = c.id_plan
JOIN unexca.inscripcion_asignatura ia ON ia.id_inscripcion_asig = c.id_inscripcion_asig
JOIN unexca.carga_academica ca ON ca.id_carga = ia.id_carga
JOIN unexca.asignatura a ON a.id_asignatura = ca.id_asignatura
GROUP BY ia.id_inscripcion_asig, ia.id_estudiante, ca.id_asignatura, a.nombre, ca.id_periodo;

-- Vista: Índice académico acumulado por estudiante
CREATE OR REPLACE VIEW unexca.v_indice_acumulado AS
SELECT
    h.id_estudiante,
    dp.nombres || ' ' || dp.apellidos AS nombre_completo,
    ROUND(SUM(h.nota_definitiva * h.unidades_credito)::NUMERIC / NULLIF(SUM(h.unidades_credito), 0), 2) AS promedio_acumulado,
    SUM(CASE WHEN e.nombre_estatus = 'Aprobado' THEN h.unidades_credito ELSE 0 END) AS creditos_aprobados,
    SUM(h.unidades_credito) AS creditos_cursados
FROM unexca.historico_academico h
JOIN unexca.datos_estudiantes de ON de.id_estudiante = h.id_estudiante
JOIN unexca.datos_personas dp ON dp.id_persona = de.id_persona
JOIN unexca.estatus e ON e.id_estatus = h.id_estatus
GROUP BY h.id_estudiante, dp.nombres, dp.apellidos;

-- Vista: Estudiantes que aplican a reparación
CREATE OR REPLACE VIEW unexca.v_estudiantes_aplican_reparacion AS
SELECT
    ndc.id_inscripcion_asig,
    ndc.id_estudiante,
    ndc.id_asignatura,
    ndc.nombre_asignatura,
    ndc.id_periodo,
    ndc.nota_calculada,
    CASE
        WHEN er.id_reparacion IS NOT NULL THEN 'Reparación Solicitada'
        ELSE 'Pendiente de Solicitud'
    END AS estado_reparacion
FROM unexca.v_nota_definitiva_calculada ndc
LEFT JOIN unexca.examenes_reparacion er
    ON er.id_inscripcion_asig = ndc.id_inscripcion_asig
WHERE ndc.nota_calculada >= 5.00 AND ndc.nota_calculada < 10.00;

-- ═══════════════════════════════════════════════════════════════════════════
-- FUNCIONES UTILITARIAS
-- ═══════════════════════════════════════════════════════════════════════════

-- Función: Calcular nota definitiva de un estudiante en una inscripción
CREATE OR REPLACE FUNCTION unexca.fn_calcular_nota_definitiva(
    p_id_inscripcion_asig INTEGER
) RETURNS DECIMAL(4,2) AS $$
DECLARE
    v_nota DECIMAL(4,2);
BEGIN
    SELECT ROUND(SUM(c.nota * pe.porcentaje / 100), 2)
    INTO v_nota
    FROM unexca.calificaciones c
    JOIN unexca.plan_evaluacion pe ON pe.id_plan = c.id_plan
    WHERE c.id_inscripcion_asig = p_id_inscripcion_asig;

    RETURN COALESCE(v_nota, 0.00);
END;
$$ LANGUAGE plpgsql;

-- Función: Verificar si el plan de evaluación suma 100% por carga
CREATE OR REPLACE FUNCTION unexca.fn_validar_plan_evaluacion(
    p_id_carga INTEGER
) RETURNS BOOLEAN AS $$
DECLARE
    v_total DECIMAL(5,2);
BEGIN
    SELECT SUM(porcentaje)
    INTO v_total
    FROM unexca.plan_evaluacion
    WHERE id_carga = p_id_carga;

    RETURN v_total = 100.00;
END;
$$ LANGUAGE plpgsql;

-- Función: Procesar resultado de examen de reparación
CREATE OR REPLACE FUNCTION unexca.fn_procesar_reparacion(
    p_id_reparacion INTEGER
) RETURNS VOID AS $$
DECLARE
    v_nota_rep DECIMAL(4,2);
    v_nota_final DECIMAL(4,2);
BEGIN
    SELECT nota_reparacion INTO v_nota_rep
    FROM unexca.examenes_reparacion
    WHERE id_reparacion = p_id_reparacion;

    -- Si aprueba reparación, nota final = 10.00 (máximo obtenible por reparación)
    -- Si reprueba, mantiene nota original
    IF v_nota_rep >= 10.00 THEN
        v_nota_final := 10.00;
    ELSE
        SELECT nota_original INTO v_nota_final
        FROM unexca.examenes_reparacion
        WHERE id_reparacion = p_id_reparacion;
    END IF;

    UPDATE unexca.examenes_reparacion
    SET nota_final_post_reparacion = v_nota_final,
        actualizado_en = CURRENT_TIMESTAMP
    WHERE id_reparacion = p_id_reparacion;
END;
$$ LANGUAGE plpgsql;
