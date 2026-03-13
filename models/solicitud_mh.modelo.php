<?php

require_once "../utils/database/conexion.php";

class ModeloSolicitudDespacho
{
    /**
     * Listar todas las solicitudes de despacho
     */
    static public function mdlListarSolicitudes($anio)
    {
        try {
            $consulta = "SELECT sd.id, sd.num_sol,
				TO_CHAR(sd.fecha, 'DD/MM/YYYY') AS fecha,
				p.num_orden || ' ' || c.nombre as cliente,
				split_part(e1.apellido, ' ', 1) || ' ' ||  split_part(e1.nombre, ' ', 1) as responsable,
                COALESCE(
                    split_part(e3.apellido, ' ', 1) || ' ' || split_part(e3.nombre, ' ', 1),
                    u.nombres
                ) as autorizado,
                sd.estado,
                '' as acciones,
				    o.id as id_orden,
				    e2.nombre as despachado,
                    sd.anulado
            FROM tblsolicitud_despacho sd
            LEFT JOIN tblorden o ON sd.id_orden = o.id
			LEFT JOIN tblpresupuesto p ON p.id = o.id
			LEFT JOIN tblclientes c On c.id = p.id_cliente
            LEFT JOIN tblempleado e1 ON sd.id_responsable = e1.id
            LEFT JOIN tblempleado e2 ON sd.id_despachado = e2.id
            LEFT JOIN tblempleado e3 ON sd.id_autorizado = e3.id
            LEFT JOIN tblusuario u ON sd.id_usuario_autorizado = u.id
            WHERE EXTRACT(YEAR FROM sd.fecha) = :anio
            ORDER BY sd.id DESC";

            $l = Conexion::ConexionDB()->prepare($consulta);
            $l->bindParam(":anio", $anio, PDO::PARAM_INT);
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
                p.num_orden || ' ' || c.nombre as orden_label,
                p.num_orden as orden,
                c.nombre as cliente,
                sd.estado,
                TO_CHAR(sd.fecha, 'YYYY-MM-DD') AS fecha,
                sd.id_responsable,
                sd.id_autorizado,
                sd.notas
            FROM tblsolicitud_despacho sd
            LEFT JOIN tblorden o ON sd.id_orden = o.id
            LEFT JOIN tblpresupuesto p ON p.id = o.id
            LEFT JOIN tblclientes c On c.id = p.id_cliente
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
            $consulta = "SELECT dd.id,dd.id_producto,i.codigo,i.descripcion,c.nombre as categoria,i.id_categoria,u.nombre as unidad,dd.cant_sol,dd.cant_apro,i.stock,i.img,
                '' as acciones
            FROM tbldetalle_despacho dd
            JOIN tblinventario i ON dd.id_producto = i.id
            JOIN tblcategoria c ON i.id_categoria = c.id
            JOIN tblunidad u ON i.id_unidad = u.id
            WHERE dd.id_solic_despacho = :id AND dd.cant_sol > 0
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
            WHERE id = :id";

            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id", $id_fila, PDO::PARAM_INT);
            $stmt->bindParam(":id_producto", $params['id_producto'], PDO::PARAM_INT);
            $stmt->bindParam(":cant_sol", $params['cant_sol'], PDO::PARAM_STR);
            $stmt->bindParam(":cant_apro", $params['cant_apro'], PDO::PARAM_STR);
            $stmt->execute();

            return array(
                'status' => 'success',
                'm' => 'Fila actualizada correctamente'
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
                'm' => 'Solicitud actualizada correctamente'
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
                'm' => 'Fila eliminada correctamente'
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
                'm' => 'Filas eliminadas correctamente'
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
     * Anular solicitud
     */
    static public function mdlAnularSolicitud($id_solicitud)
    {
        try {
            $conexion = Conexion::ConexionDB();

            $consulta = "UPDATE tblsolicitud_despacho SET 
                anulado = true
            WHERE id = :id";

            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id", $id_solicitud, PDO::PARAM_INT);
            $stmt->execute();

            return array(
                'status' => 'success',
                'm' => 'Solicitud de despacho anulada correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'error',
                'm' => "Error al anular: " . $e->getMessage()
            );
        }
    }
    /**
     * Consultar producto por código para autocompletado
     */
    static public function mdlConsultarProductoPorCodigo($codigo)
    {
        try {
            $consulta = "SELECT 
                i.id,
                i.codigo,
                i.descripcion,
                i.stock,
                u.nombre as unidad,
                i.id_categoria,
                i.img
            FROM tblinventario i
            JOIN tblunidad u ON i.id_unidad = u.id
            WHERE i.codigo = :codigo AND i.estado = true";

            $stmt = Conexion::ConexionDB()->prepare($consulta);
            $stmt->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array(
                'status' => 'error',
                'm' => "Error en la consulta: " . $e->getMessage()
            );
        }
    }
    /**
     * Guardar solicitud completa (Cabecera y Detalles)
     */
    static public function mdlGuardarSolicitudCompleta($datos)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();

            // 1. Insertar Cabecera
            $consulta = "INSERT INTO tblsolicitud_despacho 
                (num_sol, id_orden, fecha, id_responsable, notas)
                VALUES (generar_num_despacho(), :id_orden, :fecha, :id_responsable, :notas)
                RETURNING id";

            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id_orden", $datos['id_orden'], PDO::PARAM_INT);
            $stmt->bindParam(":fecha", $datos['fecha']); // Timestamp o string fecha
            $stmt->bindParam(":notas", $datos['notas']);
            $stmt->bindParam(":id_responsable", $datos['id_responsable'], PDO::PARAM_INT);

            if (!$stmt->execute()) {
                throw new Exception("Error al guardar la cabecera de la solicitud.");
            }

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $id_solicitud = $resultado['id'];

            // 2. Insertar Detalles
            if (!empty($datos['filas'])) {
                $filas = json_decode($datos['filas'], true);

                $consultaDetalle = "INSERT INTO tbldetalle_despacho 
                    (id_producto, id_solic_despacho, cant_sol, cant_apro, anulado)
                    VALUES (:id_producto, :id_solicitud, :cant_sol, :cant_apro, false)";

                $stmtDetalle = $conexion->prepare($consultaDetalle);

                foreach ($filas as $fila) {
                    $stmtDetalle->bindParam(":id_producto", $fila['id_producto'], PDO::PARAM_INT);
                    $stmtDetalle->bindParam(":id_solicitud", $id_solicitud, PDO::PARAM_INT);
                    $stmtDetalle->bindParam(":cant_sol", $fila['cant_sol'], PDO::PARAM_STR);
                    $stmtDetalle->bindParam(":cant_apro", $fila['cant_apro'], PDO::PARAM_STR);

                    if (!$stmtDetalle->execute()) {
                        throw new Exception("Error al guardar el detalle del producto ID: " . $fila['id_producto']);
                    }
                }
            }

            // Obtener el siguiente valor de la secuencia para mantener el contador actualizado
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $next_seq = $conexion->lastInsertId('secuencia_despacho') + 1;
            $_SESSION["sc_desp"] = $next_seq;

            $conexion->commit();

            // Llamar al envio de correo en segundo plano
            $stmtClienteOrden = $conexion->prepare("SELECT p.num_orden, c.nombre as cliente 
                                                    FROM tblorden o
                                                    JOIN tblpresupuesto p ON p.id = o.id
                                                    JOIN tblclientes c ON c.id = p.id_cliente
                                                    WHERE p.id = :id_orden");
            $stmtClienteOrden->bindParam(":id_orden", $datos['id_orden'], PDO::PARAM_INT);
            $stmtClienteOrden->execute();
            $datosOrden = $stmtClienteOrden->fetch(PDO::FETCH_ASSOC);

            if ($datosOrden) {
                $cliente = $datosOrden['cliente'];
                $num_orden = $datosOrden['num_orden'];
                $notas = $datos['notas'] ? $datos['notas'] : 'Sin observaciones';
                $fecha_orden = date('Y-m-d'); 
                
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $usuario = isset($_SESSION['s_usuario']->nombres) ? $_SESSION['s_usuario']->nombres : 'Desconocido';
                $origen = isset($datos['origen']) ? $datos['origen'] : 'bodega';
                
                self::enviarCorreoSolSegundoPlano($notas, $num_orden, $fecha_orden, $cliente, $usuario, $origen);
            }

            return array(
                'status' => 'success',
                'm' => 'Solicitud guardada correctamente',
                'id' => $id_solicitud,
                'nc' => $next_seq
            );

        } catch (Exception $e) {
            $conexion->rollBack();
            return array(
                'status' => 'error',
                'm' => "Error al guardar la solicitud: " . $e->getMessage()
            );
        }
    }

    /**
     * Guardar y Aprobar de una sola vez una Nueva Solicitud (Cabecera y Detalles)
     */
    static public function mdlGuardaryAprobarNuevaSolicitud($datos)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();

            // 1. Insertar Cabecera ya aprobada
            $consulta = "INSERT INTO tblsolicitud_despacho 
                (num_sol, id_orden, fecha, id_responsable, notas, estado, id_usuario_autorizado)
                VALUES (generar_num_despacho(), :id_orden, :fecha, :id_responsable, :notas, true, :id_usuario_autorizado)
                RETURNING id";

            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id_orden", $datos['id_orden'], PDO::PARAM_INT);
            $stmt->bindParam(":fecha", $datos['fecha']);
            $stmt->bindParam(":id_responsable", $datos['id_responsable'], PDO::PARAM_INT);
            $stmt->bindParam(":notas", $datos['notas']);
            $stmt->bindParam(":id_usuario_autorizado", $datos['id_usuario_autorizado'], PDO::PARAM_INT);

            if (!$stmt->execute()) {
                throw new Exception("Error al guardar la cabecera de la solicitud.");
            }

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $id_solicitud = $resultado['id'];

            // 2. Insertar Detalles con cant_apro = cant_sol
            if (!empty($datos['filas'])) {
                $filas = json_decode($datos['filas'], true);

                $consultaDetalle = "INSERT INTO tbldetalle_despacho 
                    (id_producto, id_solic_despacho, cant_sol, cant_apro, anulado)
                    VALUES (:id_producto, :id_solic_despacho, :cant_sol, :cant_apro, false)";

                $stmtDetalle = $conexion->prepare($consultaDetalle);

                foreach ($filas as $fila) {
                    $cant_apro = (isset($fila['cant_apro']) && $fila['cant_apro'] > 0) ? $fila['cant_apro'] : $fila['cant_sol'];
                    
                    $stmtDetalle->bindParam(":id_producto", $fila['id_producto'], PDO::PARAM_INT);
                    $stmtDetalle->bindParam(":id_solic_despacho", $id_solicitud, PDO::PARAM_INT);
                    $stmtDetalle->bindParam(":cant_sol", $fila['cant_sol'], PDO::PARAM_STR);
                    $stmtDetalle->bindParam(":cant_apro", $cant_apro, PDO::PARAM_STR);

                    if (!$stmtDetalle->execute()) {
                        throw new Exception("Error al guardar el detalle del producto ID: " . $fila['id_producto']);
                    }
                }
            }

            // 3. Obtener secuencia actual 
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $next_seq = $conexion->lastInsertId('secuencia_despacho') + 1;
            $_SESSION["sc_desp"] = $next_seq;

            $conexion->commit();

            // 4. Llamar al envio de correo en segundo plano
            $stmtClienteOrden = $conexion->prepare("SELECT p.num_orden, c.nombre as cliente 
                                                    FROM tblorden o
                                                    JOIN tblpresupuesto p ON p.id = o.id
                                                    JOIN tblclientes c ON c.id = p.id_cliente
                                                    WHERE p.id = :id_orden");
            $stmtClienteOrden->bindParam(":id_orden", $datos['id_orden'], PDO::PARAM_INT);
            $stmtClienteOrden->execute();
            $datosOrden = $stmtClienteOrden->fetch(PDO::FETCH_ASSOC);

            if ($datosOrden) {
                $cliente = $datosOrden['cliente'];
                $num_orden = $datosOrden['num_orden'];
                $notas = $datos['notas'] ? $datos['notas'] : 'Sin observaciones';
                $fecha_orden = date('Y-m-d'); 
                
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $usuario = isset($_SESSION['s_usuario']->nombres) ? $_SESSION['s_usuario']->nombres : 'Desconocido';
                $origen = isset($datos['origen']) ? $datos['origen'] : 'bodega';
                
                self::enviarCorreoSolSegundoPlano($notas, $num_orden, $fecha_orden, $cliente, $usuario, $origen);
            }

            return array(
                'status' => 'success',
                'm' => 'Solicitud guardada y aprobada correctamente',
                'id' => $id_solicitud,
                'nc' => $next_seq
            );

        } catch (Exception $e) {
            $conexion->rollBack();
            return array(
                'status' => 'error',
                'm' => "Error al guardar y aprobar la solicitud: " . $e->getMessage()
            );
        }
    }

    /**
     * Actualizar solicitud completa (Cabecera y Detalles)
     */
    static public function mdlActualizarSolicitudCompleta($params)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();

            $id_solicitud = $params['id_solicitud'];

            // 1. Actualizar tblsolicitud_despacho
            $consulta = "UPDATE tblsolicitud_despacho SET 
                id_orden = :id_orden,
                fecha = :fecha,
                id_responsable = :id_responsable,
                notas = :notas
            WHERE id = :id";

            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id", $id_solicitud, PDO::PARAM_INT);
            $stmt->bindParam(":id_orden", $params['id_orden'], PDO::PARAM_INT);
            $stmt->bindParam(":fecha", $params['fecha'], PDO::PARAM_STR);
            $stmt->bindParam(":id_responsable", $params['id_responsable'], PDO::PARAM_INT);
            $stmt->bindParam(":notas", $params['notas'], PDO::PARAM_STR);

            if (!$stmt->execute()) {
                throw new Exception("Error al actualizar la cabecera de la solicitud.");
            }

            // 2. Eliminar detalles existentes
            $consultaDelete = "DELETE FROM tbldetalle_despacho WHERE id_solic_despacho = :id";
            $stmtDelete = $conexion->prepare($consultaDelete);
            $stmtDelete->bindParam(":id", $id_solicitud, PDO::PARAM_INT);

            if (!$stmtDelete->execute()) {
                throw new Exception("Error al preparar la actualización de los detalles.");
            }

            // 3. Insertar nuevos detalles
            if (isset($params['filas']) && !empty($params['filas'])) {
                $filas = json_decode($params['filas'], true);

                $consultaDetalle = "INSERT INTO tbldetalle_despacho (id_solic_despacho, id_producto, cant_sol, cant_apro) 
                                    VALUES (:id_solic_despacho, :id_producto, :cant_sol, :cant_apro)";
                $stmtDetalle = $conexion->prepare($consultaDetalle);

                foreach ($filas as $fila) {
                    $cant_apro = isset($fila['cant_apro']) ? $fila['cant_apro'] : 0;

                    $stmtDetalle->bindParam(":id_solic_despacho", $id_solicitud, PDO::PARAM_INT);
                    $stmtDetalle->bindParam(":id_producto", $fila['id_producto'], PDO::PARAM_INT);
                    $stmtDetalle->bindParam(":cant_sol", $fila['cant_sol'], PDO::PARAM_STR);
                    $stmtDetalle->bindParam(":cant_apro", $cant_apro, PDO::PARAM_STR);

                    if (!$stmtDetalle->execute()) {
                        throw new Exception("Error al actualizar el detalle del producto ID: " . $fila['id_producto']);
                    }
                }
            }

            $conexion->commit();

            return array(
                'status' => 'success',
                'm' => 'Solicitud editada correctamente',
                'id' => $id_solicitud
            );

        } catch (Exception $e) {
            $conexion->rollBack();
            return array(
                'status' => 'error',
                'm' => "Error al actualizar la solicitud de despacho: " . $e->getMessage()
            );
        }
    }

    /**
     * Aprobar solicitud completa (Actualiza cabecera, detalles y estado)
     */
    static public function mdlAprobarSolicitudCompleta($params)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();

            $id_solicitud = $params['id_solicitud'];

            // 1. Actualizar tblsolicitud_despacho y estado = true
            $consulta = "UPDATE tblsolicitud_despacho SET 
                id_orden = :id_orden,
                fecha = :fecha,
                id_responsable = :id_responsable,
                notas = :notas,
                estado = true,
                id_usuario_autorizado = :id_usuario_autorizado
            WHERE id = :id";

            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id", $id_solicitud, PDO::PARAM_INT);
            $stmt->bindParam(":id_orden", $params['id_orden'], PDO::PARAM_INT);
            $stmt->bindParam(":fecha", $params['fecha'], PDO::PARAM_STR);
            $stmt->bindParam(":id_responsable", $params['id_responsable'], PDO::PARAM_INT);
            $stmt->bindParam(":notas", $params['notas'], PDO::PARAM_STR);
            $stmt->bindParam(":id_usuario_autorizado", $params['id_usuario_autorizado'], PDO::PARAM_INT);

            if (!$stmt->execute()) {
                throw new Exception("Error al actualizar la cabecera y estado de la solicitud.");
            }

            // 2. Eliminar detalles existentes para reinsertarlos limpiamente
            $consultaDelete = "DELETE FROM tbldetalle_despacho WHERE id_solic_despacho = :id";
            $stmtDelete = $conexion->prepare($consultaDelete);
            $stmtDelete->bindParam(":id", $id_solicitud, PDO::PARAM_INT);

            if (!$stmtDelete->execute()) {
                throw new Exception("Error al preparar la actualización de los detalles.");
            }

            // 3. Insertar nuevos detalles con cant_apro guardada
            if (isset($params['filas']) && !empty($params['filas'])) {
                $filas = json_decode($params['filas'], true);

                $consultaDetalle = "INSERT INTO tbldetalle_despacho (id_solic_despacho, id_producto, cant_sol, cant_apro) 
                                    VALUES (:id_solic_despacho, :id_producto, :cant_sol, :cant_apro)";
                $stmtDetalle = $conexion->prepare($consultaDetalle);

                foreach ($filas as $fila) {
                    $cant_apro = isset($fila['cant_apro']) ? $fila['cant_apro'] : 0;

                    $stmtDetalle->bindParam(":id_solic_despacho", $id_solicitud, PDO::PARAM_INT);
                    $stmtDetalle->bindParam(":id_producto", $fila['id_producto'], PDO::PARAM_INT);
                    $stmtDetalle->bindParam(":cant_sol", $fila['cant_sol'], PDO::PARAM_STR);
                    $stmtDetalle->bindParam(":cant_apro", $cant_apro, PDO::PARAM_STR);

                    if (!$stmtDetalle->execute()) {
                        throw new Exception("Error al actualizar el detalle del producto ID: " . $fila['id_producto']);
                    }
                }
            }

            $conexion->commit();

            // Llamar al envío de correo en segundo plano
            $stmtClienteOrden = $conexion->prepare("SELECT p.num_orden, c.nombre as cliente 
                                                    FROM tblorden o
                                                    JOIN tblpresupuesto p ON p.id = o.id
                                                    JOIN tblclientes c ON c.id = p.id_cliente
                                                    WHERE p.id = :id_orden");
            $stmtClienteOrden->bindParam(":id_orden", $params['id_orden'], PDO::PARAM_INT);
            $stmtClienteOrden->execute();
            $datosOrden = $stmtClienteOrden->fetch(PDO::FETCH_ASSOC);

            if ($datosOrden) {
                $cliente = $datosOrden['cliente'];
                $num_orden = $datosOrden['num_orden'];
                $notas = $params['notas'] ? $params['notas'] : 'Sin observaciones';
                $fecha_orden = date('Y-m-d'); 
                
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $usuario = isset($_SESSION['s_usuario']->nombres) ? $_SESSION['s_usuario']->nombres : 'Desconocido';
                $origen = isset($params['origen']) ? $params['origen'] : 'bodega';
                
                self::enviarCorreoSolSegundoPlano($notas, $num_orden, $fecha_orden, $cliente, $usuario, $origen, 'Se aprobo la solicitud de despacho Nro. '. $num_orden);
            }

            return array(
                'status' => 'success',
                'm' => 'Solicitud aprobada y guardada correctamente',
                'id' => $id_solicitud
            );

        } catch (Exception $e) {
            $conexion->rollBack();
            return array(
                'status' => 'error',
                'm' => "Error al aprobar la solicitud: " . $e->getMessage()
            );
        }
    }

    /**
     * Cambiar estado solicitud (Aprobar/Desaprobar)
     */
    static public function mdlAprobarSolicitud($id_solicitud, $id_usuario, $estado)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();

            $estado_bool = filter_var($estado, FILTER_VALIDATE_BOOLEAN);

            // 1. Actualizar estado y usuario autorizado
            $consulta = "UPDATE tblsolicitud_despacho SET 
                estado = :estado,
                id_usuario_autorizado = :id_usuario
            WHERE id = :id";

            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id", $id_solicitud, PDO::PARAM_INT);
            $stmt->bindParam(":estado", $estado_bool, PDO::PARAM_BOOL);
            $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            if ($estado_bool) {
                // 2. Actualizar cantidad aprobada = cantidad solicitada, SOLO si no hay una cantidad aprobada previamente (null o 0)
                $consultaDetalle = "UPDATE tbldetalle_despacho SET 
                cant_apro = cant_sol 
            WHERE id_solic_despacho = :id AND (cant_apro IS NULL OR cant_apro = 0)";

                $stmtDetalle = $conexion->prepare($consultaDetalle);
                $stmtDetalle->bindParam(":id", $id_solicitud, PDO::PARAM_INT);
                $stmtDetalle->execute();
            }
            $conexion->commit();

            if ($estado_bool) {
                // Fetch details for email
                $stmtDetalleSolicitud = $conexion->prepare("SELECT id_orden, notas FROM tblsolicitud_despacho WHERE id = :id");
                $stmtDetalleSolicitud->bindParam(":id", $id_solicitud, PDO::PARAM_INT);
                $stmtDetalleSolicitud->execute();
                $datosSolicitud = $stmtDetalleSolicitud->fetch(PDO::FETCH_ASSOC);

                if ($datosSolicitud) {
                    $stmtClienteOrden = $conexion->prepare("SELECT p.num_orden, c.nombre as cliente 
                                                            FROM tblorden o
                                                            JOIN tblpresupuesto p ON p.id = o.id
                                                            JOIN tblclientes c ON c.id = p.id_cliente
                                                            WHERE p.id = :id_orden");
                    $stmtClienteOrden->bindParam(":id_orden", $datosSolicitud['id_orden'], PDO::PARAM_INT);
                    $stmtClienteOrden->execute();
                    $datosOrden = $stmtClienteOrden->fetch(PDO::FETCH_ASSOC);

                    if ($datosOrden) {
                        $cliente = $datosOrden['cliente'];
                        $num_orden = $datosOrden['num_orden'];
                        $notas = $datosSolicitud['notas'] ? $datosSolicitud['notas'] : 'Sin observaciones';
                        $fecha_orden = date('Y-m-d'); 
                        
                        if (session_status() == PHP_SESSION_NONE) {
                            session_start();
                        }
                        $usuario = isset($_SESSION['s_usuario']->nombres) ? $_SESSION['s_usuario']->nombres : 'Desconocido';
                        $origen = isset($_POST['origen']) ? $_POST['origen'] : 'supervisor'; 
                        
                        self::enviarCorreoSolSegundoPlano($notas, $num_orden, $fecha_orden, $cliente, $usuario, $origen, 'Se aprobo la solicitud de despacho Nro. '. $num_orden);
                    }
                }
            }

            return array(
                'status' => 'success',
                'm' => 'Estado de la solicitud actualizado correctamente'
            );
        } catch (PDOException $e) {
            $conexion->rollBack();
            return array(
                'status' => 'error',
                'm' => "Error al aprobar: " . $e->getMessage()
            );
        }
    }

    /**
     * Reanudar solicitud (Desanular)
     */
    static public function mdlReanudarSolicitud($id_solicitud)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();

            $consulta = "UPDATE tblsolicitud_despacho SET anulado = false WHERE id = :id";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id", $id_solicitud, PDO::PARAM_INT);
            $stmt->execute();

            $conexion->commit();

            return array(
                'status' => 'success',
                'm' => 'Solicitud desanulada correctamente'
            );
        } catch (PDOException $e) {
            $conexion->rollBack();
            return array(
                'status' => 'error',
                'm' => "Error al desanular: " . $e->getMessage()
            );
        }
    }

    /**
     * Listar Solicitudes aprobadas sin boleta
     */
    static public function mdlListarSolicitudesAprobadasSinBoleta()
    {
        try {
            $conexion = Conexion::ConexionDB();
            $consulta = "SELECT DISTINCT sd.id, sd.num_sol, 'MATERIAL' as texto, sd.id_orden
                FROM tblsolicitud_despacho sd
                JOIN tbldetalle_despacho dd ON sd.id = dd.id_solic_despacho
                JOIN tblinventario i ON dd.id_producto = i.id
                LEFT JOIN tblboleta b ON sd.id = b.id_solicitud_despacho AND b.is_material = true
                LEFT JOIN tblorden o ON sd.id_orden = o.id
                WHERE sd.estado = true AND sd.anulado = false AND b.id IS NULL AND i.id_categoria = 1
                
                UNION
                
                SELECT DISTINCT sd.id, sd.num_sol, 'HERRAMIENTA' as texto, sd.id_orden
                FROM tblsolicitud_despacho sd
                JOIN tbldetalle_despacho dd ON sd.id = dd.id_solic_despacho
                JOIN tblinventario i ON dd.id_producto = i.id
                LEFT JOIN tblboleta b ON sd.id = b.id_solicitud_despacho AND b.is_material = false
                LEFT JOIN tblorden o ON sd.id_orden = o.id
                WHERE sd.estado = true AND sd.anulado = false AND b.id IS NULL AND i.id_categoria != 1
                
                ORDER BY num_sol DESC, texto";

            $stmt = $conexion->prepare($consulta);
            $stmt->execute();
            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $respuesta = [];
            foreach ($filas as $fila) {
                $respuesta[] = array(
                    "id" => $fila['id'],
                    "label" => $fila['num_sol'] . ' - ' . $fila['texto'],
                    "num_sol" => $fila['num_sol'],
                    "texto" => $fila['texto'],
                    "id_orden" => $fila['id_orden']
                );
            }
            return $respuesta;
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Consultar cantidad retornada (Ingreso a bodega) de un producto específico asociado a una solicitud
     */
    static public function mdlConsultarRetornoPorProductoYSolicitud($id_solicitud, $id_producto)
    {
        try {
            // En tblsalidas solo hay un id_producto para un id_boleta, por ende extraemos directo el retorno
            $consulta = "SELECT s.retorno 
                         FROM tblsalidas s
                         INNER JOIN tblboleta b ON s.id_boleta = b.id
                         WHERE b.id_solicitud_despacho = :id_solicitud 
                           AND s.id_producto = :id_producto";

            $stmt = Conexion::ConexionDB()->prepare($consulta);
            $stmt->bindParam(":id_solicitud", $id_solicitud, PDO::PARAM_INT);
            $stmt->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
            $stmt->execute();
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $resultado ? $resultado['retorno'] : null;
            
        } catch (PDOException $e) {
            return null; // En caso de error, devolvemos null
        }
    }

    /**
     * Consultar detalles de solicitud por tipo (MATERIAL o HERRAMIENTA)
     */
    static public function mdlConsultarDetalleSolicitudPorTipo($id_solicitud, $tipo)
    {
        try {
            $conexion = Conexion::ConexionDB();

            // Si el tipo es MATERIAL, filtramos categoría 1. Si es HERRAMIENTA, filtramos diferentes de 1.
            $filtro_categoria = ($tipo == 'MATERIAL') ? "c.id_categoria = 1" : "c.id_categoria != 1";

            $consulta = "SELECT 
                            c.id,
                            c.codigo, 
                            c.descripcion, 
                            u.nombre as unidad,
                            a.cant_apro
                        FROM tbldetalle_despacho a
                        INNER JOIN tblinventario c ON a.id_producto = c.id
                        LEFT JOIN tblunidad u ON c.id_unidad = u.id
                        WHERE a.id_solic_despacho = :id
                        AND $filtro_categoria";

            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":id", $id_solicitud, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array("error" => $e->getMessage());
        }
    }

    /**
     * Función para enviar correo de solicitud en segundo plano
     */
    static private function enviarCorreoSolSegundoPlano($descrip, $orden, $fecha, $cliente, $usuario, $origen = 'bodega', $title = 'Nueva solicitud de despacho')
    {
        $scriptPath = escapeshellarg(__DIR__ . DIRECTORY_SEPARATOR . 'send_email_sol.php');
        $descrip = escapeshellarg($descrip);
        $orden = escapeshellarg($orden);
        $cliente = escapeshellarg($cliente);
        $fecha = escapeshellarg($fecha);
        $usuario = escapeshellarg($usuario);
        $origen_arg = escapeshellarg($origen);
        $title = escapeshellarg($title);

        // Comando para ejecutar en segundo plano en Windows
        $command = "php $scriptPath $descrip $orden $fecha $cliente $usuario $origen_arg $title > /dev/null 2>&1 &";
     
        exec($command);
    }
}
?>