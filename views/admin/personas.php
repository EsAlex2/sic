<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Registro de Personas</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Gestiona la base de datos demográfica (paso previo a crear usuarios)</p>
    </div>
    <button onclick="document.getElementById('modalNuevaPersona').classList.remove('hidden'); document.getElementById('modalNuevaPersona').classList.add('flex');"
            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all">
        ➕ Nueva Persona
    </button>
</div>

<!-- Tabla de Personas -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
    <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Cédula</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Nombres</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Apellidos</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Género</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Contacto</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Estado</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">¿Usuario?</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($personas)): ?>
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-sm text-center text-slate-500 dark:text-slate-500 border-t border-slate-200 dark:border-slate-800/50">
                            No hay personas registradas.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($personas as $p): ?>
                        <tr class="hover:bg-slate-50 dark:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 font-bold text-blue-400">
                                <?= htmlspecialchars($p['cedula_identidad']) ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-slate-800 dark:text-slate-200">
                                <?= htmlspecialchars($p['nombres']) ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-slate-800 dark:text-slate-200">
                                <?= htmlspecialchars($p['apellidos']) ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-slate-700 dark:text-slate-300">
                                <?= htmlspecialchars($p['genero']) ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50">
                                <div class="text-slate-800 dark:text-slate-200 text-xs"><?= htmlspecialchars($p['correo_personal']) ?></div>
                                <div class="text-slate-500 dark:text-slate-500 text-xs"><?= htmlspecialchars($p['telefono_personal'] ?? '') ?></div>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50">
                                <?php if ($p['nombre_estatus'] === 'Activo'): ?>
                                    <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-500/10 text-emerald-400">
                                        <?= htmlspecialchars($p['nombre_estatus']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-amber-500/10 text-amber-400">
                                        <?= htmlspecialchars($p['nombre_estatus']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50">
                                <?php if ($p['tiene_usuario'] > 0): ?>
                                    <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-500/10 text-emerald-400">Sí</span>
                                <?php else: ?>
                                    <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-red-500/10 text-red-400">No</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Ver -->
                                    <a href="<?= url('admin/personas/ver/' . $p['id_persona']) ?>"
                                       class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-3 py-1.5 text-xs hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition"
                                       title="Ver detalle">
                                        👁 Ver
                                    </a>
                                    <!-- Editar -->
                                    <a href="<?= url('admin/personas/editar/' . $p['id_persona']) ?>"
                                       class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-3 py-1.5 text-xs hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition"
                                       title="Editar persona">
                                        ✏️ Editar
                                    </a>
                                    <?php if ($p['nombre_estatus'] === 'Activo'): ?>
                                        <!-- Eliminar (soft delete) -->
                                        <form action="<?= url('admin/personas/eliminar/' . $p['id_persona']) ?>" method="POST"
                                              onsubmit="return confirm('¿Desactivar a <?= htmlspecialchars($p['nombres'] . ' ' . $p['apellidos']) ?>? Esta acción cambiará su estatus a Inactivo.');">
                                            <button type="submit"
                                                    class="border border-red-500/30 text-red-400 rounded-xl px-3 py-1.5 text-xs hover:bg-red-500/10 hover:text-red-300 transition"
                                                    title="Desactivar persona">
                                                Suspender
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <!-- Activar -->
                                        <form action="<?= url('admin/personas/activar/' . $p['id_persona']) ?>" method="POST"
                                              onsubmit="return confirm('¿Activar a <?= htmlspecialchars($p['nombres'] . ' ' . $p['apellidos']) ?>? Esta acción cambiará su estatus a Activo.');">
                                            <button type="submit"
                                                    class="border border-emerald-500/30 text-emerald-400 rounded-xl px-3 py-1.5 text-xs hover:bg-emerald-500/10 hover:text-emerald-300 transition"
                                                    title="Activar persona">
                                                Activar
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nueva Persona -->
<div id="modalNuevaPersona" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Registrar Persona</h3>
            <button onclick="document.getElementById('modalNuevaPersona').classList.add('hidden'); document.getElementById('modalNuevaPersona').classList.remove('flex');"
                    class="text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:text-slate-300 text-2xl leading-none transition">&times;</button>
        </div>
        <form action="<?= url('admin/personas/crear') ?>" method="POST">
            <div class="px-6 py-5 space-y-4">
                <!-- Cédula + Género -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Cédula de Identidad</label>
                        <input type="number" name="cedula_identidad" required
                               class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Género</label>
                        <select name="genero" required
                                class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>

                <!-- Nombres + Apellidos -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Nombres</label>
                        <input type="text" name="nombres" required
                               class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Apellidos</label>
                        <input type="text" name="apellidos" required
                               class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    </div>
                </div>

                <!-- Fecha de Nacimiento -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" required
                           class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                </div>

                <!-- Correo + Teléfono -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Correo Personal</label>
                        <input type="email" name="correo_personal" required
                               class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Teléfono</label>
                        <input type="text" name="telefono_personal"
                               class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    </div>
                </div>

                <!-- Dirección -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Dirección de Habitación</label>
                    <textarea name="direccion_habitacion" rows="2"
                              class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition resize-none"></textarea>
                </div>
            </div>
            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                <button type="button"
                        onclick="document.getElementById('modalNuevaPersona').classList.add('hidden'); document.getElementById('modalNuevaPersona').classList.remove('flex');"
                        class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all">
                    Registrar Persona
                </button>
            </div>
        </form>
    </div>
</div>
