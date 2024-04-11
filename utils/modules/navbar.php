<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
                <li class="nav-item">
                    <a id="btnSide" class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
    </ul>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link ctrl-side" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
            <i class="fas fa-clipboard fa-lg"><span style="font-size:1rem"> Guía</span></i>
            </a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link setA" onclick="cargarContenido('content-wrapper', 'views/perfil.php')" role="button">
                <i class="fas fa-user"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="utils/database/logout.php" role="button">
                <i class="fas fa fa-power-off"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->