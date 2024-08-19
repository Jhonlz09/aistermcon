<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION["crear4"])) {
    $_SESSION["crear4"] = false;
}

// Verificar si $_SESSION["crear7"] está definido, si no, establecerlo en false
if (!isset($_SESSION["crear7"])) {
    $_SESSION["crear7"] = false;
}

if (!isset($_SESSION["crear12"])) {
    $_SESSION["crear12"] = false;
}
?>

<!-- Navbar -->
<nav id="navbar-fix" class="main-header navbar navbar-expand navbar-light" style="position:fixed;top:0">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
                <li class="nav-item">
                    <a id="btnSide" class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
    </ul>
    <ul class="navbar-nav">
    <?php if ($_SESSION["crear4"] || $_SESSION["crear7"] ) : ?>
        <li class="nav-item">
            <a class="nav-link ctrl-side" id="first_control" data-widget="control-sidebar" data-target=".first-sidebar" data-controlsidebar-slide="true" href="#" role="button">
            <i class="fas fa-clipboard fa-lg"><span style="font-size:1rem"> GUI</span></i>
            </a>
        </li>
    <?php endif; ?>
    
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
            <a id="btnlogout" class="nav-link" href="utils/database/logout.php" role="button">
                <i class="fas fa fa-power-off"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->