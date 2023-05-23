<?php

    include("../../conexion.php");

    $response = array('success' => false, 'message' => '');

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['codigo']) && isset($_POST['nombre']) && isset($_POST['grupo'])){
            // Datos a registrar en la BD
            $codigo = $_POST['codigo'];
            $nombre = $_POST['nombre'];
            $grupo = $_POST['grupo'];
            // Consulta para insertar los nuevos registros ala tabla
            $sql = "INSERT INTO tblPlanCuentas (codigo,nombre,grupo) VALUES ( ? , ? , ? ) ;";
            $params = array($codigo,$nombre,$grupo);
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $stmt = sqlsrv_query( $con, $sql , $params, $options );
            if($stmt === false){
                $errors = sqlsrv_errors();
                foreach( $errors as $error ) {
                    $response['message'] .= $error[ 'message']." , ";
                }
            }else{
                $response['success'] = true;
                $response['message'] = 'Registro realizado con éxito.';
            }
        }else{
            $response['message'] = 'Los siguientes campos son necesarios: Código, nombre y grupo';
        }
    }else{
        $response['message'] = 'La solicitud no es de tipo POST';
    }

    echo json_encode($response);
?>