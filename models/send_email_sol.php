<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'configuracion.modelo.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if (isset($argv)) {
    // Recuperar los argumentos pasados desde la función principal
    // $id_cliente = $argv[1];
    $logFile = __DIR__ . '/correo_debug.log';

    // 1. Registrar que el script inició y qué datos recibió
    $fecha_log = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$fecha_log] --- INICIANDO ENVÍO EN SEGUNDO PLANO ---\n", FILE_APPEND);
    file_put_contents($logFile, "Argumentos: " . print_r($argv, true) . "\n", FILE_APPEND);

    $descrip = $argv[1];
    $orden = $argv[2];
    $partes = explode('-', $argv[3]);
    $fecha = $partes[2] . '/' . $partes[1] . '/' . $partes[0];
    $cliente = $argv[4];
    $usuario = $argv[5];
    $origen = isset($argv[6]) ? $argv[6] : 'bodega';
    $title = isset($argv[7]) ? $argv[7] : 'Nueva solicitud de despacho';

    // Configurar y enviar el correo
    $mail = new PHPMailer(true);

    try {
        // $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../../');
        // $dotenv->load();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = "bodegaaistermcon@gmail.com"; // Tu correo Gmail
        $mail->Password = "bofy ibxv tkny jxig"; // Contraseña de Gmail (o contraseña de aplicación)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $correos = ModeloConfiguracion::mdlObtenerCorreos(); // Llama al modelo que obtiene los correos
        // Configuración de importancia

        $mail->Priority = 1; // Alta prioridad (1 = Alta, 3 = Normal, 5 = Baja)
        $mail->addCustomHeader('X-Priority', '1'); // Alta prioridad en algunos clientes
        $mail->addCustomHeader('Importance', 'high'); // 
        $mail->setFrom("bodegaaistermcon@gmail.com");

     file_put_contents($logFile, "Correos obtenidos de BD: " . json_encode($correos) . "\n", FILE_APPEND);
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
                $mail->addAddress(trim($correo));
        }
        
        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = $title;
        $mail->Body = "
        <h2>{$title}:</h2>
        <p><strong>Orden:</strong> {$orden}</p>
        <p><strong>Cliente:</strong> {$cliente}</p>
        <p><strong>Fecha:</strong> {$fecha}</p>
        <br>
        <p><strong>Notas:</strong> {$descrip}</p>
        <p><strong>Responsable:</strong> {$usuario}</p>";
        
        // Enviar el correo
        $mail->send();

        file_put_contents($logFile, "[$fecha_log] ÉXITO: Correo enviado correctamente a la orden $orden.\n\n", FILE_APPEND);
    } catch (Exception $e) {
        // 4. Registrar el error exacto de PHPMailer
        file_put_contents($logFile, "[$fecha_log] ERROR CRÍTICO PHPMailer: {$mail->ErrorInfo}\n", FILE_APPEND);
    } catch (\Throwable $th) {
        // 5. Capturar cualquier otro error (ej. clase no encontrada, error de sintaxis)
        file_put_contents($logFile, "[$fecha_log] ERROR FATAL PHP: {$th->getMessage()} en la línea {$th->getLine()}\n", FILE_APPEND);
    }
}
