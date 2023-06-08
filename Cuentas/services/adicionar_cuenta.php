<?php

    include("../../conexion.php");

    $response = array('success' => false, 'message' => '');

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['codigo']) && isset($_POST['descripcion']) && isset($_POST['grupo'])){
            // Datos a registrar en la BD
            $codigo = $_POST['codigo'];
            $codigo_cuenta = $_POST['codigo_cuenta'];
            $descripcion = $_POST['descripcion'];
            $nivel = $_POST['nivel'];

            // Descomposicion de codigo de cuenta
            $cuenta = array(
                'G' => isset($_POST['grupo']) ? $_POST['grupo'] : NULL,
                'R' => isset($_POST['rubro']) ? $_POST['rubro'] : NULL,
                'T' => isset($_POST['titulo']) ? $_POST['titulo'] : NULL,
                'C' => isset($_POST['compuesta']) ? $_POST['compuesta'] : NULL,
                'S' => isset($_POST['subcuenta']) ? $_POST['subcuenta'] : NULL
            );
            $cuenta[$nivel] = $codigo;
            // Consulta para insertar los nuevos registros ala tabla
            $sql = "INSERT INTO tblCuentas (codigo,descripcion,grupo,rubro,titulo,compuesta,subcuenta,nivel) VALUES ( ? , ? , ? , ? , ? , ? , ? , ? ) ;";
            $params = array($codigo_cuenta,$descripcion,$cuenta['G'],$cuenta['R'],$cuenta['T'],$cuenta['C'],$cuenta['S'],$nivel);
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $stmt = sqlsrv_query( $con, $sql , $params, $options );
            if($stmt === false){
                $errors = sqlsrv_errors();
                foreach( $errors as $error ) {
                    $response['message'] .= $error[ 'message']." , ";
                }
            }else{
                $response['success'] = true;
                $response['message'] = 'Registro adicionado con éxito.';
            }
            print_r($_POST);
        }else{
            $response['message'] = 'Los siguientes campos son necesarios: Código, descripción y grupo.';
        }
    }else{
        $response['message'] = 'La solicitud no es de tipo POST';
    }

    echo json_encode($response);
?>