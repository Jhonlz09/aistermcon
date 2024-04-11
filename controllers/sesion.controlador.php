<?php

require_once "../models/sesion.modelo.php";

class SesionControlador
{
    public $usuario, $password;
    public function login()
    {
        $data = SesionModelo::mdlIniciarSesion($this->usuario, $this->password);
        echo $data;
    }
}

if (isset($_POST['username'])) { 
    $u = new SesionControlador();
    $u->usuario = $_POST["username"];
    $u->password = $_POST["password"];
    $u->login();
}  