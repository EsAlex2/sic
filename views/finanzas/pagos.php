<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Gestión de Pagos</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Verifica y procesa los pagos de los estudiantes</p>
    </div>
    <button onclick="openModal('modalNuevoPago')"
            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Registrar Pago
    </button>
</div>

<!-- Table -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
    <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Referencia</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Persona</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Cédula</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Arancel</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Monto (Bs.)</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Fecha</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Estatus</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pagos)): ?>
                    <tr><td colspan="8" class="px-4 py-8 text-sm text-center text-slate-500 dark:text-slate-500 border-t border-slate-200 dark:border-slate-800/50">No hay pagos registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($pagos as $p): ?>
                        <tr class="hover:bg-slate-50 dark:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 font-bold text-blue-400 font-mono">
                                <?= htmlspecialchars($p['referencia_bancaria']) ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-slate-800 dark:text-slate-200">
                                <?= htmlspecialchars(trim($p['nombres'] . ' ' . $p['apellidos'])) ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-slate-600 dark:text-slate-400">
                                <?= htmlspecialchars($p['cedula_identidad']) ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-slate-700 dark:text-slate-300">
                                <?= htmlspecialchars($p['arancel']) ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 font-bold text-slate-900 dark:text-slate-100">
                                Bs. <?= number_format($p['monto'], 2, ',', '.') ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-slate-600 dark:text-slate-400">
                                <?= date('d/m/Y', strtotime($p['fecha_pago'])) ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50">
                                <?php
                                $color = match($p['nombre_estatus']) {
                                    'Activo'   => 'bg-emerald-500/10 text-emerald-400',
                                    'Inactivo' => 'bg-amber-500/10 text-amber-400',
                                    default    => 'bg-blue-500/10 text-blue-400'
                                };
                                ?>
                                <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full <?= $color ?>">
                                    <?= htmlspecialchars($p['nombre_estatus']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-center">
                                <button class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-lg px-3 py-1.5 text-xs hover:bg-slate-50 dark:bg-slate-800 transition">
                                    Ver
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nuevo Pago -->
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center" id="modalNuevoPago" data-modal-overlay>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-lg shadow-2xl" data-modal-card>
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Registrar Pago</h3>
            <button onclick="closeModal('modalNuevoPago')" class="text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:text-slate-300 transition text-xl leading-none">&times;</button>
        </div>
        <form action="<?= url('finanzas/pagos/registrar') ?>" method="POST">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Cédula de la Persona</label>
                    <input type="number" name="cedula_persona" required
                           class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition"
                           placeholder="Ej: 12345678">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Arancel</label>
                    <select name="id_arancel" required
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                        <option value="">Seleccione arancel...</option>
                        <?php foreach ($aranceles as $a): ?>
                            <option value="<?= $a['id_arancel'] ?>">
                                <?= htmlspecialchars($a['descripcion']) ?> - Bs. <?= number_format($a['monto'], 2, ',', '.') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Referencia Bancaria</label>
                    <input type="text" name="referencia_bancaria" required
                           class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition font-mono"
                           placeholder="Número de referencia">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Fecha de Pago</label>
                    <input type="date" name="fecha_pago" required
                           class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="closeModal('modalNuevoPago')"
                        class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all">
                    Registrar Pago
                </button>
            </div>
        </form>
    </div>
</div>
