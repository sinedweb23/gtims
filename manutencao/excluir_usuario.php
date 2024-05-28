<?php
session_start();
include('config1.php');

if ($_SESSION['permissao'] != 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

$query = "DELETE FROM usuarios WHERE id = $id";
if (mysqli_query($conn, $query)) {
    header("Location: usuarios.php");
} else {
    echo "Erro ao excluir usuário.";
}
?>
