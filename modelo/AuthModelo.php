<?php
// modelo/AuthModelo.php

class AuthModelo {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registrarUsuario($username, $password_hash) {
        // El rol por defecto es 'cliente'
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (username, password, rol) VALUES (?, ?, 'cliente')");
        return $stmt->execute([$username, $password_hash]);
    }

    public function buscarUsuarioPorUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
}
?>