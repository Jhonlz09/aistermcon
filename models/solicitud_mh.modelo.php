<?php

require_once "../utils/database/conexion.php";

class ModeloSolicitudDespacho
{
    /**
     * Listar todas las solicitudes de despacho
     */
    static public function mdlListarSolicitudes()
    {
        try {
            $consulta = "SELECT sd.id, sd.num_sol,
				TO_CHAR(sd.fecha, 'DD/MM/YYYY') AS fecha,
				p.num_orden || ' ' || c.nombre as cliente,
				e1.nombre || ' ' || e3.apellido as responsable,
				e3.nombre || ' ' || e3.apellido as autorizado,
                sd.estado,
                '' as acciones,
				    o.id as id_orden,
				    e2.nombre as despachado
            FROM tblsolicitud_despacho sd
            LEFT JOIN tblorden o ON sd.id_orden = o.id
			LEFT JOIN tblpresupuesto p ON p.id = o.id
			LEFT JOIN tblclientes c On c.id = p.id_cliente
            LEFT JOIN tblboleta b ON sd.id_boleta = b.id
            LEFT JOIN tblempleado e1 ON sd.id_responsable = e1.id
            LEFT JOIN tblempleado e2 ON sd.id_despachado = e2.id
            LEFT JOIN tblempleado e3 ON sd.id_autorizado = e3.id
            ORDER BY sd.id DESC";
            
            $l = Conexion::ConexionDB()->prepare($consulta);
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return array(
                'status' => 'error',
                'm' => "Error en la consulta: " . $e->getMessage()
            );
        }
    }
    /**
     * Consultar una solicitud específica
     */
    static public function mdlConsultarSolicitud($id)
    {
        try {
            $consulta = "SELECT 
                sd.id, 
                sd.num_sol, 
                sd.id_orden,
                sd.id_boleta,
                sd.estado,
                TO_CHAR(sd.fecha, 'DD/MM/YYYY HH24:MI') AS fecha,
                sd.id_responsable,
                sd.id_despachado,
                sd.id_autorizado
            FROM tblsolicitud_despacho sd
            WHERE sd.id = :id";
            
            $l = Conexion::ConexionDB()->prepare($consulta);
            $l->bindParam(":id", $id, PDO::PARAM_INT);
            $l->execute();
            return $l->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array(
                'status' => 'error',
                'm' => "Error en la consulta: " . $e->getMessage()
            );
        }
    }

    /**
     * Consultar detalles de productos en una solicitud
     */
    static public function mdlConsultarDetalleSolicitud($id_solicitud)
    {
        try {
            $consulta = "SELECT dd.id,dd.id_producto,i.codigo,i.descripcion,c.nombre as categoria,u.nombre as unidad,dd.cant_sol,dd.cant_apro,i.stock,dd.anulado,
                '' as acciones
            FROM tbldetalle_despacho dd
            JOIN tblinventario i ON dd.id_producto = i.id
            JOIN tblcategoria c ON i.id_categoria = c.id
            JOIN tblunidad u ON i.id_unidad = u.id
            WHERE dd.id_solic_despacho = :id
            ORDER BY dd.id";
            
            $l = Conexion::ConexionDB()->prepare($consulta);
            $l->bindParam(":id", $id_solicitud, PDO::PARAM_INT);
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return array(
                'status' => 'error',
                'm' => "Error en la consulta: " . $e->getMessage()
            );
        }
    }

    /**
     * Crear nueva solicitud de despacho
     */
    static public function mdlCrearSolicitud($id_orden, $id_boleta, $id_responsable)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();

            $consulta = "INSERT INTO tblsolicitud_despacho 
                (id_orden, id_boleta, id_responsable, estado, fecha)
                VALUES (:id_orden, :id_boleta, :id_responsable, true, NOW())
                RETURNING id";
            
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id_orden", $id_orden, PDO::PARAM_INT);
            $stmt->bindParam(":id_boleta", $id_boleta, PDO::PARAM_INT);
            $stmt->bindParam(":id_responsable", $id_responsable, PDO::PARAM_INT);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $id_nueva_solicitud = $resultado['id'];

            $conexion->commit();

