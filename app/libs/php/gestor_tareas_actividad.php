<?php

$response['exito'] = true;
$response['datos'] = $_POST['idactividad'];

require_once 'tareas_model.php';
$db = new Tareas_Model('user');
$tareas = $db->getTareasActividad($_POST['idactividad']);
$response['tareas'] = $tareas;
header('Content-type: application/json; charset=utf-8'); // Imprescindible para que se recoja el JSON en el .js
echo json_encode($response);

?>
