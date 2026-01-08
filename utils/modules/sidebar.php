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
                            $id = $item->modulo;
                            $vista = $item->vista ?? '';

                            // Si tiene vista y NO tiene hijos, añadimos data-attributes en lugar de onclick
                            $dataAttrs = '';
                            if (!$hasChildren && !empty($vista)) {
                                $vistaSafe = htmlspecialchars($vista, ENT_QUOTES);
                                $moduloSafe = htmlspecialchars($item->modulo ?? '', ENT_QUOTES);
                                $dataAttrs = ' data-vista="' . $vistaSafe . '" data-modulo="' . $moduloSafe . '"';
                            }

                            echo '<a id="' . $id . '" href="#" class="nav-link"' . $dataAttrs . '>';
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

<!-- <script>
    $(document).ready(function() {
        // Lógica de Activación de Menú (Active State)
        $('#nav').on('click', 'a.nav-link', function(e) {
            let $link = $(this);
            let $li = $link.parent('li');
            let hasChildren = $li.hasClass('has-treeview');
            if (!hasChildren) {
                // 1. Limpiar todos los activos previos
                $('ul.nav-sidebar .nav-link').removeClass('active');
                // 2. Activar el link actual
                $link.addClass('active');
                $link.parents('.has-treeview').each(function() {
                    $(this).children('a.nav-link').addClass('active');
                    // $(this).addClass('menu-open'); // Forzar apertura en AdminLTE
                });
            }
        });
    });
</script> -->


<script>
    (function($) {
        // Estado inicial: sólo primer top-level activo
        $(function() {
            $('ul.nav-sidebar .nav-link').removeClass('active');
            const $firstTop = $('#nav > li.nav-item:first > a.nav-link');
            if ($firstTop.length) {
                $firstTop.addClass('active');
            }

            // Handler delegado: único punto de entrada para clicks en links del menú
            $('#nav').on('click', 'a.nav-link', function(e) {
                const $link = $(this);
                const vista = $link.data('vista');
                const modulo = $link.data('modulo');
                // Si ya está activo, no hacemos nada
                if ($link.hasClass('active')) {
                    e.preventDefault();
                    return;
                }
                // Si el item no tiene vista (es contenedor con hijos), dejamos que el treeview lo maneje
                if (!vista) {
                    return;
                }
                // Actualizar clases 'active'
                $('ul.nav-sidebar .nav-link').removeClass('active');
                $link.addClass('active');
                $link.parents('.has-treeview').each(function() {
                    $(this).children('a.nav-link').addClass('active');
                });

                // Llamada centralizada para cargar contenido
                if (typeof cargarContenido === 'function') {
                    cargarContenido('content-wrapper', 'views/' + vista, modulo);
                } else {
                    console.warn('cargarContenido no está definida');
                }
            });
        });
    })(jQuery);
</script>