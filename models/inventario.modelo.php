<?php

require_once "../utils/database/conexion.php";

class ModeloInventario
{
    public static function mdlListarInventario()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT i.id, i.codigo, i.descripcion, c.nombre as categoria, u.nombre as unidad, p.nombre as percha, i.stock_mal, i.stock, '' as acciones, 
            i.stock_min,c.id as categoria_id,u.id as unidad_id,p.id as percha_id  
                FROM tblinventario i 
                JOIN tblcategoria c on c.id= i.id_categoria
                JOIN tblunidad u on u.id= i.id_unidad
                JOIN tblubicacion p on p.id= i.id_percha
                WHERE i.estado=true
                ORDER BY id ASC");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    public static function mdlAgregarInventario($cod, $des, $sto, $st_min, $st_mal, $cat, $uni, $ubi)
    {
        try {
            $a = Conexion::ConexionDB()->prepare("INSERT INTO tblinventario(codigo,descripcion,stock,stock_min,stock_mal,id_categoria,id_unidad,id_percha) VALUES (:cod,:des,:sto,:st_min,:st_mal,:cat,:uni,:ubi)");
            $a->bindParam(":cod", $cod, PDO::PARAM_STR);
            $a->bindParam(":des", $des, PDO::PARAM_STR);
            $a->bindParam(":sto", $sto, PDO::PARAM_INT);
            $a->bindParam(":st_min", $st_min, PDO::PARAM_INT);
            $a->bindParam(":st_mal", $st_mal, PDO::PARAM_INT);
            $a->bindParam(":cat", $cat, PDO::PARAM_INT);
            $a->bindParam(":uni", $uni, PDO::PARAM_INT);
            $a->bindParam(":ubi", $ubi, PDO::PARAM_INT);
            $a->execute();
            return array(
                'status' => 'success',
                'm' => 'El producto se agregó correctamente'
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo agregar el producto debido a que ya existe un producto con el mismo codigo'
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo agregar el producto: ' . $e->getMessage()
                );
            }
        }
    }

    public static function mdlEditarInventario($id, $codigo, $des, $sto, $st_min, $st_mal, $cat, $uni, $ubi)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("UPDATE tblinventario SET codigo=:codigo, descripcion=:des, stock=:sto, stock_min=:st_min, stock_mal=:st_mal, id_categoria=:cat, id_unidad=:uni, id_percha=:ubi WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $e->bindParam(":des", $des, PDO::PARAM_STR);
            $e->bindParam(":sto", $sto, PDO::PARAM_STR);
            $e->bindParam(":st_min", $st_min, PDO::PARAM_INT);
            $e->bindParam(":st_mal", $st_mal, PDO::PARAM_INT);
            $e->bindParam(":cat", $cat, PDO::PARAM_INT);
            $e->bindParam(":uni", $uni, PDO::PARAM_INT);
            $e->bindParam(":ubi", $ubi, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'El producto se editó correctamente'
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo editar el producto debido a que ya existe un producto con el mismo codigo'
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo editar el producto: ' . $e->getMessage()
                );
            }
        }
    }

    public static function mdlEliminarInventario($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("UPDATE tblinventario SET estado=false WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'El producto se eliminó correctamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar el producto: ' . $e->getMessage()
            );
        }
    }

    public static function mdlBuscarProductos()
    {
        try {
            $e = Conexion::ConexionDB()->prepare("SELECT i.id, i.codigo || ' - ' || i.descripcion as descripcion, (i.stock - i.stock_mal) AS cantidad
            FROM tblinventario i where i.estado =true order by i.descripcion");
            $e->execute();

            return $e->fetchAll();
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo obtener el producto: ' . $e->getMessage()
            );
        }
    }

    public static function mdlBuscarId($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("SELECT i.id, i.descripcion,'1' as cantidad, u.nombre, '0.00' as precio, '' as acciones
            FROM tblinventario i 
            JOIN tblunidad u on i.id_unidad = u.id
            WHERE i.id = :id");

            $e->bindParam(":id", $id, PDO::PARAM_STR);
            $e->execute();
            return $e->fetch(PDO::FETCH_OBJ);

        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo obtener el producto: ' . $e->getMessage()
            );
        }
    }
}
