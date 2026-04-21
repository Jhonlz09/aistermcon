<?php
require_once __DIR__ . '/conexion.php';
$conn = Conexion::ConexionDB();
$stmt = $conn->query("SELECT pg_get_viewdef('vista_inventario_completa'::regclass, true);");
echo $stmt->fetchColumn();
