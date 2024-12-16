<?php

require_once "../utils/database/conexion.php";

class ModeloCotizacion
{
    static public function mdlListarCotizacion($anio)
    {
        try {
            $consulta = "SELECT c.id, c.num_co, p.nombre, c.motivo, TO_CHAR(c.fecha, 'DD/MM/YYYY') AS fecha, c.estado_solicitud, c.estado_orden, c.ruta_pdf, '' as acciones, 
            c.id_proveedor, c.id, c.subtotal, c.impuesto, c.iva, c.total, c.otros, c.estado_anu, c.comprador
                FROM tblcotizacion c
                JOIN tblproveedores p ON p.id = c.id_proveedor 
                WHERE EXTRACT(YEAR FROM c.fecha) = :anio ORDER BY c.num_co DESC";
            $l = Conexion::ConexionDB()->prepare($consulta);
            $l->bindParam(":anio", $anio, PDO::PARAM_INT);
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlConsultarCotizacion($num_co)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("SELECT c.id,cantidad, c.id_unidad, c.descripcion, c.precio_final::MONEY, c.precio_uni, '' as acciones, u.nombre FROM tbldetalle_cotizacion c
            JOIN tblunidad u ON c.id_unidad= u.id  WHERE c.id_cotizacion=:id");
            $a->bindParam(":id", $num_co, PDO::PARAM_INT);
            $a->execute();

            return $a->fetchAll();
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo agregar el proveedor: ' . $e->getMessage()
            );
        }
    }

