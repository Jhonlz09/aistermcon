<?php
session_start();

// Establecer el tiempo de vida de la sesión a 30 minutos
$session_lifetime = 55555;

// Verificar si la sesión ha expirado
if (isset($_SESSION['last_activity'])) {
    $elapsed_time = time() - $_SESSION['last_activity'];
    if ($elapsed_time > $session_lifetime) {
        echo json_encode(['status' => 'expired']);
        session_unset();
        session_destroy();
        exit();
    }else{
        echo json_encode(['status' => 'active']);
    }
}

// Actualizar la última actividad de la sesión
// $_SESSION['last_activity'] = time();
// echo json_encode(['status' => 'active']);
?>
