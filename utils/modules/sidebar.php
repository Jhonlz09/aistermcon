<?php 
?>
<!-- Main Sidebar Container -->
<aside style="backdrop-filter:blur(85px);-webkit-backdrop-filter:blur(85px);;white-space:nowrap;overflow-x:hidden" class="main-sidebar  sidebar-light-lightblue elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <img src="assets/img/logo_menu.png" alt="logo" class="brand-image" />
        <span style="font-family: 'Source Sans Pro';" class="brand-text font-weight-bold"><?php echo isset($_SESSION["empresa"]) ? $_SESSION["empresa"] : ''; ?></span><br>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul id="nav" class=" nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="true">
                <?php
                $first = true;
                foreach ($_SESSION["permisosUsuario"] as $menu) : ?>
                    <li class="nav-item sub">
                        <a id="<?php echo $menu->modulo ?>" class="nav-link setA <?php if ($first) : ?>
                                                                            <?php echo 'active'; ?>
                        <?php endif; ?>" <?php if (!empty($menu->vista)) : ?> onclick="if (!$(this).hasClass('active')) cargarContenido('content-wrapper', 'views/<?php echo $menu->vista; ?>','<?php echo $menu->modulo ?>')" <?php endif; ?>>
                            <i class="nav-icon fas <?php echo $menu->icon; ?>"></i>
                            <p><?php echo $menu->modulo ?> <?php if (empty($menu->vista)) : ?> <i class="right fas fa-angle-left"></i> <?php endif; ?></p>
                        </a>
                        <?php if (empty($menu->vista)) : ?>
                            <?php $subMenuUsuario = PermisosControlador::ctrObtenerPermisoSubmenu($_SESSION["s_usuario"]->id, $menu->id);?>
                            <ul class="nav nav-treeview ">
                                <?php foreach ($subMenuUsuario as $subMenu) :
                                    $_SESSION["crear" . $subMenu->id] = $subMenu->crear;
                                    $_SESSION["editar" . $subMenu->id] = $subMenu->editar;
                                    $_SESSION["eliminar" . $subMenu->id] = $subMenu->eliminar;
                                ?>
                                    <li class="nav-item">
                                        <a id="<?php echo $subMenu->modulo ?>" style="cursor: pointer;" class="nav-link setB" onclick="if (!$(this).hasClass('active')) cargarContenido('content-wrapper','views/<?php echo $subMenu->vista ?>', '<?php echo $subMenu->modulo ?>' )">
                                            <i class="fas <?php echo $subMenu->icon; ?> nav-icon"></i>
                                            <p><?php echo $subMenu->modulo; ?></p>
                                            <!-- <i class="right fa-regular fa-circle-plus" style="font-size: 1.25rem;"></i> -->
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php
                    $first = false;
                endforeach; ?>
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
    });
</script>