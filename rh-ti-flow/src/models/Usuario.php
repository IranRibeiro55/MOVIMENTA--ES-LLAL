<?php
// src/models/Usuario.php
require_once __DIR__ . '/../config/database.php';

class Usuario
{
    private $conn;

    public function __construct()
    {
        $this->conn = conectarBanco(); // função dentro de database.php
    }

    public function buscarPorUsuario($usuario)
    {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE usuario = ? AND ativo = 1 LIMIT 1");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
