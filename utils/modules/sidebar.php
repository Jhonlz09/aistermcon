<!-- Main Sidebar Container -->
<aside style="backdrop-filter:blur(85px);-webkit-backdrop-filter:blur(85px);white-space:nowrap;overflow-x:hidden" class="main-sidebar sidebar-light-lightblue elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <?php
            // Buscar el archivo del logo, sin importar su extensión
            $logoPath = 'assets/img/logo_menu.png'; // Fallback por defecto
            $archivosLogo = glob('assets/img/logo_menu.*');
            if (!empty($archivosLogo)) {
                $logoPath = $archivosLogo[0]; // Toma el primero que encuentre
            }
            $v = file_exists($logoPath) ? filemtime($logoPath) : time();
        ?>
        <img src="<?php echo $logoPath . '?v=' . $v; ?>" alt="logo" class="brand-image" />
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
                            $id = $item->modulo;
                            $vista = $item->vista ?? '';

                            // Si tiene vista y NO tiene hijos, añadimos data-attributes
                            $dataAttrs = '';
                            if (!$hasChildren && !empty($vista)) {
                                $vistaSafe = htmlspecialchars($vista, ENT_QUOTES);
                                $moduloSafe = htmlspecialchars($item->modulo ?? '', ENT_QUOTES);
                                $dataAttrs = ' data-vista="' . $vistaSafe . '" data-modulo="' . $moduloSafe . '"';
                                
                                // Extraer acciones de este id desde la sesión y enviarlas
                                $accionesM = [];
                                if(isset($item->id)){
                                    foreach($_SESSION as $key => $val){
                                        if($val === true && preg_match('/^([a-zA-Z_]+)'.$item->id.'$/', $key, $m)){
                                            $accionesM[] = $m[1];
                                        }
                                    }
                                }
                                if(!empty($accionesM)){
                                    $dataAttrs .= " data-acciones='" . json_encode($accionesM) . "'";
                                }
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
<script>
    (function($) {
        $(function() {
            $('ul.nav-sidebar .nav-link').removeClass('active');
            
            const vistaInicio = '<?php echo isset($_SESSION["vista_inicio"]) ? $_SESSION["vista_inicio"] : ""; ?>';
            let $activeLink;

            if (vistaInicio !== "") {
                // Buscamos el enlace cuyo data-vista coincida con vistaInicio + '.php'
                $activeLink = $('#nav').find('a.nav-link[data-vista="' + vistaInicio + '.php"]');
            }

            // Si no encuentra la vista de inicio específica o no hay vista inicio, usamos el primer enlace como respaldo (comportamiento original)
            if (!$activeLink || $activeLink.length === 0) {
                $activeLink = $('#nav > li.nav-item:first > a.nav-link');
            }

            if ($activeLink.length) {
                $activeLink.addClass('active');
                // Si está dentro de submenús, desplegar y activar menú padre
                $activeLink.parents('.has-treeview').addClass('menu-open');
                $activeLink.parents('.has-treeview').children('a.nav-link').addClass('active');
            }
            // Handler delegado: único punto de entrada para clicks en links del menú
            $('#nav').on('click', 'a.nav-link', function(e) {
                const $link = $(this);
                const vista = $link.data('vista');
                const modulo = $link.data('modulo');
                const acciones = $link.data('acciones'); // Extract JSON actions
                const $ctrlSide = $('#first_control');
                
                // Guardar globalmente las acciones disponibles para evitar enrutamiento estricto
                if (acciones) {
                    window.currentModuleActions = acciones;
                } else {
                    window.currentModuleActions = [];
                }

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
                if ($ctrlSide.hasClass('active')) {
                    $ctrlSide.click();
                    $ctrlSide.removeClass('active');
                }
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