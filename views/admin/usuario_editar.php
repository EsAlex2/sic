<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Editar Usuario</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">
            Modificar cuenta de <?= htmlspecialchars($usuario['nombres'] . ' ' . $usuario['apellidos']) ?> 
            (Cédula: <?= htmlspecialchars($usuario['cedula_identidad'] ?? $usuario['cedula']) ?>)
        </p>
    </div>
    <a href="<?= url('admin/usuarios') ?>"
       class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition">
        ← Volver al listado
    </a>
</div>

<!-- Formulario de Edición -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 max-w-lg">
    <form action="<?= url('admin/usuarios/actualizar/' . $usuario['id_usuario']) ?>" method="POST">
        <div class="space-y-5">
            <!-- Correo Institucional -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Correo Institucional</label>
                <input type="email" name="correo_institucional" required
                       value="<?= htmlspecialchars($usuario['correo_institucional']) ?>"
                       class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
            </div>

            <!-- Rol -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Rol de Sistema</label>
                <select name="id_tipo" required
                        class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id_tipo'] ?>" <?= $usuario['id_tipo'] == $r['id_tipo'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($r['nombre_tipo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="text-[11px] text-slate-500 dark:text-slate-500 mt-1">Cambiar el rol no modifica los datos previos generados en tablas específicas (ej: datos_estudiantes).</p>
            </div>

            <!-- Contraseña -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Nueva Contraseña (Opcional)</label>
                <input type="text" name="password" 
                       class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition"
                       placeholder="Déjalo en blanco para mantener la actual">
            </div>

            <!-- Estatus -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Estatus de la Cuenta</label>
                <select name="id_estatus" required
                        class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    <?php foreach ($estatusList as $e): ?>
                        <option value="<?= $e['id_estatus'] ?>" <?= $usuario['id_estatus'] == $e['id_estatus'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($e['nombre_estatus']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 mt-8 pt-5 border-t border-slate-200 dark:border-slate-800">
            <a href="<?= url('admin/usuarios') ?>"
               class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition">
                Cancelar
            </a>
            <button type="submit"
                    class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-emerald-600/25 hover:shadow-emerald-600/40 hover:-translate-y-0.5 transition-all">
                💾 Actualizar Usuario
            </button>
        </div>
    </form>
</div>
