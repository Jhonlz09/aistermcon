<?php

require_once "../utils/database/conexion.php";

class ModeloSalidas
{
    static public function mdlListarSalidas($anio, $mes)
    {
        try {
            $consulta = "SELECT s.id, i.codigo, 
                i.descripcion,  u.nombre AS unidad,
                o.nombre || ' '|| c.nombre  || ' - '|| LPAD(b.nro_guia::TEXT, 9, '0') || ' - '|| TO_CHAR(b.fecha, 'DD/MM/YYYY HH24:MI') AS grupo, 
                s.cantidad_salida,s.retorno, o.nombre as orden, c.nombre as cliente,
                LPAD(b.nro_guia::TEXT, 9, '0') as boleta, b.id as id_boleta, 
                o.id as id_orden, c.id as id_cliente, TO_CHAR(b.fecha, 'YYYY-MM-DD') AS fecha,
                b.id_conductor,b.id_despachado, b.id_responsable,b.nro_guia,b.motivo,
                ROW_NUMBER() OVER (PARTITION BY b.id ORDER BY s.id) AS fila, s.fabricado, b.fab, b.tras, TO_CHAR(b.fecha_retorno, 'YYYY-MM-DD') AS fecha_retorno
                FROM 
                tblsalidas s
                JOIN tblinventario i ON s.id_producto = i.id
                JOIN tblboleta b ON s.id_boleta = b.id
                JOIN tblorden o ON b.id_orden = o.id
                JOIN tblclientes c ON c.id = o.id_cliente
                JOIN tblunidad u ON i.id_unidad = u.id
                WHERE 
                    EXTRACT(YEAR FROM b.fecha) = :anio 
                    AND s.id_producto_fab IS NULL ";
            if ($mes !== '') {
                $consulta .= "AND EXTRACT(MONTH FROM b.fecha) = :mes ";
            }
            $consulta .= "ORDER BY b.fecha DESC, s.id";

            $l = Conexion::ConexionDB()->prepare($consulta);
            $l->bindParam(":anio", $anio, PDO::PARAM_INT);
            if ($mes !== '') {
                $l->bindParam(":mes", $mes, PDO::PARAM_INT);
            }
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarSalida($boleta, $codigo)
    {
        try {
            // Obtener la conexión a la base de datos
            $db = Conexion::ConexionDB();

            // Obtener el id_producto a partir del código
            $stmt_get_product_id = $db->prepare("SELECT id AS id_producto FROM tblinventario WHERE codigo = :codigo");
            $stmt_get_product_id->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $stmt_get_product_id->execute();
            $id_producto = $stmt_get_product_id->fetchColumn();

            if ($id_producto === false) {
                return array(
                    'status' => 'danger',
                    'm' => 'El código del producto no existe.'
                );
            }

            // Verificar el stock disponible del producto
            $stmt_check_stock = $db->prepare("SELECT (stock - stock_mal) as stock, descripcion FROM tblinventario WHERE id = :id_producto");
            $stmt_check_stock->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
            $stmt_check_stock->execute();
            $resultado_stock = $stmt_check_stock->fetch(PDO::FETCH_ASSOC);

            if (!$resultado_stock || $resultado_stock['stock'] < 1) {
                return array(
                    'status' => 'danger',
                    'm' => 'No hay suficiente stock disponible para el producto ' . $resultado_stock['descripcion']
                );
            }

            // Verificar si el id_producto ya está relacionado con el id_boleta en tblsalidas
            $stmt_check = $db->prepare("SELECT COUNT(*) FROM tblsalidas WHERE id_producto = :id_producto AND id_boleta = :boleta");
            $stmt_check->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
            $stmt_check->bindParam(":boleta", $boleta, PDO::PARAM_INT);
            $stmt_check->execute();
            $count = $stmt_check->fetchColumn();

            if ($count > 0) {
                return array(
                    'status' => 'danger',
                    'm' => 'El producto ya existe en la guía'
                );
            }



            // Preparar la consulta para insertar en tblsalidas
            $stmt_insert = $db->prepare("INSERT INTO tblsalidas(cantidad_salida,id_boleta, id_producto) VALUES (1,:boleta, :id_producto)");
            $stmt_insert->bindParam(":boleta", $boleta, PDO::PARAM_INT);
            $stmt_insert->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
            $stmt_insert->execute();

            return array(
                'status' => 'success',
                'm' => 'El producto se agregó a la guía correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo agregar el producto: ' . $e->getMessage()
            );
        }
    }

    // public static function mdlEliminarSalida($id)
    // {
    //     try {
    //         $pdo = Conexion::ConexionDB();

    //         // Preparar la consulta para eliminar de tblboleta (esto eliminará también las entradas relacionadas en tblsalidas)
    //         $e = $pdo->prepare("DELETE FROM tblboleta WHERE id = :id");
    //         $e->bindParam(":id", $id, PDO::PARAM_INT);
    //         $e->execute();
    //         return array(
    //             'status' => 'success',
    //             'm' => 'Se eliminó la guía de remision correctamente.'
    //         );
    //     } catch (PDOException $e) {
    //         return array(
    //             'status' => 'danger',
    //             'm' => 'No se pudo eliminar la guia: ' . $e->getMessage()
    //         );
    //     }
    // }

    public static function mdlEliminarSalida($id)
    {
        try {
            $pdo = Conexion::ConexionDB();

            // 1. Consultar los nombres de las imágenes asociadas a la boleta
            $e = $pdo->prepare("SELECT nombre_imagen FROM tblimg_salida WHERE id_boleta = :id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            $imagenes = $e->fetchAll(PDO::FETCH_ASSOC);

            // 2. Eliminar las imágenes físicas del servidor
            $Dir = __DIR__ . "/../../guia_img/"; // Directorio donde están las imágenes
            foreach ($imagenes as $imagen) {
                // Suponiendo que 'nombre_imagen' contiene el nombre completo del archivo
                $nombreImagen = $imagen['nombre_imagen'];
                if (file_exists($Dir . $nombreImagen)) {
                    unlink($Dir . $nombreImagen); // Elimina el archivo físico
                }
            }

            // 3. Eliminar las entradas de la base de datos
            $e = $pdo->prepare("DELETE FROM tblboleta WHERE id = :id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();

            return array(
                'status' => 'success',
                'm' => 'Se eliminó la guia de remision y las imágenes asociadas correctamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar la boleta: ' . $e->getMessage()
            );
        }
    }



    static public function mdlBuscarBoletaPDF($id_boleta)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT s.id, i.codigo, s.cantidad_salida, u.nombre AS unidad, i.descripcion, s.cantidad_salida AS salidas, 
            s.retorno, LPAD(b.nro_guia::TEXT, 9, '0') AS id_boleta
                FROM tblsalidas s
                JOIN tblinventario i ON s.id_producto = i.id
                JOIN tblboleta b ON s.id_boleta = b.id 
                JOIN tblorden o ON b.id_orden = o.id
                JOIN tblunidad u ON i.id_unidad = u.id
                WHERE b.id = :id
                AND s.id_producto_fab IS NULL");

            $l->bindParam(":id", $id_boleta, PDO::PARAM_INT);
            $l->execute();

            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }


    // static public function mdlObtenerImgBoleta($id_boleta)
    // {
    //     try {
    //         $l = Conexion::ConexionDB()->prepare("SELECT nombre_imagen
    //         FROM tblimg_salida 
    //         WHERE id_boleta = :id");

    //         $l->bindParam(":id", $id_boleta, PDO::PARAM_INT);
    //         $l->execute();
    //         $imagenes = $l->fetchAll(PDO::FETCH_ASSOC);

    //         if ($imagenes) {
    //             return json_encode(['imagenes' => $imagenes]);
    //         } else {
    //             return json_encode(['imagenes' => []]); // Retorna un arreglo vacío si no hay imágenes
    //         }
    //     } catch (PDOException $e) {
    //         return "Error en la consulta: " . $e->getMessage();
    //     }
    // }

    static public function mdlObtenerImgBoleta($id_boleta)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT nombre_imagen as nombre_file
            FROM tblimg_salida 
            WHERE id_boleta = :id");

            $l->bindParam(":id", $id_boleta, PDO::PARAM_INT);
            $l->execute();
            $files = $l->fetchAll(PDO::FETCH_ASSOC);

            return $files ?: []; // Retorna un arreglo vacío si no hay resultados
        } catch (PDOException $e) {
            // Retornar un mensaje de error descriptivo
            return "Error en la consulta: " . $e->getMessage();
        }
    }



    static public function mdlEliminarImgBoleta($ruta)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("DELETE FROM tblimg_salida
            WHERE nombre_imagen = :id");

            $l->bindParam(":id", $ruta, PDO::PARAM_INT);
            if ($l->execute()) {
                $uploadDir = __DIR__ . "/../../guia_img/"; // Directorio donde están las imágenes
                $filePath = $uploadDir . $ruta;

                if (file_exists($filePath)) {
                    unlink($filePath); // Eliminar archivo
                }
            };
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlBuscarBoleta($id_boleta)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT s.id,
            i.descripcion, u.nombre AS unidad, s.cantidad_salida as salidas, s.retorno, LPAD(b.id::TEXT, 7, '0') as id_boleta, 
            i.codigo, s.isentrada, u.id as id_unidad
            FROM tblsalidas s
            JOIN tblinventario i ON s.id_producto = i.id
            JOIN tblboleta b ON s.id_boleta = b.id 
            JOIN tblorden o ON b.id_orden = o.id
            JOIN tblunidad u ON i.id_unidad = u.id
                WHERE b.id=:id
            ORDER BY b.fecha ASC, s.id");
            $l->bindParam(":id", $id_boleta, PDO::PARAM_INT);
            $l->execute();

            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlBuscarBoletaFab($id_boleta)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT s.id,i.descripcion, u.nombre AS unidad,
            s.cantidad_salida as salidas, s.retorno, LPAD(b.id::TEXT, 7, '0') as id_boleta,
            i.codigo, s.isentrada, u.id as id_unidad, i.id as id_fab
                FROM tblsalidas s
                JOIN tblinventario i ON s.id_producto = i.id
                JOIN tblboleta b ON s.id_boleta = b.id 
                JOIN tblorden o ON b.id_orden = o.id
                JOIN tblunidad u ON i.id_unidad = u.id
                    WHERE b.id=:id AND s.fabricado = true
                ORDER BY b.fecha ASC, s.id");
            $l->bindParam(":id", $id_boleta, PDO::PARAM_INT);
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlDetalleBoleta($id_boleta)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT s.id || ',' || b.id as id , i.codigo, s.cantidad_salida,u.nombre AS unidad,
            i.descripcion, s.cantidad_salida as salidas, s.retorno
            FROM tblsalidas s
            JOIN tblinventario i ON s.id_producto = i.id
            JOIN tblboleta b ON s.id_boleta = b.id 
            JOIN tblorden o ON b.id_orden = o.id
            JOIN tblunidad u ON i.id_unidad = u.id
                WHERE b.id= :id
            ORDER BY b.fecha ASC, s.id;");
            $l->bindParam(":id", $id_boleta, PDO::PARAM_INT);
            $l->execute();

            $numero_filas = $l->rowCount();
            $result = $l->fetchAll();

            $data = array(
                'draw' => intval($_POST['draw']),
                'recordsTotal' => $numero_filas,
                'recordsFiltered' => $numero_filas,
                'data' => $result
            );

            return $data;
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlBuscarOrdenFecha($id_orden, $fecha = null)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT s.id,s.cantidad_salida, 
            u.nombre AS unidad, i.descripcion, s.cantidad_salida as salidas, s.retorno
                FROM tblsalidas s
                JOIN tblinventario i ON s.id_producto = i.id
                JOIN tblboleta b ON s.id_boleta = b.id 
                JOIN tblorden o ON b.id_orden = o.id
                JOIN tblunidad u ON i.id_unidad = u.id
		        WHERE o.id = :id AND DATE(b.fecha) = :fecha
                ORDER BY b.fecha ASC");
            $l->bindParam(":id", $id_orden, PDO::PARAM_INT);
            $l->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            $l->execute();

            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlBuscarDetalleBoleta($id_boleta)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT b.id,
            TO_CHAR(b.fecha, 'DD/MM/YYYY') AS fecha,
            TO_CHAR(b.fecha_retorno, 'DD/MM/YYYY') AS fecha_retorno,
            o.nombre AS orden,c.nombre AS cliente,COALESCE(c.ruc, e.cedula) AS ruc,c.direccion AS direccion_cliente,
            c.telefono AS cliente_telefono,b.motivo,e_despachado.nombre AS despachado,
            e_responsable.nombre AS responsable,e.apellido || ' ' || e.nombre AS conductor,
            e.cedula AS cedula_conductor,e.telefono AS telefono_conductor,p.nombre AS placa,
            LPAD(b.nro_guia::TEXT, 9, '0') AS id_boleta, b.fab
                FROM tblboleta b
                JOIN tblorden o ON b.id_orden = o.id
                JOIN tblclientes c ON c.id = o.id_cliente
                LEFT JOIN tblempleado_placa e_conductor ON e_conductor.id = b.id_conductor
                LEFT JOIN tblempleado e_despachado ON e_despachado.id = b.id_despachado
                LEFT JOIN tblempleado e_responsable ON e_responsable.id = b.id_responsable
                LEFT JOIN tblempleado e ON e.id = e_conductor.id_empleado
                LEFT JOIN tblplaca p ON p.id = e_conductor.id_placa
                WHERE b.id = :id;");
            $l->bindParam(":id", $id_boleta, PDO::PARAM_INT);
            $l->execute();

            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlBoletaConfig()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT emisor, ruc, matriz, correo1, correo2, telefonos FROM tblconfiguracion");
            $l->execute();

            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }
}
