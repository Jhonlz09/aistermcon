<?php

require_once "../utils/database/conexion.php";
require_once '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Exception as SpreadException;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ModeloCarga
{
    static public function mdlCargarProductos($file)
    {
        $resultados = [];
        $registrados = 0;
        $categoriaNoRegistrada = [];
        $unidadNoRegistrada = [];
        $ubicacionNoRegistrada = [];
        try {
            $doc = IOFactory::load($file['tmp_name'])->getSheet(0);
            $numeroFilas = $doc->getHighestDataRow();
            $repetidos = 0;
            $vacios = 0;
            $incorrectos = 0;
            $noRegistrados = 0;
            //CICLO FOR PARA REGISTROS DE PRODUCTOS
            for ($i = 4; $i <= $numeroFilas; $i++) {
                try {
                    $codigo = strtoupper(trim($doc->getCell("A" . $i)->getValue()));
                    $descripcion = strtoupper(trim($doc->getCell("B" . $i)->getValue()));
                    $categoria = strtoupper(trim($doc->getCell("C" . $i)->getValue()));
                    $unidad = strtoupper(trim($doc->getCell("D" . $i)->getValue()));
                    $ubicacion = strtoupper(trim($doc->getCell("E" . $i)->getValue()));
                    $stock_min = trim($doc->getCell("F" . $i)->getValue());
                    $stock = trim($doc->getCell("G" . $i)->getValue());

                    $id_categoria = self::mdlObtenerId('tblcategoria', $categoria);
                    if ($id_categoria == null && !empty($categoria)) {
                        $categoriaNoRegistrada[] = $categoria;
                    }
                    $id_unidad = self::mdlObtenerId('tblunidad',   $unidad);
                    if ($id_unidad == null && !empty($unidad)) {
                        $unidadNoRegistrada[] = $unidad;
                    }
                    $id_ubicacion = self::mdlObtenerId('tblubicacion',  $ubicacion);
                    if ($id_ubicacion == null && !empty($ubicacion)) {
                        $ubicacionNoRegistrada[] = $ubicacion;
                    }

                    $a = Conexion::ConexionDB()->prepare("INSERT INTO tblinventario(codigo,descripcion,stock,stock_min,id_categoria,id_unidad,id_percha) VALUES (:cod,:des,:sto,:st_min,:cat,:uni,:ubi)");
                    $a->bindParam(":cod", $codigo, PDO::PARAM_STR);
                    $a->bindParam(":des", $descripcion, PDO::PARAM_STR);
                    $a->bindParam(":sto", $stock, PDO::PARAM_INT);
                    $a->bindParam(":st_min", $stock_min, PDO::PARAM_INT);
                    $a->bindParam(":cat", $id_categoria, PDO::PARAM_INT);
                    $a->bindParam(":uni", $id_unidad, PDO::PARAM_INT);
                    $a->bindParam(":ubi", $id_ubicacion, PDO::PARAM_INT);

                    if ($a->execute()) {
                        $registrados++;
                    }
                } catch (PDOException $e) {
                    if ($e->getCode() == '23505') {
                        $repetidos++;
                    } else if ($e->getCode() == '22P02') {
                        $vacios++;
                    } else {
                        $incorrectos++;
                    }
                    $noRegistrados++;
                }
            }
            $resultados = [
                'registrados' => $registrados,
                'noRegistrados' => $noRegistrados,
                'repetidos' => $repetidos,
                'vacios' => $vacios,
                'incorrectos' => $incorrectos,
                'categoriaNoRegistrada' => $categoriaNoRegistrada,
                'unidadNoRegistrada' => $unidadNoRegistrada,
                'ubicacionNoRegistrada' => $ubicacionNoRegistrada,
            ];
            return $resultados;
        } catch (SpreadException $exception) {

            return $resultados;
        }
    }

    static private function mdlObtenerId($tabla, $valor)
    {
        if (empty($valor)) {
            return '';
        }
        $stmt = Conexion::ConexionDB()->prepare("SELECT id FROM $tabla WHERE nombre = :valor");
        $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
        // Verificar si $stmt->execute() se ejecuta correctamente
        if ($stmt->execute()) {
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar si fetch devolvió un resultado antes de acceder a $campoId
            if ($resultado !== false && isset($resultado['id'])) {
                return $resultado['id'];
            }
        }
        // Si no se cumple ninguna condición anterior, devolver un valor predeterminado
        return null;
    }

    static public function mdlCargarCategorias($file)
    {
        $resultados = [];
        $registrados = 0;
        try {
            $doc = IOFactory::load($file['tmp_name'])->getSheet(0);
            $numeroFilas = $doc->getHighestDataRow();
            $repetidos = 0;
            $vacios = 0;
            $incorrectos = 0;
            $noRegistrados = 0;
            //CICLO FOR PARA REGISTROS DE CATEGORIAS
            for ($i = 4; $i <= $numeroFilas; $i++) {
                try {
                    $categoria = strtoupper(trim($doc->getCell("A" . $i)->getValue()));
                    if (empty($categoria)) {
                        $categoria = null;
                    }

                    $a = Conexion::ConexionDB()->prepare("INSERT INTO tblcategoria(nombre) VALUES (:cat)");
                    $a->bindParam(":cat", $categoria, PDO::PARAM_STR);

                    if ($a->execute()) {
                        $registrados++;
                    }
                } catch (PDOException $e) {
                    if ($e->getCode() == '23505') {
                        $repetidos++;
                    } else if ($e->getCode() == '23502') {
                        $vacios++;
                    } else {
                        $incorrectos++;
                    }
                    $noRegistrados++;
                }
            }
            $resultados = [
                'registrados' => $registrados,
                'noRegistrados' => $noRegistrados,
                'repetidos' => $repetidos,
                'vacios' => $vacios,
                'incorrectos' => $incorrectos,
            ];
            return $resultados;
        } catch (SpreadException $exception) {

            return $resultados;
        }
    }

    static public function mdlCargarUnidades($file)
    {
        $resultados = [];
        $registrados = 0;
        try {
            $doc = IOFactory::load($file['tmp_name'])->getSheet(0);
            $numeroFilas = $doc->getHighestDataRow();
            $repetidos = 0;
            $vacios = 0;
            $incorrectos = 0;
            $noRegistrados = 0;
            //CICLO FOR PARA REGISTROS DE UNIDADES
            for ($i = 4; $i <= $numeroFilas; $i++) {
                try {
                    $unidad = strtoupper(trim($doc->getCell("A" . $i)->getValue()));
                    if (empty($unidad)) {
                        $unidad = null;
                    }

                    $a = Conexion::ConexionDB()->prepare("INSERT INTO tblunidad(nombre) VALUES (:und)");
                    $a->bindParam(":und", $unidad, PDO::PARAM_STR);

                    if ($a->execute()) {
                        $registrados++;
                    }
                } catch (PDOException $e) {
                    if ($e->getCode() == '23505') {
                        $repetidos++;
                    } else if ($e->getCode() == '23502') {
                        $vacios++;
                    } else {
                        $incorrectos++;
                    }
                    $noRegistrados++;
                }
            }
            $resultados = [
                'registrados' => $registrados,
                'noRegistrados' => $noRegistrados,
                'repetidos' => $repetidos,
                'vacios' => $vacios,
                'incorrectos' => $incorrectos,
            ];
            return $resultados;
        } catch (SpreadException $exception) {

            return $resultados;
        }
    }

    static public function mdlCargarUbicacion($file)
    {
        $resultados = [];
        $registrados = 0;
        try {
            $doc = IOFactory::load($file['tmp_name'])->getSheet(0);
            $numeroFilas = $doc->getHighestDataRow();
            $repetidos = 0;
            $vacios = 0;
            $incorrectos = 0;
            $noRegistrados = 0;
            //CICLO FOR PARA REGISTROS DE UBICACION
            for ($i = 4; $i <= $numeroFilas; $i++) {
                try {
                    $ubicacion = strtoupper(trim($doc->getCell("A" . $i)->getValue()));
                    if (empty($ubicacion)) {
                        $ubicacion = null;
                    }

                    $a = Conexion::ConexionDB()->prepare("INSERT INTO tblubicacion(nombre) VALUES (:ubi)");
                    $a->bindParam(":ubi", $ubicacion, PDO::PARAM_STR);

                    if ($a->execute()) {
                        $registrados++;
                    }
                } catch (PDOException $e) {
                    if ($e->getCode() == '23505') {
                        $repetidos++;
                    } else if ($e->getCode() == '23502') {
                        $vacios++;
                    } else {
                        $incorrectos++;
                    }
                    $noRegistrados++;
                }
            }
            $resultados = [
                'registrados' => $registrados,
                'noRegistrados' => $noRegistrados,
                'repetidos' => $repetidos,
                'vacios' => $vacios,
                'incorrectos' => $incorrectos,
            ];
            return $resultados;
        } catch (SpreadException $exception) {

            return $resultados;
        }
    }

    static public function mdlCargarProveedores($file)
    {
        $resultados = [];
        $registrados = 0;
        try {
            $doc = IOFactory::load($file['tmp_name'])->getSheet(0);
            $numeroFilas = $doc->getHighestDataRow();
            $repetidos = 0;
            $vacios = 0;
            $incorrectos = 0;
            $noRegistrados = 0;
            //CICLO FOR PARA REGISTROS DE PROVEEDORES
            for ($i = 4; $i <= $numeroFilas; $i++) {
                try {
                    $proveedor = strtoupper(trim($doc->getCell("A" . $i)->getValue()));
                    if (empty($proveedor)) {
                        $proveedor = null;
                    }

                    $a = Conexion::ConexionDB()->prepare("INSERT INTO tblproveedores(nombre) VALUES (:prov)");
                    $a->bindParam(":prov", $proveedor, PDO::PARAM_STR);

                    if ($a->execute()) {
                        $registrados++;
                    }
                } catch (PDOException $e) {
                    if ($e->getCode() == '23505') {
                        $repetidos++;
                    } else if ($e->getCode() == '23502') {
                        $vacios++;
                    } else {
                        $incorrectos++;
                    }
                    $noRegistrados++;
                }
            }
            $resultados = [
                'registrados' => $registrados,
                'noRegistrados' => $noRegistrados,
                'repetidos' => $repetidos,
                'vacios' => $vacios,
                'incorrectos' => $incorrectos,
            ];
            return $resultados;
        } catch (SpreadException $exception) {

            return $resultados;
        }
    }

    static public function mdlCargarClientes($file)
    {
        $resultados = [];
        $registrados = 0;
        try {
            $doc = IOFactory::load($file['tmp_name'])->getSheet(0);
            $numeroFilas = $doc->getHighestDataRow();
            $repetidos = 0;
            $vacios = 0;
            $incorrectos = 0;
            $noRegistrados = 0;
            //CICLO FOR PARA REGISTROS DE CLIENTES
            for ($i = 4; $i <= $numeroFilas; $i++) {
                try {
                    $clientes = strtoupper(trim($doc->getCell("A" . $i)->getValue()));
                    if (empty($clientes)) {
                        $clientes = null;
                    }

                    $a = Conexion::ConexionDB()->prepare("INSERT INTO tblclientes(nombre) VALUES (:cli)");
                    $a->bindParam(":cli", $clientes, PDO::PARAM_STR);

                    if ($a->execute()) {
                        $registrados++;
                    }
                } catch (PDOException $e) {
                    if ($e->getCode() == '23505') {
                        $repetidos++;
                    } else if ($e->getCode() == '23502') {
                        $vacios++;
                    } else {
                        $incorrectos++;
                    }
                    $noRegistrados++;
                }
            }
            $resultados = [
                'registrados' => $registrados,
                'noRegistrados' => $noRegistrados,
                'repetidos' => $repetidos,
                'vacios' => $vacios,
                'incorrectos' => $incorrectos,
            ];
            return $resultados;
        } catch (SpreadException $exception) {

            return $resultados;
        }
    }

    static public function mdlCargarEmpleados($file)
    {
        $resultados = [];
        $registrados = 0;
        try {
            $doc = IOFactory::load($file['tmp_name'])->getSheet(0);
            $numeroFilas = $doc->getHighestDataRow();
            $repetidos = 0;
            $vacios = 0;
            $incorrectos = 0;
            $noRegistrados = 0;
            //CICLO FOR PARA REGISTROS DE EMPLEADOS
            for ($i = 4; $i <= $numeroFilas; $i++) {
                try {
                    $cedula = strtoupper(trim($doc->getCell("A" . $i)->getValue()));
                    if (empty($cedula)) {
                        $cedula = null;
                    } else if (!(preg_match('/^[0-9]+$/', $cedula))) {
                        $cedula = '1';
                    } 

                    $nombre_empleado = strtoupper(trim($doc->getCell("B" . $i)->getValue()));
                    if (empty($nombre_empleado)) {
                        $nombre_empleado = null;
                    }

                    $conductor = $doc->getCell("C" . $i)->getValue();
                    if (empty($conductor)) {
                        $conductor = false;
                    }else{
                        $conductor = true;
                    }

                    $a = Conexion::ConexionDB()->prepare("INSERT INTO tblempleado(cedula,nombre, conductor) VALUES (:ced,:nom,:con)");
                    $a->bindParam(":ced", $cedula, PDO::PARAM_STR);
                    $a->bindParam(":nom", $nombre_empleado, PDO::PARAM_STR);
                    $a->bindParam(":con", $conductor, PDO::PARAM_BOOL);

                    if ($a->execute()) {
                        $registrados++;
                    }
                } catch (PDOException $e) {
                    if ($e->getCode() == '23505') {
                        $repetidos++;
                    } else if ($e->getCode() == '23502') {
                        $vacios++;
                    } else {
                        $incorrectos++;
                        
                    }
                    $noRegistrados++;
                }
            }
            $resultados = [
                'registrados' => $registrados,
                'noRegistrados' => $noRegistrados,
                'repetidos' => $repetidos,
                'vacios' => $vacios,
                'incorrectos' => $incorrectos,
            ];
            return $resultados;
        } catch (SpreadException $exception) {

            return $resultados;
        }
    }
}
