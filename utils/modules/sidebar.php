<?php
// sidebar.php
?>
<!-- Main Sidebar Container -->
<aside style="backdrop-filter:blur(85px);-webkit-backdrop-filter:blur(85px);;white-space:nowrap;overflow-x:hidden" class="main-sidebar sidebar-light-lightblue elevation-4">
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
                /* DEBUG: Verificar que la sesión y menuTree existen (se imprimará como comentario HTML) Quitar estas líneas en producción */
                if (!isset($_SESSION)) session_start(); // asegura que la sesión esté iniciada
                echo '<!-- DEBUG: isset($_SESSION)=' . (isset($_SESSION) ? 'yes' : 'no') . ' -->';
                if (!isset($_SESSION['menuTree'])) {
                    echo '<!-- DEBUG: menuTree NO existe -->';
                } else {
                    $countRoots = is_array($_SESSION['menuTree']) ? count($_SESSION['menuTree']) : 0;
                    echo '<!-- DEBUG: menuTree existe; raíces=' . $countRoots . ' -->';
                    // echo '<!-- ' . htmlspecialchars(print_r($_SESSION['menuTree'], true)) . ' -->'; // opcional, muy verboso
                }

                function renderMenu($items, $isSub = false)
                {
                    if ($isSub) echo '<ul class="nav nav-treeview">';

                    // si $items no es un array o está vacío, no hacemos nada
                    if (!is_array($items) || count($items) === 0) {
                        if ($isSub) echo '</ul>';
                        return;
                    }

                    foreach ($items as $item) {
                        // protección: asegurar propiedades mínimas
                        if (!isset($item->id) || !isset($item->modulo)) continue;

                        $hasChildren = isset($item->children) && is_array($item->children) && count($item->children) > 0;

                        $liId = 'menu-item-' . (int)$item->id;
                        echo '<li id="' . $liId . '" class="nav-item ' . ($hasChildren ? 'has-children' : '') . '">';

                        // Contenedor (sin vista o con hijos)
                        if ($hasChildren || empty($item->vista)) {

                            echo '<a href="#" class="nav-link" data-vista="" data-id="' . (int)$item->id . '" data-modulo="' . htmlspecialchars($item->modulo, ENT_QUOTES) . '">';
                            echo '<i class="nav-icon fas ' . htmlspecialchars($item->icon ?? '', ENT_QUOTES) . '"></i>';
                            echo '<p>' . htmlspecialchars($item->modulo, ENT_QUOTES) . ' <i class="right fas fa-angle-left"></i></p>';
                            echo '</a>';

                            // Renderizar hijos recursivamente
                            renderMenu($item->children, true);
                        } else {
                            // Item con vista -> clickable
                            $vista = $item->vista;
                            echo '<a href="#" class="nav-link" data-vista="' . htmlspecialchars($vista, ENT_QUOTES) . '" data-id="' . (int)$item->id . '" data-modulo="' . htmlspecialchars($item->modulo, ENT_QUOTES) . '" onclick="cargarContenido(\'content-wrapper\', \'views/' . htmlspecialchars($vista, ENT_QUOTES) . '\', \'' . addslashes($item->modulo) . '\'); return false;">';
                            echo '<i class="nav-icon fas ' . htmlspecialchars($item->icon ?? '', ENT_QUOTES) . '"></i>';
                            echo '<p>' . htmlspecialchars($item->modulo, ENT_QUOTES) . '</p>';
                            echo '</a>';
                        }

                        echo '</li>';
                    }

                    if ($isSub) echo '</ul>';
                }

                // LLAMADA A renderMenu: verifica existencia y llama
                if (isset($_SESSION['menuTree']) && is_array($_SESSION['menuTree']) && count($_SESSION['menuTree']) > 0) {
                    renderMenu($_SESSION['menuTree']);
                } else {
                    // Mensaje pequeño (en HTML comentado para evitar romper el diseño)
                    echo '<!-- No hay módulos para este usuario o menuTree está vacío -->';
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

        // Verificar si no es un dispositivo móvil
        if (window.matchMedia("(max-width: 768px)").matches) {
            const sidebar = document.querySelector('.main-sidebar');
            sidebar.classList.add('sidebar-no-expand');
            const nav = document.getElementById('nav');
            nav.classList.remove('nav-child-indent')
        }

        const nav = document.getElementById("nav");

        // Registrar clicks delegados
        nav.addEventListener("click", function(e) {
            const link = e.target.closest("a.nav-link");
            if (!link) return;
            const li = link.closest("li");
            // Solo manejamos la CLASE ACTIVE
            const hasSubmenu = li.classList.contains("has-treeview");
            // Caso 1: tiene submenu → solo dejar que AdminLTE lo abra
            if (hasSubmenu) {
                return; // NO tocamos active
            }
            // Caso 2: es una vista final → marcar active
            markAsActive(link);
        });

        function markAsActive(link) {
            // 1) eliminar active existente
            document.querySelectorAll("#nav a.nav-link.active")
                .forEach(a => a.classList.remove("active"));
            // 2) marcar el clickeado
            link.classList.add("active");
            // 3) abrir todos los padres (sin romper AdminLTE)
            openParents(link);
        }

        function openParents(link) {
            let li = link.closest("li");
            while (li && li.id !== "nav") {
                const parentUl = li.parentElement;
                if (parentUl.classList.contains("nav-treeview")) {
                    const parentLi = parentUl.closest("li.has-treeview");
                    if (parentLi) {
                        parentLi.classList.add("menu-open");
                        const parentA = parentLi.querySelector(":scope > a.nav-link");
                        if (parentA) parentA.classList.add("active");
                    }
                }
                li = li.parentElement;
            }
        }
    });
</script>