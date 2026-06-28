<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Gestión de Estudiantes</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Administra los estudiantes y su información académica</p>
    </div>
    <button type="button" onclick="document.getElementById('modalNuevoEstudiante').classList.remove('hidden'); document.getElementById('modalNuevoEstudiante').classList.add('flex');"
            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Registrar Estudiante
    </button>
</div>

<!-- Dashboard Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Card Total -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center text-2xl">
            🎓
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Estudiantes</p>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100"><?= number_format($stats['total']) ?></h3>
        </div>
    </div>
    <!-- Card PNF -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col justify-center">
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Por Programa (PNF)</p>
        <div class="space-y-1">
            <?php foreach (array_slice($stats['por_pnf'], 0, 2) as $p): ?>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-700 dark:text-slate-300 truncate pr-2"><?= htmlspecialchars($p['nombre_pnf']) ?></span>
                    <span class="font-bold text-slate-900 dark:text-slate-100"><?= $p['total'] ?></span>
                </div>
            <?php endforeach; ?>
            <?php if (count($stats['por_pnf']) > 2): ?>
                <div class="text-xs text-blue-500 cursor-help" title="Y otros programas más...">+<?= count($stats['por_pnf']) - 2 ?> más</div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Card Sede -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col justify-center">
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Por Sede</p>
        <div class="space-y-1">
            <?php foreach (array_slice($stats['por_sede'], 0, 2) as $s): ?>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-700 dark:text-slate-300 truncate pr-2"><?= htmlspecialchars($s['nombre_sede']) ?></span>
                    <span class="font-bold text-slate-900 dark:text-slate-100"><?= $s['total'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm mb-8">
    <form method="GET" action="<?= url('admin/estudiantes') ?>" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1 w-full">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Buscar</label>
            <input type="text" name="buscar" value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>" placeholder="Cédula, Nombres..." class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 rounded-lg px-4 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500">
        </div>
        <div class="w-full md:w-48">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Programa (PNF)</label>
            <select name="pnf" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 rounded-lg px-4 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500">
                <option value="">Todos</option>
                <?php foreach ($pnfs as $p): ?>
                    <option value="<?= $p['id_pnf'] ?>" <?= (isset($_GET['pnf']) && $_GET['pnf'] == $p['id_pnf']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['nombre_pnf']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="w-full md:w-48">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Sede</label>
            <select name="sede" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 rounded-lg px-4 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500">
                <option value="">Todas</option>
                <?php foreach ($sedes as $s): ?>
                    <option value="<?= $s['id_sede'] ?>" <?= (isset($_GET['sede']) && $_GET['sede'] == $s['id_sede']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['nombre_sede']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white font-semibold rounded-lg px-4 py-2 text-sm hover:bg-blue-700 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Filtrar
            </button>
            <?php if (!empty($_GET['buscar']) || !empty($_GET['pnf']) || !empty($_GET['sede'])): ?>
                <a href="<?= url('admin/estudiantes') ?>" class="bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold rounded-lg px-4 py-2 text-sm hover:bg-slate-300 dark:hover:bg-slate-600 transition flex items-center">
                    Limpiar
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 uppercase font-semibold">
                <tr>
                    <th class="px-4 py-4">Cédula</th>
                    <th class="px-4 py-4">Nombre Completo</th>
                    <th class="px-4 py-4">PNF</th>
                    <th class="px-4 py-4">Trayecto</th>
                    <th class="px-4 py-4">Estatus</th>
                    <th class="px-4 py-4">Registro</th>
                    <th class="px-4 py-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                <?php if (empty($estudiantes)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                            No hay estudiantes registrados.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($estudiantes as $e): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-4 py-4 font-medium text-slate-900 dark:text-slate-100">
                                <?= htmlspecialchars($e['cedula_identidad']) ?>
                            </td>
                            <td class="px-4 py-4 font-medium text-slate-900 dark:text-slate-100">
                                <?= htmlspecialchars($e['nombres'] . ' ' . $e['apellidos']) ?>
                            </td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">
                                <?= htmlspecialchars($e['nombre_pnf']) ?>
                            </td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">
                                <?= htmlspecialchars($e['trayecto']) ?>
                            </td>
                            <td class="px-4 py-4">
                                <?php
                                $estatus_color = 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300';
                                if (($e['nombre_estatus'] ?? '') === 'Cursando') $estatus_color = 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400';
                                if (($e['nombre_estatus'] ?? '') === 'Graduado' || ($e['nombre_estatus'] ?? '') === 'Egresado') $estatus_color = 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400';
                                if (($e['nombre_estatus'] ?? '') === 'Retirado') $estatus_color = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
                                ?>
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full <?= $estatus_color ?>">
                                    <?= htmlspecialchars($e['nombre_estatus'] ?? 'Cursando') ?>
                                </span>
                            </td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">
                                <div class="text-[9px] leading-tight whitespace-nowrap">
                                    <span class="block">C: <?= $e['creado_en'] ? date('d/m/y H:i', strtotime($e['creado_en'])) : '-' ?></span>
                                    <span class="block text-slate-400">A: <?= $e['actualizado_en'] ? date('d/m/y H:i', strtotime($e['actualizado_en'])) : '-' ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= url('admin/estudiantes/ver/' . $e['id_estudiante']) ?>"
                                       class="border border-blue-300 dark:border-blue-700/50 text-blue-600 dark:text-blue-400 rounded-lg px-2.5 py-1.5 text-xs hover:bg-blue-50 dark:hover:bg-blue-900/30 transition inline-flex items-center gap-1" title="Ver Detalles">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <button type="button" onclick="abrirModalEditar(<?= $e['id_estudiante'] ?>, '<?= $e['id_pnf'] ?? '' ?>', '<?= $e['id_trayecto'] ?? '' ?>', '<?= $e['id_sede'] ?? '' ?>', '<?= $e['fecha_ingreso'] ?>', '<?= $e['id_estatus'] ?? '' ?>')"
                                            class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-lg px-2.5 py-1.5 text-xs hover:bg-slate-50 dark:hover:bg-slate-800 transition inline-flex items-center gap-1" title="Editar">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ═══════════ MODAL: Nuevo Estudiante ═══════════ -->
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center" id="modalNuevoEstudiante">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-lg shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="sticky top-0 bg-white dark:bg-slate-900 z-10 flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Registrar Estudiante</h3>
            <button type="button" onclick="document.getElementById('modalNuevoEstudiante').classList.add('hidden'); document.getElementById('modalNuevoEstudiante').classList.remove('flex');" class="text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:text-slate-300 transition text-xl leading-none">&times;</button>
        </div>
        <form action="<?= url('admin/estudiantes/crear') ?>" method="POST" id="formNuevoEstudiante">
            <div class="p-6 space-y-6">
                <!-- Step 1: Search Person -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Buscar Persona (Cédula)</label>
                    <div class="flex gap-2">
                        <input type="text" id="cedula_busqueda"
                               class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition"
                               placeholder="Ej: 12345678">
                        <button type="button" onclick="buscarPersona()"
                                class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all whitespace-nowrap">
                            🔍 Buscar
                        </button>
                    </div>
                    <div id="busqueda_resultado" class="mt-2 text-sm"></div>
                </div>

                <!-- Step 2: Academic Data (hidden initially) -->
                <div id="paso2_datos" class="hidden pt-4 border-t border-slate-200 dark:border-slate-800 space-y-4">
                    <input type="hidden" id="id_persona_oculto" name="id_persona">

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Persona Encontrada</label>
                        <input type="text" id="nombre_encontrado"
                               class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-700 dark:text-slate-300 cursor-not-allowed" disabled>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Sede</label>
                        <select name="id_sede" required
                                class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                            <option value="">Seleccione Sede...</option>
                            <?php foreach ($sedes as $s): ?>
                                <option value="<?= $s['id_sede'] ?>"><?= htmlspecialchars($s['nombre_sede']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Programa Nacional de Formación (PNF)</label>
                        <select name="id_pnf" required
                                class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                            <option value="">Seleccione PNF...</option>
                            <?php foreach ($pnfs as $p): ?>
                                <option value="<?= $p['id_pnf'] ?>"><?= htmlspecialchars($p['nombre_pnf']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Trayecto</label>
                        <select name="id_trayecto" required
                                class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                            <option value="">Seleccione Trayecto...</option>
                            <?php foreach ($trayectos as $t): ?>
                                <option value="<?= $t['id_trayecto'] ?>"><?= htmlspecialchars($t['descripcion']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Fecha de Ingreso</label>
                        <input type="date" name="fecha_ingreso" required value="<?= date('Y-m-d') ?>"
                               class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <div class="sticky bottom-0 bg-white dark:bg-slate-900 z-10 flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modalNuevoEstudiante').classList.add('hidden'); document.getElementById('modalNuevoEstudiante').classList.remove('flex');"
                        class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition">
                    Cancelar
                </button>
                <button type="submit" id="btn_guardar"
                        class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-emerald-600/25 hover:shadow-emerald-600/40 hover:-translate-y-0.5 transition-all hidden">
                    Registrar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ═══════════ MODAL: Editar Estudiante ═══════════ -->
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center" id="modalEditarEstudiante">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-md shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Editar Estudiante</h3>
            <button type="button" onclick="document.getElementById('modalEditarEstudiante').classList.add('hidden'); document.getElementById('modalEditarEstudiante').classList.remove('flex');" class="text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:text-slate-300 transition text-xl leading-none">&times;</button>
        </div>
        <form method="POST" id="formEditarEstudiante">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Sede</label>
                    <select name="id_sede" id="edit_id_sede" required
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                        <?php foreach ($sedes as $s): ?>
                            <option value="<?= $s['id_sede'] ?>"><?= htmlspecialchars($s['nombre_sede']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">PNF</label>
                    <select name="id_pnf" id="edit_id_pnf" required
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                        <?php foreach ($pnfs as $p): ?>
                            <option value="<?= $p['id_pnf'] ?>"><?= htmlspecialchars($p['nombre_pnf']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Trayecto</label>
                    <select name="id_trayecto" id="edit_id_trayecto" required
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                        <?php foreach ($trayectos as $t): ?>
                            <option value="<?= $t['id_trayecto'] ?>"><?= htmlspecialchars($t['descripcion']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Fecha de Ingreso</label>
                    <input type="date" name="fecha_ingreso" id="edit_fecha_ingreso" required
                           class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Estatus Académico</label>
                    <select name="id_estatus" id="edit_id_estatus" required
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                        <?php foreach ($estatus_list as $est): ?>
                            <option value="<?= $est['id_estatus'] ?>"><?= htmlspecialchars($est['nombre_estatus']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modalEditarEstudiante').classList.add('hidden'); document.getElementById('modalEditarEstudiante').classList.remove('flex');"
                        class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-emerald-600/25 hover:-translate-y-0.5 transition-all">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ── Search person by cédula (2-step user creation) ──
async function buscarPersona() {
    const cedula = document.getElementById('cedula_busqueda').value;
    const divRes = document.getElementById('busqueda_resultado');
    const paso2 = document.getElementById('paso2_datos');
    const btnGuardar = document.getElementById('btn_guardar');

    if (!cedula) {
        divRes.innerHTML = '<span class="text-red-400">Ingrese una cédula</span>';
        return;
    }

    divRes.innerHTML = '<span class="text-blue-400">Buscando...</span>';
    paso2.classList.add('hidden');
    btnGuardar.classList.add('hidden');

    try {
        const response = await fetch(`<?= url('api/personas/buscar/') ?>${cedula}?context=estudiante`);
        const result = await response.json();

        if (result.success) {
            divRes.innerHTML = '<span class="text-emerald-400">✓ Persona encontrada</span>';
            document.getElementById('id_persona_oculto').value = result.data.id_persona;
            document.getElementById('nombre_encontrado').value = result.data.nombre_completo;

            paso2.classList.remove('hidden');
            btnGuardar.classList.remove('hidden');
        } else {
            divRes.innerHTML = `<span class="text-red-400">✗ ${result.message}</span>`;
        }
    } catch (error) {
        divRes.innerHTML = '<span class="text-red-400">✗ Error de conexión</span>';
    }
}

function abrirModalEditar(id, id_pnf, id_trayecto, id_sede, fecha, estatus) {
    document.getElementById('formEditarEstudiante').action = '<?= url("admin/estudiantes/actualizar/") ?>' + id;
    
    // Set selects
    if(id_pnf) document.getElementById('edit_id_pnf').value = id_pnf;
    if(id_trayecto) document.getElementById('edit_id_trayecto').value = id_trayecto;
    if(id_sede) document.getElementById('edit_id_sede').value = id_sede;
    if(fecha) document.getElementById('edit_fecha_ingreso').value = fecha;
    if(estatus) document.getElementById('edit_id_estatus').value = estatus;
    
    document.getElementById('modalEditarEstudiante').classList.remove('hidden');
    document.getElementById('modalEditarEstudiante').classList.add('flex');
}
</script>
