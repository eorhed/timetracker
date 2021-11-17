<?php



require_once 'registroTiempo_model.php';
$db = new RegistroTiempo_Model("user");
$registroSeguimiento = array('idtarea' => $_POST['idtarea'],
                             'comentarios' => $_POST['comentarios'],
                             'fecha_inicio' => $_POST['fecha_inicio'],
                             'duracion' => $_POST['duracion']
                            );

$idRegistroSesion = $db->insertarRegistroSeguimientoBD($registroSeguimiento);

$response["exito"] = true;
$response["registro"] = $idRegistroSesion;

header('Content-type: application/json; charset=utf-8'); // Imprescindible para que se recoja el JSON en el .js
echo json_encode($response);