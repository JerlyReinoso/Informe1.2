<?php
class User {
    public $id;
    public $nombre;
    public $email;
    public $password;

    public function __construct($id, $nombre, $email, $password) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password = $password;
    }

    public static function create($nombre, $email, $password) {
        global $conn;  // Usamos la conexión global

        // Cifra la contraseña con bcrypt
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (nombre, email, password) VALUES (:nombre, :email, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password_hashed);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public static function getUserByEmail($email) {
        global $conn;  // Usamos la conexión global

        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new User($row['id'], $row['nombre'], $row['email'], $row['password']);
        }
        return null;
    }
}
?>