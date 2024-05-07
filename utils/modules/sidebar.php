<?php $permisosUsuario = PermisosControlador::ctrObtenerPermisos($_SESSION["s_usuario"]->id);
foreach ($permisosUsuario as $permiso) {
    $_SESSION["crear" . $permiso->id] = $permiso->crear;
    $_SESSION["editar" . $permiso->id] = $permiso->editar;
    $_SESSION["eliminar" . $permiso->id] = $permiso->eliminar;
} ?>
<!-- Main Sidebar Container -->
<aside style="backdrop-filter:blur(10px);white-space:nowrap;overflow-x:hidden" class="main-sidebar  sidebar-light-lightblue elevation-4 sidebar-no-expand">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <img src="assets/img/logo_menu.png" alt="logo" class="brand-image" />
        <span style="font-family: 'Source Sans Pro';" class="brand-text font-weight-bold">AISTERMCON S.A</span><br>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul id="nav" class=" nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php
                $first = true;
                foreach ($permisosUsuario as $menu) : ?>
                    <li class="nav-item liv">
                        <a id="<?php echo $menu->modulo ?>" class="nav-link setA <?php if ($first) : ?>
                                                                            <?php echo 'active'; ?>
                                                                        <?php endif; ?>" <?php if (!empty($menu->vista)) : ?> onclick="if (!$(this).hasClass('active')) cargarContenido('content-wrapper', 'views/<?php echo $menu->vista; ?>','<?php echo $menu->modulo ?>')" <?php endif; ?>>
                            <i class="nav-icon fas <?php echo $menu->icon; ?>"></i>
                            <p><?php echo $menu->modulo ?> <?php if (empty($menu->vista)) : ?> <i class="right fas fa-angle-left"></i> <?php endif; ?></p>
                        </a>

                        <?php if (empty($menu->vista)) : ?>
                            <?php
                            $subMenuUsuario = PermisosControlador::ctrObtenerPermisoSubmenu($_SESSION["s_usuario"]->id, $menu->id);
                            ?>
                            <ul class="nav nav-treeview">
                                <?php foreach ($subMenuUsuario as $subMenu) :
                                    $_SESSION["crear" . $subMenu->id] = $subMenu->crear;
                                    $_SESSION["editar" . $subMenu->id] = $subMenu->editar;
                                    $_SESSION["eliminar" . $subMenu->id] = $subMenu->eliminar;
                                ?>
                                    <li class="nav-item">
                                        <a id="<?php echo $menu->modulo ?>" style="cursor: pointer;" class="nav-link setB" onclick="if (!$(this).hasClass('active')) cargarContenido('content-wrapper','views/<?php echo $subMenu->vista ?>', '<?php echo $menu->modulo ?>' )">
                                            <i class="fas <?php echo $subMenu->icon; ?> nav-icon"></i>
                                            <p><?php echo $subMenu->modulo; ?></p>
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
            <!-- <li class="mode">
                <div class="moon-sun">
                    <i class="fas fa-sun sun icon"></i>
                    <i class="fas fa-moon moon icon"></i>
                </div>
                <span class="mode-text text">Modo oscuro</span>
                <div class="toggle-switch">
                    <span class="switch"></span>
                </div>
            </li> -->

        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<!-- /.Main Sidebar Container -->