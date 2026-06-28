<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <div class="flex items-center gap-3">
            <a href="<?= url('admin/cargas') ?>" class="text-slate-400 hover:text-blue-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Creador de Clases (Cohortes)</h1>
        </div>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1 ml-9">Configura una sección completa con sus docentes, materias y estudiantes de una sola vez.</p>
    </div>
</div>

<form id="formCreadorClase" action="<?= url('admin/cargas/clase/guardar') ?>" method="POST" class="space-y-6">
    
    <!-- Bloque 1: Configuración General -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
        <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4 border-b border-slate-200 dark:border-slate-800 pb-2">1. Configuración General</h2>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Período Académico</label>
                <select name="id_periodo" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:text-slate-100">
                    <option value="">Seleccione...</option>
                    <?php foreach($periodos as $p): ?>
                        <option value="<?= $p['id_periodo'] ?>"><?= htmlspecialchars($p['periodo']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Sede</label>
                <select name="id_sede" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:text-slate-100">
                    <option value="">Seleccione...</option>
                    <?php foreach($sedes as $s): ?>
                        <option value="<?= $s['id_sede'] ?>"><?= htmlspecialchars($s['nombre_sede']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Sección</label>
                <select name="id_seccion" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:text-slate-100">
                    <option value="">Seleccione...</option>
                    <?php foreach($secciones as $sec): ?>
                        <option value="<?= $sec['id_seccion'] ?>"><?= htmlspecialchars($sec['cod_seccion']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Turno</label>
                <select name="id_turno" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:text-slate-100">
                    <option value="">Seleccione...</option>
                    <?php foreach($turnos as $t): ?>
                        <option value="<?= $t['id_turno'] ?>"><?= htmlspecialchars($t['turno']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">PNF (Filtro Global)</label>
                <select id="filtroPnf" required class="w-full bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-blue-700 dark:text-blue-300 font-medium">
                    <option value="">Filtrar Asignaturas/Alumnos...</option>
                    <?php foreach($pnfs as $pnf): ?>
                        <option value="<?= $pnf['id_pnf'] ?>"><?= htmlspecialchars($pnf['nombre_pnf']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- Bloque 2: Materias y Docentes -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4 border-b border-slate-200 dark:border-slate-800 pb-2">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">2. Asignaturas y Docentes</h2>
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Total UC: <strong id="totalUC" class="text-blue-600">0</strong> / 18</span>
                <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Materias: <strong id="totalMaterias">1</strong> / 6</span>
            </div>
        </div>
        
        <div id="contenedorMaterias" class="space-y-4">
            <!-- Fila 1 -->
            <div class="materia-row grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-slate-50 dark:bg-slate-800/30 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
                <div class="md:col-span-11">
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Horario (Asignatura y Docente)</label>
                    <div class="flex gap-4">
                        <select class="select-horario w-1/2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:text-slate-100" onchange="seleccionarHorario(this)">
                            <option value="">Seleccione bloque de horario...</option>
                            <?php foreach($horarios as $h): ?>
                                <option value="<?= $h['id_horario'] ?>" 
                                        data-idasig="<?= $h['id_asignatura'] ?>" 
                                        data-iddoc="<?= $h['id_docente'] ?>" 
                                        data-uc="<?= $h['unidades_credito'] ?>" 
                                        data-pnf="<?= $h['id_pnf'] ?>"
                                        data-desc="<?= htmlspecialchars($h['asignatura_nombre'] . ' — Prof. ' . $h['nombres'] . ' ' . $h['apellidos']) ?>">
                                    <?= htmlspecialchars($h['dia_semana'] . ' ' . date('H:i', strtotime($h['hora_inicio'])) . ' a ' . date('H:i', strtotime($h['hora_fin'])) . ' | ' . $h['asignatura_nombre'] . ' (Prof. ' . $h['apellidos'] . ')') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="w-1/2 flex gap-2">
                            <input type="text" readonly placeholder="Asignatura y Docente autocompletados" class="desc-horario flex-1 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-sm text-slate-500 dark:text-slate-400 cursor-not-allowed">
                            <input type="hidden" name="materias[0][id_asignatura]" class="hidden-asignatura" required>
                            <input type="hidden" name="materias[0][id_docente]" class="hidden-docente" required>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-1 flex justify-center">
                    <button type="button" class="btn-quitar-materia text-slate-400 hover:text-red-500 transition-colors p-2 hidden" title="Quitar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <button type="button" id="btnAgregarMateria" class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Añadir otra asignatura
            </button>
        </div>
    </div>

    <!-- Bloque 3: Estudiantes -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4 border-b border-slate-200 dark:border-slate-800 pb-2">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">3. Estudiantes Inscritos</h2>
            <div id="contadorEstudiantesContainer" class="flex items-center gap-4 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 px-3 py-1 rounded-full">
                <span class="text-sm font-medium">Seleccionados: <strong id="contadorEstudiantes">0</strong> / 40</span>
            </div>
        </div>
        
        <div class="mb-4">
            <input type="text" id="buscadorEstudiantes" placeholder="Buscar estudiante por cédula o nombre..." class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors dark:text-slate-100">
        </div>

        <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden h-64 overflow-y-auto">
            <table class="w-full" id="tablaEstudiantes">
                <thead class="sticky top-0 bg-slate-100 dark:bg-slate-800 z-10">
                    <tr>
                        <th class="w-12 px-4 py-2 text-center">
                            <input type="checkbox" id="checkTodos" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Cédula</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Nombres y Apellidos</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800" id="listaEstudiantes">
                    <?php foreach($estudiantes as $est): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 cursor-pointer estudiante-row" data-pnf="<?= $est['id_pnf'] ?>" onclick="toggleCheckbox(this)">
                            <td class="w-12 px-4 py-2 text-center" onclick="event.stopPropagation()">
                                <input type="checkbox" name="estudiantes[]" value="<?= $est['id_estudiante'] ?>" class="check-estudiante rounded border-slate-300 text-blue-600 focus:ring-blue-500" onchange="actualizarContadorEstudiantes()">
                            </td>
                            <td class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 est-cedula"><?= htmlspecialchars($est['cedula']) ?></td>
                            <td class="px-4 py-2 text-sm text-slate-600 dark:text-slate-400 est-nombre"><?= htmlspecialchars($est['apellidos'] . ' ' . $est['nombres']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Botonera -->
    <div class="flex justify-end gap-4 border-t border-slate-200 dark:border-slate-800 pt-6">
        <a href="<?= url('admin/cargas') ?>" class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-6 py-3 font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition">
            Cancelar
        </a>
        <button type="submit" id="btnGuardarClase" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold rounded-xl px-8 py-3 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all">
            Crear Clase
        </button>
    </div>
</form>

<script>
    const MAX_MATERIAS = 6;
    const MAX_UC = 18;
    const MAX_ESTUDIANTES = 40;

    const contenedorMaterias = document.getElementById('contenedorMaterias');
    const btnAgregarMateria = document.getElementById('btnAgregarMateria');
    let baseMateriaRow = contenedorMaterias.querySelector('.materia-row').cloneNode(true);
    baseMateriaRow.querySelector('.btn-quitar-materia').classList.remove('hidden');

    function seleccionarHorario(selectElement) {
        const option = selectElement.options[selectElement.selectedIndex];
        const row = selectElement.closest('.materia-row');
        
        const hiddenAsig = row.querySelector('.hidden-asignatura');
        const hiddenDoc = row.querySelector('.hidden-docente');
        const descInput = row.querySelector('.desc-horario');

        if (option.value) {
            hiddenAsig.value = option.getAttribute('data-idasig');
            hiddenDoc.value = option.getAttribute('data-iddoc');
            descInput.value = option.getAttribute('data-desc');
        } else {
            hiddenAsig.value = '';
            hiddenDoc.value = '';
            descInput.value = '';
        }
        recalcularTotales();
    }

    function recalcularTotales() {
        let totalUC = 0;
        let totalMaterias = 0;
        const selects = document.querySelectorAll('.select-horario');
        selects.forEach(select => {
            const option = select.options[select.selectedIndex];
            if (option && option.value !== "") {
                totalUC += parseInt(option.getAttribute('data-uc')) || 0;
                totalMaterias++;
            }
        });

        document.getElementById('totalUC').textContent = totalUC;
        document.getElementById('totalMaterias').textContent = totalMaterias;

        const elTotalUC = document.getElementById('totalUC');
        if (totalUC > MAX_UC) {
            elTotalUC.classList.remove('text-blue-600');
            elTotalUC.classList.add('text-red-500');
        } else {
            elTotalUC.classList.remove('text-red-500');
            elTotalUC.classList.add('text-blue-600');
        }

        btnAgregarMateria.style.display = totalMaterias >= MAX_MATERIAS ? 'none' : 'inline-flex';
    }

    contenedorMaterias.addEventListener('click', function(e) {
        const btnQuitar = e.target.closest('.btn-quitar-materia');
        if (btnQuitar) {
            btnQuitar.closest('.materia-row').remove();
            recalcularTotales();
        }
    });

    btnAgregarMateria.addEventListener('click', function() {
        const currentMaterias = document.querySelectorAll('.materia-row').length;
        if (currentMaterias < MAX_MATERIAS) {
            const newRow = baseMateriaRow.cloneNode(true);
            const index = currentMaterias;
            
            // Limpiar valores
            newRow.querySelector('.select-horario').value = '';
            newRow.querySelector('.desc-horario').value = '';
            newRow.querySelector('.hidden-asignatura').value = '';
            newRow.querySelector('.hidden-docente').value = '';
            
            // Actualizar los names
            newRow.querySelector('.hidden-asignatura').name = `materias[${index}][id_asignatura]`;
            newRow.querySelector('.hidden-docente').name = `materias[${index}][id_docente]`;
            
            contenedorMaterias.appendChild(newRow);
            recalcularTotales();
        }
    });

    // Lógica para actualizar contador estudiantes
    function actualizarContadorEstudiantes() {
        const checkeados = document.querySelectorAll('.check-estudiante:checked').length;
        const totalRows = document.querySelectorAll('.estudiante-row:not([style*="display: none"])').length;
        const checkAll = document.getElementById('checkTodos');
        
        document.getElementById('contadorEstudiantes').textContent = checkeados;
        
        if (checkeados > MAX_ESTUDIANTES) {
            document.getElementById('contadorEstudiantesContainer').classList.remove('bg-blue-100', 'text-blue-700', 'dark:bg-blue-900/30', 'dark:text-blue-400');
            document.getElementById('contadorEstudiantesContainer').classList.add('bg-red-100', 'text-red-700', 'dark:bg-red-900/30', 'dark:text-red-400');
        } else {
            document.getElementById('contadorEstudiantesContainer').classList.add('bg-blue-100', 'text-blue-700', 'dark:bg-blue-900/30', 'dark:text-blue-400');
            document.getElementById('contadorEstudiantesContainer').classList.remove('bg-red-100', 'text-red-700', 'dark:bg-red-900/30', 'dark:text-red-400');
        }

        checkAll.checked = checkeados > 0 && checkeados === totalRows;
    }

    // Lógica para marcar/desmarcar todos los visibles
    document.getElementById('checkTodos').addEventListener('change', function() {
        const isChecked = this.checked;
        const visibleRows = document.querySelectorAll('.estudiante-row:not([style*="display: none"])');
        visibleRows.forEach(row => {
            const checkbox = row.querySelector('.check-estudiante');
            checkbox.checked = isChecked;
        });
        actualizarContadorEstudiantes();
    });

    // Lógica de Filtrado por PNF (Global)
    const filtroPnf = document.getElementById('filtroPnf');
    filtroPnf.addEventListener('change', function() {
        const selectedPnf = this.value;

        // 1. Filtrar Estudiantes
        const estudiantesRows = document.querySelectorAll('.estudiante-row');
        estudiantesRows.forEach(row => {
            if (!selectedPnf || row.getAttribute('data-pnf') === selectedPnf) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
                row.querySelector('.check-estudiante').checked = false; // deseleccionar si se oculta
            }
        });

        // 2. Filtrar Horarios
        const horariosSelects = document.querySelectorAll('.select-horario');

        horariosSelects.forEach(select => {
            const oldValue = select.value;
            Array.from(select.options).forEach(opt => {
                if(opt.value === '') return; // la opción por defecto
                const pnfOpt = opt.getAttribute('data-pnf');
                if (!selectedPnf || pnfOpt === selectedPnf || !pnfOpt) { 
                    opt.style.display = '';
                } else {
                    opt.style.display = 'none';
                    if (oldValue === opt.value) select.value = ''; // Deseleccionar si está oculto
                }
            });
            seleccionarHorario(select); // forzar actualizacion de campos ocultos
        });

        actualizarContadorEstudiantes();
        recalcularTotales();
    });

    function toggleCheckbox(row) {
        const cb = row.querySelector('.check-estudiante');
        cb.checked = !cb.checked;
        actualizarContadorEstudiantes();
    }

    // Buscador de Estudiantes
    document.getElementById('buscadorEstudiantes').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tablaEstudiantes tbody tr');
        
        rows.forEach(row => {
            const cedula = row.querySelector('.est-cedula').textContent.toLowerCase();
            const nombre = row.querySelector('.est-nombre').textContent.toLowerCase();
            
            if (cedula.includes(filter) || nombre.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Validación Final antes de enviar
    document.getElementById('formCreadorClase').addEventListener('submit', function(e) {
        recalcularTotales();
        actualizarContadorEstudiantes();
        
        const totalUC = parseInt(document.getElementById('totalUC').textContent) || 0;
        const totalEst = parseInt(document.getElementById('totalEstudiantes').textContent) || 0;
        const totalMat = parseInt(document.getElementById('totalMaterias').textContent) || 0;

        if (totalMat === 0) {
            e.preventDefault();
            alert('Debes añadir al menos una asignatura.');
            return;
        }

        if (totalUC > MAX_UC) {
            e.preventDefault();
            alert(`Has superado el límite de ${MAX_UC} Unidades de Crédito.`);
            return;
        }

        if (totalEst > MAX_ESTUDIANTES) {
            e.preventDefault();
            alert(`Has superado el límite de ${MAX_ESTUDIANTES} estudiantes por sección.`);
            return;
        }
    });
</script>
