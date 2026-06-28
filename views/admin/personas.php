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

<!-- Controles y Estadísticas -->
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4 animate-fade">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 px-5 py-3 rounded-2xl shadow-sm inline-flex items-center gap-4">
        <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Registradas</p>
            <p class="text-xl font-bold text-slate-900 dark:text-slate-100"><?= count($personas) ?> Personas</p>
        </div>
    </div>
    
    <div class="relative w-full md:w-96">
        <input type="text" id="buscadorPersonas" onkeyup="filtrarPersonas()" placeholder="Buscar por cédula, nombre, contacto..." 
               class="w-full pl-11 pr-4 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 transition-all shadow-sm">
        <svg class="w-5 h-5 text-slate-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
    </div>
</div>

<!-- Tabla de Personas -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
    <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-x-auto">
        <table class="w-full min-w-[800px]" id="tablaPersonas">
            <thead>
                <tr>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[10px] uppercase tracking-wider px-3 py-3 text-left">Cédula</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[10px] uppercase tracking-wider px-3 py-3 text-left">Nombres</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[10px] uppercase tracking-wider px-3 py-3 text-left">Apellidos</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[10px] uppercase tracking-wider px-3 py-3 text-left">Género</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[10px] uppercase tracking-wider px-3 py-3 text-left">Contacto</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[10px] uppercase tracking-wider px-3 py-3 text-left">Estado</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[10px] uppercase tracking-wider px-3 py-3 text-left">¿Usuario?</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[10px] uppercase tracking-wider px-3 py-3 text-left">Registro</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[10px] uppercase tracking-wider px-3 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($personas)): ?>
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-sm text-center text-slate-500 dark:text-slate-500 border-t border-slate-200 dark:border-slate-800/50">
                            No hay personas registradas.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($personas as $p): ?>
                        <tr class="hover:bg-slate-50 dark:bg-slate-800/30 transition-colors">
                            <td class="px-3 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 font-bold text-blue-400">
                                <?= htmlspecialchars($p['cedula_identidad']) ?>
                            </td>
                            <td class="px-3 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 font-medium text-slate-900 dark:text-slate-100">
                                <?= htmlspecialchars($p['nombres']) ?>
                            </td>
                            <td class="px-3 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 font-medium text-slate-900 dark:text-slate-100">
                                <?= htmlspecialchars($p['apellidos']) ?>
                            </td>
                            <td class="px-3 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-slate-600 dark:text-slate-400">
                                <?= htmlspecialchars($p['genero']) ?>
                            </td>
                            <td class="px-3 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50">
                                <div class="text-slate-800 dark:text-slate-200 text-xs"><?= htmlspecialchars($p['correo_personal']) ?></div>
                                <div class="text-slate-500 dark:text-slate-500 text-[11px]"><?= htmlspecialchars($p['telefono_personal'] ?? '') ?></div>
                            </td>
                            <td class="px-3 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50">
                                <?php if ($p['nombre_estatus'] === 'Activo'): ?>
                                    <span class="inline-flex px-2 py-0.5 text-[11px] font-semibold rounded-full bg-emerald-500/10 text-emerald-400">
                                        <?= htmlspecialchars($p['nombre_estatus']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex px-2 py-0.5 text-[11px] font-semibold rounded-full bg-amber-500/10 text-amber-400">
                                        <?= htmlspecialchars($p['nombre_estatus']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50">
                                <?php if ($p['tiene_usuario'] > 0): ?>
                                    <span class="inline-flex px-2 py-0.5 text-[11px] font-semibold rounded-full bg-emerald-500/10 text-emerald-400">Sí</span>
                                <?php else: ?>
                                    <span class="inline-flex px-2 py-0.5 text-[11px] font-semibold rounded-full bg-red-500/10 text-red-400">No</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50">
                                <div class="text-[9px] text-slate-500 leading-tight whitespace-nowrap">
                                    <span class="block">C: <?= $p['creado_en'] ? date('d/m/y H:i', strtotime($p['creado_en'])) : '-' ?></span>
                                    <span class="block text-slate-400">A: <?= $p['actualizado_en'] ? date('d/m/y H:i', strtotime($p['actualizado_en'])) : '-' ?></span>
                                </div>
                            </td>
                            <td class="px-3 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Ver -->
                                    <a href="<?= url('admin/personas/ver/' . $p['id_persona']) ?>"
                                       class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-3 py-1.5 text-xs hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition"
                                       title="Ver detalle">
                                        👁
                                    </a>
                                    <!-- Editar -->
                                    <a href="<?= url('admin/personas/editar/' . $p['id_persona']) ?>"
                                       class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-3 py-1.5 text-xs hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition"
                                       title="Editar persona">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <?php if ($p['nombre_estatus'] === 'Activo'): ?>
                                        <!-- Suspender (soft delete) -->
                                        <form action="<?= url('admin/personas/eliminar/' . $p['id_persona']) ?>" method="POST"
                                              onsubmit="return confirm('¿Suspender a <?= htmlspecialchars($p['nombres'] . ' ' . $p['apellidos']) ?>? Esta acción cambiará su estatus a Inactivo.');">
                                            <button type="submit"
                                                    class="bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg px-2.5 py-1.5 text-xs hover:shadow-red-600/25 transition inline-flex items-center gap-1" title="Suspender">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <!-- Activar -->
                                        <form action="<?= url('admin/personas/activar/' . $p['id_persona']) ?>" method="POST"
                                              onsubmit="return confirm('¿Activar a <?= htmlspecialchars($p['nombres'] . ' ' . $p['apellidos']) ?>? Esta acción cambiará su estatus a Activo.');">
                                            <button type="submit"
                                                    class="border border-emerald-500/30 text-emerald-400 rounded-xl px-3 py-1.5 text-xs hover:bg-emerald-500/10 hover:text-emerald-300 transition"
                                                    title="Activar persona">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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

<script>
    function filtrarPersonas() {
        let input = document.getElementById('buscadorPersonas');
        let filter = input.value.toLowerCase();
        let table = document.getElementById('tablaPersonas');
        let tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            if (tr[i].parentNode.tagName.toLowerCase() === 'thead') continue;
            
            let tds = tr[i].getElementsByTagName('td');
            if (tds.length === 1 && tds[0].colSpan > 1) {
                continue;
            }
            
            if (tds.length > 0) {
                let cedula = tds[0].textContent || tds[0].innerText;
                let nombres = tds[1].textContent || tds[1].innerText;
                let apellidos = tds[2].textContent || tds[2].innerText;
                let genero = tds[3].textContent || tds[3].innerText;
                let contacto = tds[4].textContent || tds[4].innerText;
                
                let combined = cedula + " " + nombres + " " + apellidos + " " + genero + " " + contacto;
                
                if (combined.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
