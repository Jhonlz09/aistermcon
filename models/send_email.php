<?php
require_once __DIR__ . '/../vendor/autoload.php';


use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// $dotenv = Dotenv::createImmutable(__DIR__);
// $dotenv->load();

if (isset($argv)) {
    // Recuperar los argumentos pasados desde la función principal
    // $id_cliente = $argv[1];
    $nombre = $argv[1];
    $orden = $argv[2];
    // $ruta = $argv[4];
    $fecha = $argv[3];

    // Configurar y enviar el correo
    $mail = new PHPMailer(true);

    // try {
    //     // Configuración del servidor SMTP
    //     $mail->isSMTP();
    //     $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
    //     $mail->SMTPAuth = true;
    //     $mail->Username = 'jdleon5@tes.edu.ec'; // Tu correo Gmail
    //     $mail->Password = 'meteoro123'; // Contraseña de Gmail (o contraseña de aplicación)
    //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    //     $mail->Port = 587;

        

    //     // Configuración de importancia
    //     $mail->Priority = 1; // Alta prioridad (1 = Alta, 3 = Normal, 5 = Baja)
    //     $mail->addCustomHeader('X-Priority', '1'); // Alta prioridad en algunos clientes
    //     $mail->addCustomHeader('Importance', 'high'); // C

    //     // Configuración del remitente y destinatario
    //     $mail->setFrom('jdleon5@tes.edu.ec');
    //     // $mail->addAddress('jhonleon2001@gmail.com'); // Cambia por el correo del cliente o destinatario
    //     $mail->addAddress('operaciones@aistermcon.com'); // Cambia por el correo del cliente o destinatario
    //     $mail->addAddress('imunoz@aistermcon.com'); // Cambia por el correo del cliente o destinatario

    //     // $mail->addAddress('@gmail.com');

    //     // Contenido del correo
    //     $mail->isHTML(true);
    //     $mail->Subject = 'Nueva orden de trabajo abierta';
    //     $mail->Body = "
    //     <h2>Detalles de la nueva orden:</h2>
    //     <p><strong>Orden:</strong> {$orden}</p>
    //     <p><strong>Descripcion:</strong> {$nombre}</p>
    //     <p><strong>Fecha:</strong> {$fecha}</p>";
    //     // Enviar el correo
    //     $mail->send();
    // } catch (Exception $e) {
    //     error_log("Error al enviar el correo: {$mail->ErrorInfo}");
    // }
}
