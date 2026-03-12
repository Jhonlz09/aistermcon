<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'configuracion.modelo.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
use Dotenv\Dotenv;
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
    $origen = isset($argv[6]) ? $argv[6] : 'bodega';

    // Configurar y enviar el correo
    $mail = new PHPMailer(true);

    try {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../../');
        $dotenv->load();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['EMAIL']; // Tu correo Gmail
        $mail->Password = $_ENV['EMAIL_PASS']; // Contraseña de Gmail (o contraseña de aplicación)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $correos = ModeloConfiguracion::mdlObtenerCorreos(); // Llama al modelo que obtiene los correos
        // Configuración de importancia
        $mail->Priority = 1; // Alta prioridad (1 = Alta, 3 = Normal, 5 = Baja)
        $mail->addCustomHeader('X-Priority', '1'); // Alta prioridad en algunos clientes
        $mail->addCustomHeader('Importance', 'high'); // 
        $mail->setFrom($_ENV['EMAIL']);
     
        // Combinamos los correos de supervisor y bodega para enviarles la notificación
        $destinatarios_sup = isset($correos['correos_sup']) && is_array($correos['correos_sup']) ? $correos['correos_sup'] : [];
        $destinatarios_bod = isset($correos['correos_bod']) && is_array($correos['correos_bod']) ? $correos['correos_bod'] : [];
        
        if ($origen === 'supervisor') {
            $destinatarios = $destinatarios_sup;
        } else {
            $destinatarios = $destinatarios_bod;
        }
        
        $destinatarios = array_unique($destinatarios);

        foreach ($destinatarios as $correo) {
            if (!empty(trim($correo))) {
                $mail->addAddress(trim($correo));
            }
        }
        
        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Nueva solicitud de despacho';
        $mail->Body = "
        <h2>Detalles de la nueva solicitud:</h2>
        <p><strong>Orden:</strong> {$orden}</p>
        <p><strong>Cliente:</strong> {$cliente}</p>
        <p><strong>Fecha:</strong> {$fecha}</p>
        <br>
        <p><strong>Responsable:</strong> {$usuario}</p>";
        
        // Enviar el correo
        $mail->send();
    } catch (Exception $e) {
        error_log("Error al enviar el correo: {$mail->ErrorInfo}");
    }
}
