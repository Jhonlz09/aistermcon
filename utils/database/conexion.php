<?php

class Conexion
{
    static public function ConexionDB()
    {
        $config = self::ConfigFile(__DIR__ . '/config.txt');

        $opciones = array(PDO::ATTR_EMULATE_PREPARES => false);
        try {
            $conexion = new PDO("pgsql:host={$config['host']}; port={$config['port']}; dbname={$config['dbname']}", $config['username'], $config['password'], $opciones);
            return $conexion;
        } catch (PDOException $e) {
            echo ("No se pudo conectar a la bd, $e");
        }
    }

    static private function ConfigFile($filename)
    {
        $config = array();

        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            list($key, $value) = explode('=', $line, 2);
            $config[trim($key)] = trim($value);
        }

        return $config;
    }
}
