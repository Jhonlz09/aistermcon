<?php
require_once __DIR__ . '/../../vendor/autoload.php';  // Carga el autoloader de Composer desde la raíz del proyecto

use Dotenv\Dotenv;
class Conexion
{
    static public function ConexionDB()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); // Esto sube dos niveles para llegar a la raíz del proyecto
        $dotenv->load();

        // Acceder directamente a las variables de entorno
        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASS'];
        $port = $_ENV['DB_PORT'];

        $opciones = array(PDO::ATTR_EMULATE_PREPARES => false);

        try {
            // Conexión con la base de datos usando las variables de entorno
            $conexion = new PDO("pgsql:host=$host; port=$port; dbname=$dbname", $username, $password, $opciones);
            return $conexion;
        } catch (PDOException $e) {
            echo ("No se pudo conectar a la bd, {$e->getMessage()}");
        }
    }
}
