<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'configuracion.modelo.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if (isset($argv)) {
    // Recuperar los argumentos pasados desde la función principal
    // $id_cliente = $argv[1];
    $descrip = $argv[1];
    $orden = $argv[2];
    $partes = explode('-', $argv[3]);
    $fecha = $partes[2] . '/' . $partes[1] . '/' . $partes[0];
    $cliente = $argv[4];
    $usuario = $argv[5];

    // Configurar y enviar el correo
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'bodegaaistermcon@gmail.com'; // Tu correo Gmail
        $mail->Password = 'bofy ibxv tkny jxig'; // Contraseña de Gmail (o contraseña de aplicación)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $correos = ModeloConfiguracion::mdlObtenerCorreos(); // Llama al modelo que obtiene los correos
        // Configuración de importancia
        $mail->Priority = 1; // Alta prioridad (1 = Alta, 3 = Normal, 5 = Baja)
        $mail->addCustomHeader('X-Priority', '1'); // Alta prioridad en algunos clientes
        $mail->addCustomHeader('Importance', 'high'); // 
        $mail->setFrom('bodegaaistermcon@gmail.com');
     
        foreach ($correos as $correo) {
            $mail->addAddress($correo); // Agrega cada correo como destinatario
        }
        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Nueva orden de trabajo abierta';
        $mail->Body = "
        <h2>Detalles de la nueva orden:</h2>
        <p><strong>Orden:</strong> {$orden}</p>
        <p><strong>Cliente:</strong> {$cliente}</p>
        <p><strong>Descripcion:</strong> {$descrip}</p>
        <p><strong>Fecha:</strong> {$fecha}</p>
        <br>
        <p><strong>Usuario:</strong> {$usuario}</p>";
        
        // Enviar el correo
        $mail->send();
    } catch (Exception $e) {
        error_log("Error al enviar el correo: {$mail->ErrorInfo}");
    }
}
