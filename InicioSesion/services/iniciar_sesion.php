<?php

    require("../../conexion_empresa.php");

    $response = array('success' => false, 'message' => '');

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['pin']) && isset($_POST['usuario']) && isset($_POST['contrasenia'])){
            // Obteniendo el PIN de la empresa
            $pin = $_POST['pin'];
            $status = "ACTIVO";
            // Preparando la consulta
            $sql = "SELECT * 
                    FROM tblBase tb, tblEmpresas te
                    WHERE tb.idCompany = te.idEmpresa
                        AND tb.pin = ? 
                        AND tb.status = ? ;";

            $params = array($pin , $status);
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $stmt = sqlsrv_query( $con_com, $sql , $params, $options );

            if( $stmt ){
                $num = sqlsrv_num_rows($stmt);
                if( $num > 0 ){
                    $company = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC );
                    // Configurando el uso de cookies
                    $lifetime = 60*60*24;
                    session_set_cookie_params($lifetime);
                    // Definiendo cookies de empresa
                    if(isset($_COOKIE['base'])){
                        unset($_COOKIE['base']);
                    }
                    if(isset($_COOKIE['server'])){
                        unset($_COOKIE['server']);
                    }
                    setcookie('base',$company['base'], time()+64800,'/',false);
                    setcookie('server',$company['server'], time()+64800,'/',false);
                    $_SESSION['base'] = $company['base'];
	                $_SESSION['server'] = $company['server'];
                    // Incluyendo archivo de conexion a la empresa
                    include("../../conexion.php");
                    // Obteniendo el usuario y contraseña
                    $user = $_POST['usuario'];
                    $password = $_POST['contrasenia'];
                    // Preparando la consulta de usuario en la empresa
                    $sql = "SELECT TOP(1) * 
                            FROM tblUsuarios tu
                            WHERE tu.usuario = ?
                                AND tu.password = ? ;";
                    $params = array($user,$password);
                    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                    $stmt = sqlsrv_query( $con, $sql , $params, $options );
                    if( $stmt ){
                        $num = sqlsrv_num_rows($stmt);
                        if( $num > 0 ){
                            $data = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC );
                            // Definiendo cookies de usuario
                            if(isset($_COOKIE['id_user'])){
                                unset($_COOKIE['id_user']);
                            }
                            if(isset($_COOKIE['user'])){
                                unset($_COOKIE['user']);
                            }
                            setcookie('id_user',$data['idUsuario'], time()+64800,'/',false);
                            setcookie('user',$data['nombre'], time()+64800,'/',false);
                            $_SESSION['id_user'] = $data['idUsuario'];
                            $_SESSION['user'] = $data['nombre'];
                            
                            $response['success'] = true;
                            $response['message'] = "Inicio de sesión exitosa.";
                        }else{
                            $response['message'] = 'Usuario y contraseña no registradas.';
                        }
                    }else{
                        $errors = sqlsrv_errors();
                        foreach( $errors as $error ) {
                            $response['message'] .= $error[ 'message']." , ";
                        }
                    }
                }else{
                    $response['message'] = 'PIN incorrecto.';
                }
            }else{
                $errors = sqlsrv_errors();
                foreach( $errors as $error ) {
                    $response['message'] .= $error[ 'message']." , ";
                }
            }
        }else{
            $response['message'] = 'Los siguientes campos son necesarios: PIN, usuario y contraseña.';
        }
    }else{
        $response['message'] = 'La solicitud no es de tipo POST';
    }

    echo json_encode($response);
?>