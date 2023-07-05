<?php
$nombre = $_POST['hola'];
echo json_encode(['status'=>200, 'parametro'=>$nombre]);

?>