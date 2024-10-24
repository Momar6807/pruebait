<?php
session_start();
$url = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$urlParts = explode('/', trim($url, '/'));
$route = '/' . $urlParts[0] . (isset($urlParts[1]) ? '/' . $urlParts[1] : '') . (isset($urlParts[2]) && is_numeric($urlParts[2]) ? '/:id' : '');
switch ($route) {
    case '/':
        if (isset($_SESSION['user'])) {
            header('Location: /users');
        } else {
            require './views/index.php';
        }
        break;
    case '/users':
        if (isset($_SESSION['user'])) {
            require './views/users/index.php';
        } else {
            // redireccionar al inicio
            header('Location: /');
        }
        break;
    case '/api/users':
        require './controllers/users.php';
        switch ($method) {
            case 'GET':
                header('Content-Type: application/json; charset=utf-8');
                $userList = getUsers();
                echo json_encode(['status' => 200, 'data' => $userList]);
                break;
            case 'POST':
                header('Content-Type: application/json; charset=utf-8');
                if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['first_name']) && isset($_POST['last_name'])) {
                    $createdUser = createUser(
                        $_POST['username'],
                        $_POST['email'],
                        $_POST['password'],
                        $_POST['first_name'],
                        $_POST['last_name']
                    );
                    if (!$createdUser['error']) {
                        echo json_encode(['status' => 200, 'data' => $createdUser['user']]);
                    } else {
                        echo json_encode(['status' => 500, 'error' => $createdUser['data']]);
                    }
                } else {
                    echo json_encode(['status' => 500, 'error' => 'Todos los campos son obligatorios']);
                }
                break;
            case 'DELETE':
                break;
            default:
                require './views/405.php';
                break;
        }
        break;
    case '/api/users/:id':
        require './controllers/users.php';
        $userId = (int) $urlParts[2];
        switch ($method) {
            case 'GET':
                header('Content-Type: application/json; charset=utf-8');
                $user = getUser($userId);
                if (!$user['error']) {
                    echo json_encode(['status' => 200, 'data' => $user['data']]);
                    return;
                }
                echo json_encode(['status' => 500, 'error' => $user['data']]);
                break;
            case 'POST':
                header('Content-Type: application/json; charset=utf-8');
                if (
                    isset($_POST['username'])
                    && isset($_POST['email'])
                    && isset($_POST['first_name'])
                    && isset($_POST['last_name'])
                ) {
                    $updatedUser = updateUser(
                        $userId,
                        $_POST['username'],
                        $_POST['email'],
                        $_POST['first_name'],
                        $_POST['last_name'],
                        $_POST['password'] ?? null,
                    );
                    if (!$updatedUser['error']) {
                        echo json_encode(['status' => 200, 'data' => $updatedUser['data']]);
                        return;
                    } else {
                        echo json_encode(['status' => 500, 'error' => $updatedUser['data']]);
                        return;
                    }
                } else {
                    echo json_encode(['status' => 400, 'data' => 'Faltan datos necesarios']);
                }
                break;
            case 'DELETE':
                header('Content-Type: application/json; charset=utf-8');
                $deleted = deleteUser($userId);
                echo json_encode(['status' => 200, 'message' => 'Usuario eliminado', 'data' => $deleted]);
                break;
            default:
                require './views/405.php';
                break;
        }
        break;
    case '/login':
        if ($method != 'POST') {
            require './views/405.php';
        } else {
            require './controllers/users.php';
            $auth = login($_POST['username'], $_POST['password']);
            if (!$auth['error']) {
                // correcto, iniciar sesion y redireccionar
                $_SESSION['user'] = $auth['data'];
                echo json_encode(['status' => 200, 'data' => $auth]);
            } else {
                echo json_encode(['status' => 500, 'error' => $auth['message']]);
            }
        }
        break;
    case '/logout':
        unset($_SESSION['user']);
        header('Location: /');
        break;
    default:
        require './views/404.php';
        break;
}