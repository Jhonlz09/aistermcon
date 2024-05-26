<?php require_once "../utils/database/config.php";?>

<head>
    <title>Perfil</title>
</head>
<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <h1 class="col-p">Perfil</h1>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content-header -->
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-dark card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center" style="padding:1.04rem 1rem">
                            <i style="font-size:6rem" class="fas fa-user"></i>
                        </div>
                        <h3 class="profile-username text-center"><?php echo isset($_SESSION["s_usuario"]->nombres) ? $_SESSION["s_usuario"]->nombres : ''; ?></h3>
                        <p class="text-muted text-center"><?php echo isset($_SESSION["s_usuario"]->nombre_usuario) ? $_SESSION["s_usuario"]->nombre_usuario : ''; ?></p>

                        <div class="alert bg-gradient-navy text-center" role="alert">
                        <?php echo isset($_SESSION["s_usuario"]->perfil) ? $_SESSION["s_usuario"]->perfil : ''; ?>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="padding:.75rem 1.25rem">
                        <h3 class="card-title font-weight-bold">Información personal</h3>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" autocomplete="off">
                            <div class="form-group row mb-3">
                                <label for="inputName" style="padding-block:.5rem" class="col-sm-3 col-form-label fsize-1"><i class="fa-solid fa-signature"></i> Nombres</label>
                                <div class="col">
                                    <input type="text" value="<?php echo $_SESSION["s_usuario"]->nombres; ?>" spellcheck="false" class="form-control border-2" id="inputName" placeholder="Nombres">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="inputUser" style="padding-block:.5rem" class="col-sm-3 col-form-label"><i class="fa-solid fa-user"></i> Usuario</label>
                                <div class="col">
                                    <input type="text" value="<?php echo $_SESSION["s_usuario"]->nombre_usuario; ?>" spellcheck="false" class="form-control border-2" id="inputUser" placeholder="Nombre de usuario">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="btnEditarClave" style="padding-block:.5rem" class="col-sm-3 col-form-label fsize-2"><i class="fa-solid fa-lock"></i> Contraseña</label>
                                <div class="col">
                                    <span id="btnEditarClave" class="dis e-span badge bg-gradient-dark" data-icon="fa-tags" data-value="Categoria" title='Editar'><i class="fa-solid fa-pencil"></i></span>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="offset-sm-3 col-sm-9">
                                    <button type="submit" id="btnGuardar" class="btn bg-gradient-navy"><i class="fa-solid fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.Contenido -->
<script>
    var espacioLibre = obtenerEspacioLibreLocalStorage();
    console.log('Espacio libre en localStorage:', espacioLibre);
    var espacioOcupado = obtenerEspacioOcupadoLocalStorage();
    console.log('Espacio ocupado en localStorage:', espacioOcupado);
</script>