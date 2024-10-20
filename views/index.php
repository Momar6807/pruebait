<!DOCTYPE html>
<html lang="es">

<?php include './views/layout/head.php'; ?> acceder

<body>
    <h1 class="title">Prueba IT</h1>
    <div class="card mt-5">
        <div class="card-body" style="max-width: 600px">
            <h2 class="card-title">¡Bienvenid@!</h2>
            <h3 class="card-title">Para acceder, ingresa tus credenciales:</h3>
            <p class="alert alert-info">
                Credenciales de prueba
                <br />
                usuario: admin
                <br />
                contaseña: root
            </p>
            <input name="username" class="form-control" placeholder="Usuario" />
            <input name="password" class="form-control" placeholder="Contraseña" type="password" />
            <button name="login" style="width: 100%;" class="btn btn-primary">Enviar</button>
        </div>
    </div>
</body>

<script>
    $(document).ready(function () {
        $('input[name="login"]').on('click', () => {
            const username = $('input[name="username"]').val();
            const password = $('input[name="password"]').val();
            alert(username);


        });
    })
</script>

</html>