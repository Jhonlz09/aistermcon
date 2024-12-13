<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['s_usuario'])) {
    return;
} else {
    header("Location: /aistermcon");
    exit();
}

// $inactive= 2400; // 5 minutos

// // Comprueba si el usuario ha estado inactivo demasiado tiempo
// if (isset($_SESSION['last_activity'])) {
//     // Calcula el tiempo de inactividad
//     $session_life = time() - $_SESSION['last_activity'];

//     // Si el tiempo de inactividad es mayor que el tiempo permitido, destruye la sesión y redirige
//     if ($session_life > $inactive) {
//         session_unset();
//         echo "<script type='text/javascript'>
//         alert('La sesion ha expirado')
//         const btnlogout = document.getElementById('btnlogout')
//         btnlogout.click();</script>";
//         // session_destroy();
//     }
// }

// // Actualiza la marca de tiempo de la última actividad
// $_SESSION['last_activity'] = time();
?>

