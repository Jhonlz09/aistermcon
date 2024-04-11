<head>
    <title>Iniciar sesion</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<div class="container-login">
    <div class="wrap-login">
        <form autocomplete="off" class="needs-validation-login login-form" id="formLogin" method="post" novalidate>
            <span class="login-form-title"> <img style="height:3rem;margin-top: -6px;" src="assets/img/logo_menu.png" alt="logo aistermcon"> AISTERMCON</span>
            <div class="wrap-input100">
                <i class="fa-solid fa-user icon"></i>
                <input autocomplete="off" class="input100" type="text" id="usuario" name="usuario" placeholder="Usuario" required>
                <span class="focus-efecto"></span>
                <div class="font-weight-bold invalid-feedback">Debe ingresar su usuario*</div>
            </div>

            <div class="wrap-input100">
                <i class="fa-solid fa-lock icon"></i>
                <input autocomplete="off" class="input100" type="password" id="password" name="password" placeholder="Contraseña" required>
                <span class="focus-efecto"></span>
                <div class="font-weight-bold invalid-feedback">Debe ingresar su contraseña*</div>
            </div>
            <div class="container-login-form-btn">
                <div class="wrap-login-form-btn">
                    <button type="submit" id="iniciar" class="login-form-btn">Ingresar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Borrar todo el localStorage
        let mode = localStorage.getItem('darkMode');

        localStorage.clear();

        // Restaurar la variable desde la copia de seguridad
        localStorage.setItem('darkMode', mode);

        const form = document.getElementById('formLogin'),
            btn = document.getElementById('iniciar'),
            user = document.getElementById('usuario'),
            pass = document.getElementById('password');

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }
            $(btn).hide();
            btn.disabled = true;
            const username = user.value,
                password = pass.value;
            $.ajax({
                type: 'POST',
                url: 'controllers/sesion.controlador.php',
                data: {
                    username: username,
                    password: password
                },
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = "/aistermcon/";
                    } else if (response === 'invalid_password') {
                        validarLogin('La contraseña es incorrecta', false, btn, null, pass);
                    } else {
                        validarLogin('El usuario no existe', true, btn, user)
                    }
                }
            });
        });
    });

    function validarLogin(title, limpiar, btn, user, pass = null) {
        Swal.fire({
            title: title,
            icon: 'error',
            background: '#d3d3d3c4',
            // confirmButtonColor: '#b7040f',
            didClose: () => {
                setTimeout(function() {
                    if (limpiar) {
                        user.value = '';
                        user.focus();
                    } else {
                        pass.value = '';
                        pass.focus();
                    }
                    $(btn).show();
                    btn.disabled = false;
                }, 0);
            }
        });
    }
</script>

</html>