            return array(
                'status' => 'success',
                'm' => 'Solicitud creada exitosamente',
                'id' => $id_nueva_solicitud
            );
        } catch (PDOException $e) {
            $conexion->rollBack();
            return array(
                'status' => 'error',
                'm' => "Error al crear la solicitud: " . $e->getMessage()
            );
        }
    }

    /**
     * Agregar filas a una solicitud
     */
    static public function mdlAgregarFilasSolicitud($cantidad_filas, $id_solicitud)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();

            $consulta = "INSERT INTO tbldetalle_despacho 
                (id_producto, id_solic_despacho, cant_sol, cant_apro, anulado)
                VALUES (NULL, :id_solicitud, 0, 0, false)";
            
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id_solicitud", $id_solicitud, PDO::PARAM_INT);

            for ($i = 0; $i < $cantidad_filas; $i++) {
                $stmt->execute();
            }

            $conexion->commit();

            return array(
                'status' => 'success',
                'm' => 'Filas agregadas exitosamente'
            );
        } catch (PDOException $e) {
            $conexion->rollBack();
            return array(
                'status' => 'error',
                'm' => "Error al agregar filas: " . $e->getMessage()
            );
        }
    }

    /**
     * Actualizar fila de detalle en solicitud
     */
    static public function mdlActualizarFilaSolicitud($id_fila, $params)
    {
        try {
            $conexion = Conexion::ConexionDB();
            
            $consulta = "UPDATE tbldetalle_despacho SET 
                id_producto = :id_producto,
                cant_sol = :cant_sol,
                cant_apro = :cant_apro,
                anulado = :anulado
            WHERE id = :id";
            
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id", $id_fila, PDO::PARAM_INT);
            $stmt->bindParam(":id_producto", $params['id_producto'], PDO::PARAM_INT);
            $stmt->bindParam(":cant_sol", $params['cant_sol'], PDO::PARAM_STR);
            $stmt->bindParam(":cant_apro", $params['cant_apro'], PDO::PARAM_STR);
            $stmt->bindParam(":anulado", $params['anulado'], PDO::PARAM_BOOL);
            $stmt->execute();

            return array(
                'status' => 'success',
                'm' => 'Fila actualizada exitosamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'error',
                'm' => "Error al actualizar: " . $e->getMessage()
            );
        }
    }

    /**
     * Actualizar solicitud
     */
    static public function mdlActualizarSolicitud($params)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();

            // Actualizar filas si es necesario
            if (isset($params['filas']) && $params['isFilas'] == 'true') {
                $filas = json_decode($params['filas'], true);
                
                foreach ($filas as $fila) {
                    $consulta = "UPDATE tbldetalle_despacho SET 
                        id_producto = :id_producto,
                        cant_sol = :cant_sol,
                        cant_apro = :cant_apro
                    WHERE id = :id";
                    
                    $stmt = $conexion->prepare($consulta);
                    $stmt->bindParam(":id", $fila['id'], PDO::PARAM_INT);
                    $stmt->bindParam(":id_producto", $fila['id_producto'], PDO::PARAM_INT);
                    $stmt->bindParam(":cant_sol", $fila['cant_sol'], PDO::PARAM_STR);
                    $stmt->bindParam(":cant_apro", $fila['cant_apro'], PDO::PARAM_STR);
                    $stmt->execute();
                }
            }

            // Actualizar datos principales de solicitud
            $consulta = "UPDATE tblsolicitud_despacho SET 
                id_orden = :id_orden,
                id_boleta = :id_boleta,
                id_responsable = :id_responsable
            WHERE id = :id";
            
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id", $params['id'], PDO::PARAM_INT);
            $stmt->bindParam(":id_orden", $params['id_orden'], PDO::PARAM_INT);
            $stmt->bindParam(":id_boleta", $params['id_boleta'], PDO::PARAM_INT);
            $stmt->bindParam(":id_responsable", $params['id_responsable'], PDO::PARAM_INT);
            $stmt->execute();

            $conexion->commit();

            return array(
                'status' => 'success',
                'm' => 'Solicitud actualizada exitosamente'
            );
        } catch (PDOException $e) {
            $conexion->rollBack();
            return array(
                'status' => 'error',
                'm' => "Error al actualizar: " . $e->getMessage()
            );
        }
    }

    /**
     * Eliminar una fila de detalle
     */
    static public function mdlEliminarFilaSolicitud($id_fila)
    {
        try {
            $conexion = Conexion::ConexionDB();
            
            $consulta = "DELETE FROM tbldetalle_despacho WHERE id = :id";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id", $id_fila, PDO::PARAM_INT);
            $stmt->execute();

            return array(
                'status' => 'success',
                'm' => 'Fila eliminada exitosamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'error',
                'm' => "Error al eliminar: " . $e->getMessage()
            );
        }
    }

    /**
     * Eliminar todas las filas de una solicitud
     */
    static public function mdlEliminarFilasSolicitud($id_solicitud)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();

            $consulta = "DELETE FROM tbldetalle_despacho WHERE id_solic_despacho = :id";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id", $id_solicitud, PDO::PARAM_INT);
            $stmt->execute();

            $conexion->commit();

            return array(
                'status' => 'success',
                'm' => 'Filas eliminadas exitosamente'
            );
        } catch (PDOException $e) {
            $conexion->rollBack();
            return array(
                'status' => 'error',
                'm' => "Error al eliminar: " . $e->getMessage()
            );
        }
    }

    /**
     * Eliminar solicitud
     */
    static public function mdlEliminarSolicitud($id_solicitud)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();

            // Eliminar detalles primero
            $consulta = "DELETE FROM tbldetalle_despacho WHERE id_solic_despacho = :id";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id", $id_solicitud, PDO::PARAM_INT);
            $stmt->execute();

            // Luego eliminar la solicitud
            $consulta = "DELETE FROM tblsolicitud_despacho WHERE id = :id";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id", $id_solicitud, PDO::PARAM_INT);
            $stmt->execute();

            $conexion->commit();

            return array(
                'status' => 'success',
                'm' => 'Solicitud eliminada exitosamente'
            );
        } catch (PDOException $e) {
            $conexion->rollBack();
            return array(
                'status' => 'error',
                'm' => "Error al eliminar: " . $e->getMessage()
            );
        }
    }

    /**
     * Cargar productos por categoría
     */
    static public function mdlCargarProductosPorCategoria()
    {
        try {
            $consulta = "SELECT 
                c.id as categoria_id,
                c.nombre as categoria,
                json_agg(
                    json_build_object(
                        'id', i.id,
                        'codigo', i.codigo,
                        'descripcion', i.descripcion,
                        'stock', i.stock,
                        'unidad', u.nombre
                    ) ORDER BY i.descripcion
                ) as productos
            FROM tblinventario i
            JOIN tblcategoria c ON i.id_categoria = c.id
            JOIN tblunidad u ON i.id_unidad = u.id
            WHERE i.estado = true AND i.stock > 0
            GROUP BY c.id, c.nombre
            ORDER BY c.nombre";
            
            $stmt = Conexion::ConexionDB()->prepare($consulta);
            $stmt->execute();
            $resultados = $stmt->fetchAll();

            // Procesar resultados para convertir JSON
            foreach ($resultados as &$row) {
                $row['productos'] = json_decode($row['productos'], true);
            }

            return $resultados;
        } catch (PDOException $e) {
            return array(
                'status' => 'error',
                'm' => "Error en la consulta: " . $e->getMessage()
            );
        }
    }

    /**
     * Autorizar solicitud
     */
    static public function mdlAutorizarSolicitud($id_solicitud, $id_autorizado)
    {
        try {
            $conexion = Conexion::ConexionDB();
            
            $consulta = "UPDATE tblsolicitud_despacho SET 
                id_autorizado = :id_autorizado,
                estado = true
            WHERE id = :id";
            
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id", $id_solicitud, PDO::PARAM_INT);
            $stmt->bindParam(":id_autorizado", $id_autorizado, PDO::PARAM_INT);
            $stmt->execute();

            return array(
                'status' => 'success',
                'm' => 'Solicitud autorizada exitosamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'error',
                'm' => "Error al autorizar: " . $e->getMessage()
            );
        }
    }

    /**
     * Confirmar despacho
     */
    static public function mdlConfirmarDespacho($id_solicitud, $id_despachado)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();

            // Actualizar solicitud
            $consulta = "UPDATE tblsolicitud_despacho SET 
                id_despachado = :id_despachado,
                estado = false
            WHERE id = :id";
            
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id", $id_solicitud, PDO::PARAM_INT);
            $stmt->bindParam(":id_despachado", $id_despachado, PDO::PARAM_INT);
            $stmt->execute();

            // Actualizar stock de productos
            $consultaStock = "SELECT id_producto, cant_apro FROM tbldetalle_despacho 
                WHERE id_solic_despacho = :id_solicitud AND cant_apro > 0 AND anulado = false";
            
            $stmtStock = $conexion->prepare($consultaStock);
            $stmtStock->bindParam(":id_solicitud", $id_solicitud, PDO::PARAM_INT);
            $stmtStock->execute();
            
            $detalles = $stmtStock->fetchAll();

            foreach ($detalles as $detalle) {
                $consultaUpdate = "UPDATE tblinventario SET 
                    stock = stock - :cantidad
                WHERE id = :id_producto";
                
                $stmtUpdate = $conexion->prepare($consultaUpdate);
                $stmtUpdate->bindParam(":id_producto", $detalle['id_producto'], PDO::PARAM_INT);
                $stmtUpdate->bindParam(":cantidad", $detalle['cant_apro'], PDO::PARAM_STR);
                $stmtUpdate->execute();
            }

            $conexion->commit();

            return array(
                'status' => 'success',
                'm' => 'Despacho confirmado exitosamente'
            );
        } catch (PDOException $e) {
            $conexion->rollBack();
            return array(
                'status' => 'error',
                'm' => "Error al confirmar despacho: " . $e->getMessage()
            );
        }
    }

    /**
     * Anular solicitud
     */
    static public function mdlAnularSolicitud($id_solicitud)
    {
        try {
            $conexion = Conexion::ConexionDB();
            
            $consulta = "UPDATE tbldetalle_despacho SET 
                anulado = true
            WHERE id_solic_despacho = :id";
            
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id", $id_solicitud, PDO::PARAM_INT);
            $stmt->execute();

            return array(
                'status' => 'success',
                'm' => 'Solicitud anulada exitosamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'error',
                'm' => "Error al anular: " . $e->getMessage()
            );
        }
    }
}
?>
