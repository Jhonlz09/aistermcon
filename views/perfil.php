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
                        <h3 class="profile-username text-center">Nina Mcintire</h3>
                        <p class="text-muted text-center">Software Engineer</p>

                        <div class="alert bg-gradient-navy text-center" role="alert">
                            Administrador
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
                        <form class="form-horizontal">
                            <div class="form-group row mb-3">
                                <label for="inputName" class="col-sm-3 col-form-label fsize-1"><i class="fa-solid fa-signature"></i> Nombres</label>
                                <div class="col">
                                    <input type="text" class="form-control border-2" id="inputName" placeholder="Nombres">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="inputEmail" class="col-sm-3 col-form-label"><i class="fa-solid fa-user"></i> Usuario</label>
                                <div class="col">
                                    <input type="text" class="form-control border-2" id="inputEmail" placeholder="Nombre de usuario">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="btnEditar" class="col-sm-3 col-form-label fsize-2"><i class="fa-solid fa-lock"></i> Contraseña</label>
                                <div class="col">
                                    <span id="btnEditar" class="dis e-span badge bg-gradient-dark" data-icon="fa-tags" data-value="Categoria" title='Editar'><i class="fa-solid fa-pencil"></i></span>
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