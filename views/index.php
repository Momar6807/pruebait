<!DOCTYPE html>
<html lang="es">

<head>
    <title>Prueba IT</title>
    <?php include './views/layout/head.php'; ?>
</head>

<body>
    <h1 class="h1 text-center my-2">Prueba IT</h1>
    <div class="d-flex justify-content-center">
        <div class="card mt-2">
            <div class="card-body" style="max-width: 600px">
                <p class="h3 card-title">¡Bienvenid@!</p>
                <p class="h4 card-title">Para acceder, ingresa tus credenciales:</p>
                <p class="alert alert-info">
                    Credenciales de prueba
                    <br />
                    usuario: admin
                    <br />
                    contaseña: root
                </p>
                <form name="login">
                    <div class="mb-2">
                        <label for="username" class="form-label">Usuario</label>
                        <input type="text" name="username" class="form-control" placeholder="Usuario"
                            aria-describedby="helpId" />
                        <label for="password" class="form-label">Contraseña</label>
                        <input name="password" class="form-control" placeholder="Contraseña" type="password" />
                    </div>
                    <button type="submit" style="width: 100%;" class="btn btn-primary">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</body>

<script>
    $(document).ready(function () {
        $('form[name="login"]').on('submit', (event) => {
            event.preventDefault();
            const username = $('input[name="username"]').val();
            const password = $('input[name="password"]').val();
            // llamada a la api para iniciar sesión
            $.ajax({ method: 'POST', url: '/login', data: { username: username, password: password } })
                .done((data) => {
                    data = JSON.parse(data);
                    if (data.status == 200) {
                        // redirección al módulo de usuarios
                        window.location.href = '/users';
                    } else {
                        console.log(data);
                        alert('Credenciales incorrectas');
                    }
                }
                )

        });
    })
</script>

</html>