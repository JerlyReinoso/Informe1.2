<?php
require_once '../config/db.php';  // Conectar a la base de datos
require_once '../vendor/autoload.php';  // Para usar JWT y bcrypt

use \Firebase\JWT\JWT;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_GET['action'] ?? '';

    if ($action == 'create') {
        // Ruta para crear un nuevo usuario
        $data = json_decode(file_get_contents("php://input"));
        $nombre = $data->nombre;
        $email = $data->email;
        $password = $data->password;

        // Cifrar la contraseña
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insertar el usuario en la base de datos
        $sql = "INSERT INTO users (nombre, email, password) VALUES ('$nombre', '$email', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Usuario creado exitosamente"]);
        } else {
            echo json_encode(["message" => "Error: " . $sql . "<br>" . $conn->error]);
        }
    } elseif ($action == 'login') {
        // Ruta para iniciar sesión
        $data = json_decode(file_get_contents("php://input"));
        $email = $data->email;
        $password = $data->password;

        // Verificar si el usuario existe
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verificar la contraseña
            if (password_verify($password, $user['password'])) {
                // Generar JWT
                $key = "secreta";
                $issuedAt = time();
                $expirationTime = $issuedAt + 3600;  // el token expira en 1 hora
                $payload = array(
                    "user_id" => $user['id'],
                    "email" => $user['email'],
                    "exp" => $expirationTime
                );

                $jwt = JWT::encode($payload, $key);

                // Devolver el JWT al cliente
                echo json_encode(["message" => "Inicio de sesión exitoso", "token" => $jwt]);
            } else {
                echo json_encode(["message" => "Contraseña incorrecta"]);
            }
        } else {
            echo json_encode(["message" => "Usuario no encontrado"]);
        }
    }
}
?>
