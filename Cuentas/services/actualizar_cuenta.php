<?php

    include("../../conexion.php");

    $response = array('success' => false, 'message' => '');

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['id']) && isset($_POST['codigo']) && isset($_POST['descripcion'])){
            // Datos a registrar en la BD
            $id_cuenta = $_POST['id'];
            $codigo = $_POST['codigo'];
            $codigo_cuenta = $_POST['codigo_cuenta'];
            $descripcion = $_POST['descripcion'];
            $nivel = $_POST['ce_nivel'];
            // Consulta para verificar la existencia de una cuenta
            $sql = "SELECT * 
                    FROM tblCuentas tc
                    WHERE tc.codigo = ?
                        AND tc.idCuenta <> ? ;";
            $params = array($codigo_cuenta,$id_cuenta);
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $stmt = sqlsrv_query( $con, $sql , $params, $options );
            if( $stmt ){
                $num_registros = sqlsrv_num_rows($stmt);
                if( $num_registros == 0 ){
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
                    $sql = "UPDATE tblCuentas 
                    SET codigo = ? ,
                        descripcion = ? ,
                        grupo = ? ,
                        rubro = ? ,
                        titulo = ? ,
                        compuesta = ? ,
                        subcuenta = ? ,
                        nivel = ?
                    WHERE idCuenta = ? ;";
                    $params = array($codigo_cuenta,$descripcion,$cuenta['G'],$cuenta['R'],$cuenta['T'],$cuenta['C'],$cuenta['S'],$nivel,$id_cuenta);
                    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                    $stmt = sqlsrv_query( $con, $sql , $params, $options );
                    if( $stmt ){
                        $response['success'] = true;
                        $response['message'] = 'Cuenta actualizada con éxito.';
                    }else{
                        $errors = sqlsrv_errors();
                        foreach( $errors as $error ) {
                            $response['message'] .= $error[ 'message']." , ";
                        }
                    }
                }else{
                    $response['message'] = "Código de cuenta ya registrada.";
                }
            }else{
                $errors = sqlsrv_errors();
                foreach( $errors as $error ) {
                    $response['message'] .= $error[ 'message']." , ";
                }
            }
        }else{
            $response['message'] = 'Los siguientes campos son necesarios: Id, código y descripción.';
        }
    }else{
        $response['message'] = 'La solicitud no es de tipo POST';
    }

    echo json_encode($response);
?>