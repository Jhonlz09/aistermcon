<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
                <li class="nav-item">
                    <a id="btnSide" class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
    </ul>
    <ul class="navbar-nav">
    <?php if ($_SESSION["crear2"]) : ?>
        <li class="nav-item">
            <a class="nav-link ctrl-side" id="first_control" data-widget="control-sidebar" data-target=".first-sidebar" data-controlsidebar-slide="true" href="#" role="button">
            <i class="fas fa-clipboard fa-lg"><span style="font-size:1rem"> GUI</span></i>
            </a>
        </li>
    <?php endif; ?>
        <li class="nav-item">
            <a class="nav-link ctrl-side" id="second_control" data-widget="control-sidebar"  data-target=".second-sidebar" data-controlsidebar-slide="true" href="#" role="button">
            <i class="fas fa-file-invoice-dollar fa-lg"><span style="font-size:1rem"> PPT</span></i>
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