<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Detalle de Persona</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Información completa de <?= htmlspecialchars($persona['nombres'] . ' ' . $persona['apellidos']) ?></p>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= url('admin/personas/editar/' . $persona['id_persona']) ?>"
           class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all">
            ✏️ Editar
        </a>
        <a href="<?= url('admin/personas') ?>"
           class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition">
            ← Volver
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Datos Personales -->
    <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
        <h2 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-5 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
            Datos Personales
        </h2>

        <div class="grid grid-cols-2 gap-x-8 gap-y-5">
            <div>
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-500 uppercase tracking-wider mb-1">Cédula de Identidad</p>
                <p class="text-sm text-blue-400 font-bold"><?= htmlspecialchars($persona['cedula_identidad']) ?></p>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-500 uppercase tracking-wider mb-1">Estatus</p>
                <?php if ($persona['nombre_estatus'] === 'Activo'): ?>
                    <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-500/10 text-emerald-400">
                        <?= htmlspecialchars($persona['nombre_estatus']) ?>
                    </span>
                <?php else: ?>
                    <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-amber-500/10 text-amber-400">
                        <?= htmlspecialchars($persona['nombre_estatus']) ?>
                    </span>
                <?php endif; ?>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-500 uppercase tracking-wider mb-1">Nombres</p>
                <p class="text-sm text-slate-800 dark:text-slate-200"><?= htmlspecialchars($persona['nombres']) ?></p>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-500 uppercase tracking-wider mb-1">Apellidos</p>
                <p class="text-sm text-slate-800 dark:text-slate-200"><?= htmlspecialchars($persona['apellidos']) ?></p>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-500 uppercase tracking-wider mb-1">Género</p>
                <p class="text-sm text-slate-700 dark:text-slate-300"><?= htmlspecialchars($persona['genero']) ?></p>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-500 uppercase tracking-wider mb-1">Fecha de Nacimiento</p>
                <p class="text-sm text-slate-700 dark:text-slate-300">
                    <?= $persona['fecha_nacimiento'] ? date('d/m/Y', strtotime($persona['fecha_nacimiento'])) : '—' ?>
                </p>
            </div>
        </div>

        <!-- Contacto -->
        <h2 class="text-base font-bold text-slate-800 dark:text-slate-200 mt-8 mb-5 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-purple-500"></span>
            Información de Contacto
        </h2>

        <div class="grid grid-cols-2 gap-x-8 gap-y-5">
            <div>
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-500 uppercase tracking-wider mb-1">Correo Personal</p>
                <p class="text-sm text-slate-800 dark:text-slate-200"><?= htmlspecialchars($persona['correo_personal']) ?></p>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-500 uppercase tracking-wider mb-1">Teléfono Personal</p>
                <p class="text-sm text-slate-700 dark:text-slate-300"><?= htmlspecialchars($persona['telefono_personal'] ?? '—') ?></p>
            </div>
            <div class="col-span-2">
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-500 uppercase tracking-wider mb-1">Dirección de Habitación</p>
                <p class="text-sm text-slate-700 dark:text-slate-300"><?= htmlspecialchars($persona['direccion_habitacion'] ?? '—') ?></p>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-500 uppercase tracking-wider mb-1">Fecha de Ingreso</p>
                <p class="text-sm text-slate-700 dark:text-slate-300">
                    <?= $persona['fecha_ingreso'] ? date('d/m/Y', strtotime($persona['fecha_ingreso'])) : '—' ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Panel Lateral — Usuario -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 h-fit">
        <h2 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-5 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            Cuenta de Usuario
        </h2>

        <?php if ($usuario): ?>
            <div class="space-y-4">
                <div>
                    <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-500 uppercase tracking-wider mb-1">Cédula de acceso</p>
                    <p class="text-sm text-slate-800 dark:text-slate-200"><?= htmlspecialchars($usuario['cedula']) ?></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-500 uppercase tracking-wider mb-1">Correo Institucional</p>
                    <p class="text-sm text-slate-800 dark:text-slate-200"><?= htmlspecialchars($usuario['correo_institucional']) ?></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-500 uppercase tracking-wider mb-1">Rol</p>
                    <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-purple-500/10 text-purple-400">
                        <?= htmlspecialchars($usuario['nombre_tipo']) ?>
                    </span>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-500 uppercase tracking-wider mb-1">Estatus del Usuario</p>
                    <?php if ($usuario['estatus_usuario'] === 'Activo'): ?>
                        <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-500/10 text-emerald-400">
                            <?= htmlspecialchars($usuario['estatus_usuario']) ?>
                        </span>
                    <?php else: ?>
                        <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-red-500/10 text-red-400">
                            <?= htmlspecialchars($usuario['estatus_usuario']) ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-6">
                <div class="text-4xl mb-3">👤</div>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">Sin cuenta de usuario</p>
                <p class="text-xs text-slate-600">Esta persona aún no tiene un usuario asignado en el sistema.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
