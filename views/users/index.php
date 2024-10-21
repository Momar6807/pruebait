<!DOCTYPE html>
<html lang="en">

<head>
    <?php include './views/layout/head.php'; ?>
    <title>Usuarios</title>
</head>

<body class="mx-2">
    <?php include './views/layout/navbar.php' ?>

    <h1 class="h2">Listado de Usuarios</h1>
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#nuevoUsuarioModal">Nuevo usuario</button>
            <input type="text" id="searchInput" class="form-control w-50" placeholder="Buscar usuario..." />
        </div>
    </div>
    </div>
    <div class="card my-2">
        <div class="table-responsive">
            <table class="table table-striped table-responsive card-body">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido</th>
                        <th scope="col">F. Creación</th>
                        <th scope="col">F. Actualización</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody id="users">
                </tbody>
            </table>
        </div>
    </div>

    <!-- nuevo usuario -->
    <div class="modal fade" id="nuevoUsuarioModal" tabindex="-1" aria-labelledby="nuevoUsuarioModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevoUsuarioModalLabel">Registrar Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="nuevoUsuarioForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="first_name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- editar usuario  -->
    <div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarUsuarioModalLabel">Editar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editarUsuarioForm">
                        <input type="hidden" id="editUserId">
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="editUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="editFirstName" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="editFirstName" name="first_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editLastName" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="editLastName" name="last_name" required>
                        </div>

                        <div class="mb-3">
                            <div class="alert alert-info">
                                <p>Si no deseas actualizar la contraseña, deja estos campos en blanco</p>
                            </div>
                            <label for="editLastName" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="editPassword" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="editLastName" class="form-label">Confirmar contraseña</label>
                            <input type="password" class="form-control" id="editPasswordConfirm" name="password_confirm">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- eliminar -->
    <div class="modal fade" id="eliminarUsuarioModal" tabindex="-1" aria-labelledby="eliminarUsuarioModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eliminarUsuarioModalLabel">Eliminar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás segur@ de que deseas eliminar al usuario <strong id="deleteUsername"></strong>?</p>
                    <input type="hidden" id="deleteUserId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteUser">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    $(document).ready(() => {
        const userList = $('#users');
        const searchInput = $('#searchInput');

        // obtener usuarios
        $.ajax({ url: '/api/users' }).done((response) => {
            let users = response.data;
            renderTable(users);

            // filtrar barra de busqueda
            searchInput.on('input', function () {
                const searchTerm = $(this).val().toLowerCase();
                const filteredUsers = users.filter(user =>
                    user.username.toLowerCase().includes(searchTerm) ||
                    user.email.toLowerCase().includes(searchTerm) ||
                    user.first_name.toLowerCase().includes(searchTerm) ||
                    user.last_name.toLowerCase().includes(searchTerm)
                );
                renderTable(filteredUsers);
            });
        });

        //mostrar registros de la tabla
        function renderTable(users) {
            userList.html(() => {
                let html = '';
                users.forEach((user) => {
                    html += `
                    <tr>
                        <th>${user.id}</th>
                        <td>${user.username}</td>
                        <td>${user.email}</td>
                        <td>${user.first_name}</td>
                        <td>${user.last_name}</td>
                        <td>${user.created_at}</td>
                        <td>${user.updated_at}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editarUsuarioModal" data-id="${user.id}" data-username="${user.username}" data-email="${user.email}" data-first_name="${user.first_name}" data-last_name="${user.last_name}">Editar</button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminarUsuarioModal" data-id="${user.id}" data-username="${user.username}">Eliminar</button>
                        </td>
                    </tr>
                    `;
                });
                return html;
            });
        }

        // crear usuario
        $('#nuevoUsuarioForm').on('submit', function (event) {
            event.preventDefault();
            const formData = $(this).serialize();
            $.ajax({
                method: 'POST',
                url: '/api/users',
                data: formData
            }).done((response) => {
                if (response.status === 200) {
                    $('#nuevoUsuarioModal').modal('hide');
                    alert('Usuario creado exitosamente');
                    location.reload();
                } else {
                    alert('Error al crear el usuario: ' + response.error);
                }
            });
        });


        // asignar datos en edicion de usuario
        $(document).on('click', '[data-bs-target="#editarUsuarioModal"]', function () {
            const userId = $(this).data('id');
            const username = $(this).data('username');
            const email = $(this).data('email');
            const firstName = $(this).data('first_name');
            const lastName = $(this).data('last_name');

            $('#editUserId').val(userId);
            $('#editUsername').val(username);
            $('#editEmail').val(email);
            $('#editFirstName').val(firstName);
            $('#editLastName').val(lastName);
        });

        // editar usuario
        $('#editarUsuarioForm').on('submit', function (event) {
            event.preventDefault();
            const userId = $('#editUserId').val();
            const formData = $(this).serialize();
            if ($('#editPassword').val() != '' && !($('#editPassword').val() == $('#editPasswordConfirm').val())) {
                return alert('Las contraseñas no coinciden');
            }
            $.ajax({
                method: 'PUT',
                url: `/api/users/${userId}`,
                data: formData
            }).done((response) => {
                console.log(response);
                if (response.status === 200) {
                    $('#editarUsuarioModal').modal('hide');
                    alert('Usuario actualizado exitosamente');
                    location.reload();
                } else {
                    alert('Error al actualizar el usuario: ' + response.data);
                }
            });
        });

        // asignar datos de eliminación
        $(document).on('click', '[data-bs-target="#eliminarUsuarioModal"]', function () {
            const userId = $(this).data('id');
            const username = $(this).data('username');
            $('#deleteUserId').val(userId);
            $('#deleteUsername').text(username);
        });

        $('#confirmDeleteUser').on('click', function () {
            const userId = $('#deleteUserId').val();
            $.ajax({
                method: 'DELETE',
                url: `/api/users/${userId}`
            }).done((response) => {
                if (response.status === 200) {
                    $('#eliminarUsuarioModal').modal('hide');
                    alert('Usuario eliminado exitosamente');
                    location.reload();
                } else {
                    alert('Error al eliminar el usuario: ' + response.error);
                }
            });
        });
    });
</script>

</html>