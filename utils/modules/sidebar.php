<!-- Main Sidebar Container -->
<aside style="backdrop-filter:blur(85px);-webkit-backdrop-filter:blur(85px);white-space:nowrap;overflow-x:hidden" class="main-sidebar sidebar-light-lightblue elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <img src="assets/img/logo_menu.png" alt="logo" class="brand-image" />
        <span style="font-family: 'Source Sans Pro';" class="brand-text font-weight-bold"><?php echo isset($_SESSION["empresa"]) ? $_SESSION["empresa"] : ''; ?></span><br>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul id="nav" class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="true">
                <?php
                // 1. Verificación de Sesión Segura
                if (session_status() === PHP_SESSION_NONE) session_start();

                // 2. Función Renderizadora (Protegida contra re-declaración)
                if (!function_exists('renderSidebarMenu')) {
                    function renderSidebarMenu($items, $level = 1)
                    {
                        // Si no hay items, salir
                        if (empty($items)) return;

                        // Sub-nivel contenedor (UL)
                        if ($level > 1) echo '<ul class="nav nav-treeview">';

                        foreach ($items as $item) {
                            // Validación básica
                            if (!isset($item->modulo)) continue;

                            $hasChildren = !empty($item->children);

                            // Determinar Icono según nivel
                            $iconClass = 'nav-icon fas ' . ($item->icon ?? 'fa-circle');
                            if ($level === 2 && empty($item->icon)) $iconClass = 'nav-icon far fa-circle';
                            if ($level >= 3 && empty($item->icon)) $iconClass = 'nav-icon far fa-dot-circle';

                            // --- RENDER ITEM (LI) ---
                            echo '<li class="nav-item ' . ($hasChildren ? 'has-treeview' : '') . '">';

                            // Enlace (A)
                            $href = '#';
                            $onclick = '';
                            $vista = $item->vista ?? '';

                            // Si tiene vista y NO tiene hijos, es clickeable para cargar contenido
                            if (!$hasChildren && !empty($vista)) {
                                // Escapamos datos para JS
                                $vistaSafe = htmlspecialchars($vista, ENT_QUOTES);
                                $moduloSafe = addslashes($item->modulo);
                                $onclick = "onclick=\"cargarContenido('content-wrapper', 'views/$vistaSafe', '$moduloSafe');\"";
                            }

                            echo '<a href="' . $href . '" class="nav-link" ' . $onclick . '>';
                            echo '<i class="' . $iconClass . '"></i>';
                            echo '<p>';
                            echo htmlspecialchars($item->modulo);
                            if ($hasChildren) {
                                echo '<i class="right fas fa-angle-left"></i>';
                            }
                            echo '</p>';
                            echo '</a>';

                            // Recursividad para hijos
                            if ($hasChildren) {
                                renderSidebarMenu($item->children, $level + 1);
                            }

                            echo '</li>';
                        }

                        if ($level > 1) echo '</ul>';
                    }
                }

                // 3. Ejecutar Renderizado
                if (!empty($_SESSION['menuTree'])) {
                    renderSidebarMenu($_SESSION['menuTree']);
                }
                ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<!-- /.Main Sidebar Container -->

<script>
    $(document).ready(function() {
        // Lógica de Activación de Menú (Active State)
        $('#nav').on('click', 'a.nav-link', function(e) {
            let $link = $(this);
            let $li = $link.parent('li');
            let hasChildren = $li.hasClass('has-treeview');

            // Si es un padre (carpeta), AdminLTE maneja el slideDown.
            // Solo queremos manejar la clase 'active' si es un enlace final (hoja).
            if (!hasChildren) {
                // 1. Limpiar todos los activos previos
                $('ul.nav-sidebar .nav-link').removeClass('active');
                
                // 2. Activar el link actual
                $link.addClass('active');

                // 3. Activar recursivamente a los padres (abuelos) para que queden azules/abiertos
                $link.parents('.has-treeview').each(function() {
                    $(this).children('a.nav-link').addClass('active');
                    $(this).addClass('menu-open'); // Forzar apertura en AdminLTE
                });
            }
        });
    });
</script>