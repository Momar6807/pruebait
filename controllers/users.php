<?php
include './lib/conn.php';
function getUsers()
{
    global $conn;
    // traer listado de usuarios (sin contraseña)
    $query = $conn->query("SELECT id, username, email, first_name, last_name, created_at, updated_at FROM users;");
    $listado = $query->fetch_all(MYSQLI_ASSOC);
    return $listado;
}

function getUser($id)
{
    global $conn;
    // traer usuario si existe
    $query = $conn->prepare("SELECT id, username, email, first_name, last_name, created_at, updated_at FROM users where id = ?;");
    $query->bind_param('d', $id);
    $query->execute();
    $res = $query->get_result();
    if ($res->num_rows > 0) {
        return ['error' => false, 'data' => $res->fetch_assoc()];
    } else {
        return ['error' => true, 'data' => 'Usuario no encontrado'];
    }
}

function createUser($username, $email, $password, $firstname, $lastname)
{
    global $conn;
    // revisar si existe un usuario con ese correo o nombre de usuario
    $checkQuery = $conn->prepare("SELECT * FROM users WHERE username  = ? OR email = ?");
    $checkQuery->bind_param('ss', $username, $email);
    $checkQuery->execute();
    $check = $checkQuery->get_result();
    if ($check->num_rows > 0) {
        return ['error' => true, 'data' => 'Un usuario con este correo o nombre de usuario ya existe'];
    }
    // si no existe, continuar con el registro
    $query = $conn->prepare("INSERT INTO users (username, email, password, first_name, last_name) VALUES (?,?,?,?,?)");
    $query->bind_param('sssss', $username, $email, $password, $firstname, $lastname);
    if ($query->execute()) {
        // regresar el usuario recien creado
        $createdUser = $conn->query("SELECT * FROM users order by id desc limit 1")->fetch_assoc();
        return ['user' => $createdUser, 'error' => false];
    } else {
        return ['error' => true, 'data' => $query->error];
    }
}


function updateUser($id, $username, $email, $firstname, $lastname, $password = null)
{
    global $conn;
    // revisar si existe un usuario con ese id
    $checkQuery = $conn->prepare("SELECT * FROM users WHERE id  = ?");
    $checkQuery->bind_param('d', $id);
    $checkQuery->execute();
    $check = $checkQuery->get_result();

    if ($check->num_rows > 0) {
        // buscar nombre de usuario, si existe validar si coincide con el id proporcionado para evitar duplicados
        $existQuery = $conn->prepare("SELECT * FROM users WHERE username  = ? ");
        $existQuery->bind_param('s', $username);
        $existQuery->execute();
        $userExist = $existQuery->get_result();
        if ($userExist->num_rows > 0) {
            // validar si el usuario tiene el id proporcionado
            $user = $userExist->fetch_assoc();
            $proporcionado = $user['id'] == $id;
            if ($proporcionado) {
                // si coincide, actualizar el usuario
                if ($password) {
                    $query = $conn->prepare("UPDATE users set username = ?, email = ?, password = ?, first_name = ?, last_name = ? WHERE id = ?");
                    $query->bind_param('sssssd', $username, $email, $password, $firstname, $lastname, $id);
                } else {
                    $query = $conn->prepare("UPDATE users set username = ?, email = ?, first_name = ?, last_name = ? WHERE id = ?");
                    $query->bind_param('ssssd', $username, $email, $firstname, $lastname, $id);
                }
                // si existe el usuario, actualizar sus datos
                $query->execute();
                $query->get_result();
                if ($query->affected_rows > 0 || count($query->error_list) == 0) {
                    return ['error' => false, 'data' => 'Usuario actualizado'];
                } else {
                    return ['error' => true, 'data' => 'No se pudo actualizar el usuario'];
                }
            } else {
                return ['error' => true, 'data' => 'Ya hay un usuario con ese nombre de usuario registrado'];
            }
        } else {
            // si el usuario no existe, actualizar los datos
            if ($password) {
                $query = $conn->prepare("UPDATE users set username = ?, email = ?, password = ?, first_name = ?, last_name = ? WHERE id = ?");
                $query->bind_param('sssssd', $username, $email, $password, $firstname, $lastname, $id);
            } else {
                $query = $conn->prepare("UPDATE users set username = ?, email = ?, first_name = ?, last_name = ? WHERE id = ?");
                $query->bind_param('ssssd', $username, $email, $firstname, $lastname, $id);
            }
            $query->execute();
            if ($query->affected_rows > 0) {
                return ['error' => false, 'data' => 'Usuario actualizado'];
            } else {
                return ['error' => true, 'data' => 'No se pudo actualizar el usuario'];
            }
        }
    } else {
        return ['error' => true, 'data' => 'No existe el usuario'];
    }
}
function deleteUser($id)
{
    global $conn;
    $query = $conn->prepare('DELETE FROM users WHERE id = ?');
    $query->bind_param('d', $id);
    $deleted = $query->execute();
    return ['error' => false, 'user' => $deleted];
}

function login($username, $password)
{
    global $conn;

    // obtener datos de usuario
    $query = $conn->prepare("SELECT id, username, password, first_name, last_name FROM users WHERE username = ?");
    $query->bind_param('s', $username);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows > 0) {
        // si existe, validar contraseña
        $user = $result->fetch_assoc();
        if ($password == $user['password']) {
            return ['error' => false, 'message' => 'Inicio de sesión exitoso', 'data' => $user];
        } else {
            // error
            return ['error' => true, 'message' => 'Credenciales inválidas'];
        }
    } else {
        // no encontrado
        return ['error' => true, 'message' => 'Credenciales inválidas'];
    }
}