    static public function mdlConsultarCotizacionPDF($num_co)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("SELECT c.num_co, p.nombre as proveedor, p.direccion, c.motivo, TO_CHAR(c.fecha, 'DD/MM/YYYY') AS fecha, c.estado_solicitud, estado_orden, '' as acciones, 
            c.id_proveedor, c.id, c.comprador, p.ruc, p.telefono, c.subtotal::MONEY, c.impuesto::MONEY, c.iva, c.total::MONEY
                FROM tblcotizacion c
                JOIN tblproveedores p ON p.id = c.id_proveedor 
				WHERE c.id=:id");
            $a->bindParam(":id", $num_co, PDO::PARAM_INT);
            $a->execute();

            return $a->fetchAll();
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo agregar el proveedor: ' . $e->getMessage()
            );
        }
    }

    static public function mdlAgregarCotizacion($nombre, $dir, $correo, $tel)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("INSERT INTO tblproveedores(nombre,direccion,correo,telefono) VALUES (:nombre,:dir, :correo, :tel)");
            $a->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $a->bindParam(":dir", $dir, PDO::PARAM_STR);
            $a->bindParam(":correo", $correo, PDO::PARAM_STR);
            $a->bindParam(":tel", $tel, PDO::PARAM_STR);
            $a->execute();

            return array(
                'status' => 'success',
                'm' => 'La solic se agregó correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo agregar el proveedor: ' . $e->getMessage()
            );
        }
    }

    static public function mdlAgregarFilasCotizacion($filas, $id_cotizacion)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();

            $consulta = "INSERT INTO tbldetalle_cotizacion(cantidad, id_unidad, descripcion, precio_final, id_cotizacion)
            VALUES (null, 1, '', null, :id)";
            $a = $conexion->prepare($consulta);

            // Repetir la consulta por la cantidad de filas
            for ($i = 0; $i < $filas; $i++) {
                $a->bindParam(":id", $id_cotizacion, PDO::PARAM_INT);
                $a->execute();
            }

            $conexion->commit();

            return array(
                'status' => 'success',
                'm' => 'Las fila(s) se agregaron correctamente.'
            );
        } catch (PDOException $e) {
            $conexion->rollBack();
            return array(
                'status' => 'danger',
                'm' => 'No se pudieron agregar las filas: ' . $e->getMessage()
            );
        }
    }


    static public function mdlEditarCotizacion($num_co)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("SELECT c.id,cantidad, c.id_unidad, c.descripcion, c.precio_final::NUMERIC, c.precio_uni, '' as acciones, u.nombre FROM tbldetalle_cotizacion c
            JOIN tblunidad u ON c.id_unidad= u.id  WHERE c.id_cotizacion=:id ORDER BY c.id");
            $a->bindParam(":id", $num_co, PDO::PARAM_INT);
            $a->execute();

            return $a->fetchAll();
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo consultar la solic. de compra: ' . $e->getMessage()
            );
        }
    }

    // static public function mdlActualizarFilasCotizacion($filas)
    // {
    //     try {
    //         $conexion = Conexion::ConexionDB();
    //         $a = $conexion->prepare("");
    //         $a->bindParam(":id", $, PDO::PARAM_INT);
    //         $a->execute();

    //         return array(
    //             'status' => 'success',
    //             'm' => 'La solic. de compra se editó correctamente'
    //         );

    //         return $a->fetchAll();

    //     } catch (PDOException $e) {
    //         return array(
    //             'status' => 'danger',
    //             'm' => 'No se pudo editar la solic. de compra: ' . $e->getMessage()
    //         );
    //     }
    // }


    // static public function mdlActualizarFilasCotizacion($filas)
    // {
    //     try {
    //         $conexion = Conexion::ConexionDB();
    //         $conexion->beginTransaction();
    //         $filas = json_decode($filas, true);  // Decodifica JSON a un array asociativo

    //         foreach ($filas as $fila) {
    //             $consulta = "UPDATE tbldetalle_cotizacion
    //                     SET cantidad = :cantidad,
    //                         id_unidad = :id_unidad,
    //                         descripcion = :descripcion,
    //                         precio_final = :precio_final
    //                     WHERE id = :id";

    //             // Preparar la sentencia
    //             $a = $conexion->prepare($consulta);
    //             $a->bindParam(":id", $fila['id'], PDO::PARAM_INT);
    //             $a->bindParam(":cantidad", $fila['cantidad'], PDO::PARAM_STR);
    //             $a->bindParam(":id_unidad", $fila['id_unidad'], PDO::PARAM_INT);
    //             $a->bindParam(":descripcion", $fila['descripcion'], PDO::PARAM_STR);
    //             $a->bindParam(":precio_final", $fila['precio_final'], PDO::PARAM_STR);
    //             $a->execute();
    //         }

    //         // Si todo salió bien, confirmar la transacción
    //         $conexion->commit();

    //         return array(
    //             'status' => 'success',
    //             'm' => 'La solicitud de compra se editó correctamente.'
    //         );
    //     } catch (PDOException $e) {
    //         // Si hay un error, deshacer la transacción
    //         $conexion->rollBack();
    //         return array(
    //             'status' => 'danger',
    //             'm' => 'No se pudo editar la solicitud de compra: ' . $e->getMessage()
    //         );
    //     }
    // }


    // static public function mdlActualizarFilasCotizacion($filas, $id_cotizacion, $subtotal, $total, $iva, $impuestos, $isPrecio = false)
    // {
    //     try {
    //         $conexion = Conexion::ConexionDB();
    //         $conexion->beginTransaction();
    //         $filas = json_decode($filas, true);  // Decodifica JSON a un array asociativo

    //         // Actualizar las filas en tbldetalle_cotizacion
    //         foreach ($filas as $fila) {
    //             $consulta = "UPDATE tbldetalle_cotizacion
    //                 SET cantidad = :cantidad,
    //                     id_unidad = :id_unidad,
    //                     descripcion = :descripcion,
    //                     precio_final = :precio_final
    //                 WHERE id = :id";

    //             // Preparar la sentencia
    //             $a = $conexion->prepare($consulta);
    //             $a->bindParam(":id", $fila['id'], PDO::PARAM_INT);
    //             $a->bindParam(":cantidad", $fila['cantidad'], PDO::PARAM_STR);
    //             $a->bindParam(":id_unidad", $fila['id_unidad'], PDO::PARAM_INT);
    //             $a->bindParam(":descripcion", $fila['descripcion'], PDO::PARAM_STR);
    //             $a->bindParam(":precio_final", $fila['precio_final'], PDO::PARAM_STR);
    //             $a->execute();
    //         }

    //         // Actualizar el campo 'motivo' de la cotización específica
    //         $consultaMotivo = "UPDATE tblcotizacion
    //         SET motivo = CONCAT(
    //             (
    //                 SELECT STRING_AGG(descripcion, ', ' ORDER BY id)
    //                 FROM (
    //                     SELECT descripcion
    //                     FROM tbldetalle_cotizacion
    //                     WHERE id_cotizacion = :id_cotizacion
    //                     ORDER BY id
    //                     LIMIT 5
    //                 ) AS subquery
    //             ),
    //             CASE 
    //                 WHEN (SELECT COUNT(*) FROM tbldetalle_cotizacion WHERE id_cotizacion = :id_cotizacion) > 5
    //                 THEN '...'
    //                 ELSE ''
    //             END
    //         )
    //         WHERE id = :id_cotizacion;
    //     ";

    //         // Preparar y ejecutar la consulta de actualización del campo 'motivo'
    //         $aMotivo = $conexion->prepare($consultaMotivo);
    //         $aMotivo->bindParam(":id_cotizacion", $id_cotizacion, PDO::PARAM_INT);
    //         $aMotivo->execute();

    //         // Confirmar la transacción
    //         // $conexion->commit();

    //         // Calcular y actualizar subtotal, impuestos, iva y total
    //         if ($isPrecio) {
    //             $consultaCalculo = "UPDATE tblcotizacion SET 
    //                 subtotal = :subtotal,
    //                 impuesto = :impuestos, -- IVA al 16%
    //                 iva = :iva, -- Igual que impuestos para este ejemplo
    //                 total = :total
    //             WHERE id = :id_cotizacion;";

    //             $aCalculo = $conexion->prepare($consultaCalculo);
    //             $aCalculo->bindParam(":id_cotizacion", $id_cotizacion, PDO::PARAM_INT);
    //             $aCalculo->bindParam(":subtotal", $subtotal, PDO::PARAM_STR);
    //             $aCalculo->bindParam(":total", $total, PDO::PARAM_STR);
    //             $aCalculo->bindParam(":iva", $iva, PDO::PARAM_STR);
    //             $aCalculo->bindParam(":impuestos", $impuestos, PDO::PARAM_STR);
    //             $aCalculo->execute();
    //         }


    //         // Confirmar la transacción
    //         $conexion->commit();
    //         return array(
    //             'status' => 'success',
    //             'm' => 'La solicitud de compra se editó correctamente.'
    //         );
    //     } catch (PDOException $e) {
    //         // Si hay un error, deshacer la transacción
    //         $conexion->rollBack();
    //         return array(
    //             'status' => 'danger',
    //             'm' => 'No se pudo editar la solicitud de compra: ' . $e->getMessage()
    //         );
    //     }
    // }

    static public function mdlActualizarFilasCotizacion($filas, $id_cotizacion, $subtotal, $total, $iva, $impuestos, $isPrecio = false)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();
            $filas = json_decode($filas, true); // Decodifica JSON a un array asociativo
            $consulta = "UPDATE tbldetalle_cotizacion
            SET cantidad = :cantidad,
                id_unidad = :id_unidad,
                descripcion = :descripcion,
                precio_final = :precio_final
            WHERE id = :id";
            // Actualizar las filas en tbldetalle_cotizacion
            foreach ($filas as $fila) {
                $a = $conexion->prepare($consulta);
                $a->bindParam(":id", $fila['id'], PDO::PARAM_INT);
                $a->bindParam(":cantidad", $fila['cantidad'], PDO::PARAM_STR);
                $a->bindParam(":id_unidad", $fila['id_unidad'], PDO::PARAM_INT);
                $a->bindParam(":descripcion", $fila['descripcion'], PDO::PARAM_STR);
                $a->bindParam(":precio_final", $fila['precio_final'], PDO::PARAM_STR);
                $a->execute();
            }

            // Actualizar el campo 'motivo' de la cotización específica
            $consultaMotivo = "UPDATE tblcotizacion
        SET motivo = CONCAT(
            (
                SELECT STRING_AGG(descripcion, ', ' ORDER BY id)
                FROM (
                    SELECT descripcion
                    FROM tbldetalle_cotizacion
                    WHERE id_cotizacion = :id_cotizacion
                    ORDER BY id
                    LIMIT 5
                ) AS subquery
            ),
            CASE 
                WHEN (SELECT COUNT(*) FROM tbldetalle_cotizacion WHERE id_cotizacion = :id_cotizacion) > 5
                THEN '...'
                ELSE ''
            END
        )
        WHERE id = :id_cotizacion;";

            $aMotivo = $conexion->prepare($consultaMotivo);
            $aMotivo->bindParam(":id_cotizacion", $id_cotizacion, PDO::PARAM_INT);
            $aMotivo->execute();

            // Calcular y actualizar subtotal, impuestos, IVA y total si es necesario
            if ($isPrecio) {
                $resultado = self::mdlActualizarTotalesCotizacion($conexion, $id_cotizacion, $subtotal, $total, $iva, $impuestos);
                if ($resultado['status'] !== 'success') {
                    throw new Exception($resultado['m']);
                }
            }

            $conexion->commit();

            return array(
                'status' => 'success',
                'm' => 'La solicitud de compra se editó correctamente.'
            );
        } catch (Exception $e) {
            $conexion->rollBack();
            return array(
                'status' => 'danger',
                'm' => 'No se pudo editar la solicitud de compra: ' . $e->getMessage()
            );
        }
    }




    static public function mdlActualizarTotalesCotizacion($conexion, $id_cotizacion, $subtotal, $total, $iva, $impuestos)
    {
        try {
            if ($conexion === null) {
                $conexion = Conexion::ConexionDB();
            }
            $consulta = "UPDATE tblcotizacion SET 
            subtotal = :subtotal,
            impuesto = :impuestos,
            iva = :iva,
            total = :total
        WHERE id = :id_cotizacion";
            $aC = $conexion->prepare($consulta);
            $aC->bindParam(":id_cotizacion", $id_cotizacion, PDO::PARAM_INT);
            $aC->bindParam(":subtotal", $subtotal, PDO::PARAM_STR);
            $aC->bindParam(":total", $total, PDO::PARAM_STR);
            $aC->bindParam(":iva", $iva, PDO::PARAM_STR);
            $aC->bindParam(":impuestos", $impuestos, PDO::PARAM_STR);
            $aC->execute();

            return array(
                'status' => 'success',
                'm' => 'Valores actualizados correctamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudieron actualizar los valores: ' . $e->getMessage()
            );
        }
    }

    static public function mdlEliminarFilasIds($id_cotizacion)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();

            // 1. Establecer el campo 'motivo' de la tabla 'tblcotizacion' a vacío
            $consultaMotivo = "UPDATE tblcotizacion SET motivo = '', subtotal='', total='', impuesto='' WHERE id = :id_cotizacion";
            $stmtM = $conexion->prepare($consultaMotivo);
            $stmtM->bindParam(":id_cotizacion", $id_cotizacion, PDO::PARAM_INT);
            $stmtM->execute();

            // 2. Eliminar los registros correspondientes de 'tbldetalle_cotizacion'
            $consultaEliminar = "DELETE FROM tbldetalle_cotizacion WHERE id_cotizacion = :id";
            $stmtE = $conexion->prepare($consultaEliminar);
            $stmtE->bindParam(":id", $id_cotizacion, PDO::PARAM_INT);
            $stmtE->execute();

            $conexion->commit();
            return array(
                'status' => 'success',
                'm' => 'Se eliminaron todas las filas de la solic. de compra con éxito.'
            );
        } catch (PDOException $e) {
            $conexion->rollBack();  // Deshacer cambios si ocurre un error
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar las filas de la solic. de compra: ' . $e->getMessage()
            );
        }
    }

    public static function mdlSubirPDF($id, $ruta)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("UPDATE tblcotizacion SET ruta_pdf=:ruta WHERE id=:id");
            $a->bindParam(":ruta", $ruta, PDO::PARAM_STR);
            $a->bindParam(":id", $id, PDO::PARAM_INT);
            $a->execute();

            return array(
                'status' => 'success',
                'm' => 'El pdf del presupuesto del proveedor guardó correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo guardar el pdf de presupuesto del proveedor' . $e->getMessage()
            );
        }
    }

    public static function mdlEliminarCotizacion($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("UPDATE tblcotizacion  SET 
            estado_anu = true WHERE id = :id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'Se anuló la solic. de cotización / compra con correctamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo anular la solic. de cotización / compra: ' . $e->getMessage()
            );
        }
    }

    public static function mdlEliminarFila($id, $id_cotizacion)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();
            $consultaMotivo = "UPDATE tblcotizacion
            SET motivo = CONCAT(
                (
                    SELECT STRING_AGG(descripcion, ', ' ORDER BY id)
                    FROM (
                        SELECT descripcion
                        FROM tbldetalle_cotizacion
                        WHERE id_cotizacion = :id_cotizacion
                        ORDER BY id
                        LIMIT 5
                    ) AS subquery
                ),
                CASE 
                    WHEN (SELECT COUNT(*) FROM tbldetalle_cotizacion WHERE id_cotizacion = :id_cotizacion) > 5
                    THEN '...'
                    ELSE ''
                END
            )
            WHERE id = :id_cotizacion;
        ";

            $e = $conexion->prepare("DELETE FROM tbldetalle_cotizacion WHERE id = :id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();

            // Preparar y ejecutar la consulta de actualización del campo 'motivo'
            $aMotivo = $conexion->prepare($consultaMotivo);
            $aMotivo->bindParam(":id_cotizacion", $id_cotizacion, PDO::PARAM_INT);
            $aMotivo->execute();

            $conexion->commit();

            return array(
                'status' => 'success',
                'm' => 'Se eliminó la fila con éxito.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar la fila ' . $e->getMessage()
            );
        }
    }

    static public function mdlDatosProveedor($id)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT ruc, telefono, direccion
                FROM tblproveedores WHERE id=:id");
            $l->bindParam(':id', $id, PDO::PARAM_INT);
            $l->execute();
            return $l->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }
}
