<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Editar Persona</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Modificar los datos de <?= htmlspecialchars($persona['nombres'] . ' ' . $persona['apellidos']) ?></p>
    </div>
    <a href="<?= url('admin/personas') ?>"
       class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition">
        ← Volver al listado
    </a>
</div>

<!-- Formulario de Edición -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 max-w-2xl">
    <form action="<?= url('admin/personas/actualizar/' . $persona['id_persona']) ?>" method="POST">
        <div class="space-y-5">
            <!-- Cédula (solo lectura) -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Cédula de Identidad</label>
                <input type="text" value="<?= htmlspecialchars($persona['cedula_identidad']) ?>" disabled
                       class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-500 dark:text-slate-500 cursor-not-allowed">
                <p class="text-[11px] text-slate-600 mt-1">La cédula no se puede modificar.</p>
            </div>

            <!-- Nombres + Apellidos -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Nombres</label>
                    <input type="text" name="nombres" required
                           value="<?= htmlspecialchars($persona['nombres']) ?>"
                           class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Apellidos</label>
                    <input type="text" name="apellidos" required
                           value="<?= htmlspecialchars($persona['apellidos']) ?>"
                           class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                </div>
            </div>

            <!-- Género + Fecha de Nacimiento -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Género</label>
                    <select name="genero" required
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                        <option value="Masculino" <?= $persona['genero'] === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                        <option value="Femenino" <?= $persona['genero'] === 'Femenino' ? 'selected' : '' ?>>Femenino</option>
                        <option value="Otro" <?= $persona['genero'] === 'Otro' ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" required
                           value="<?= htmlspecialchars($persona['fecha_nacimiento']) ?>"
                           class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                </div>
            </div>

            <!-- Correo + Teléfono -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Correo Personal</label>
                    <input type="email" name="correo_personal" required
                           value="<?= htmlspecialchars($persona['correo_personal']) ?>"
                           class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Teléfono</label>
                    <input type="text" name="telefono_personal"
                           value="<?= htmlspecialchars($persona['telefono_personal'] ?? '') ?>"
                           class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                </div>
            </div>

            <!-- Dirección -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Dirección de Habitación</label>
                <textarea name="direccion_habitacion" rows="3"
                          class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition resize-none"><?= htmlspecialchars($persona['direccion_habitacion'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 mt-8 pt-5 border-t border-slate-200 dark:border-slate-800">
            <a href="<?= url('admin/personas') ?>"
               class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition">
                Cancelar
            </a>
            <button type="submit"
                    class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-emerald-600/25 hover:shadow-emerald-600/40 hover:-translate-y-0.5 transition-all">
                💾 Guardar Cambios
            </button>
        </div>
    </form>
</div>
