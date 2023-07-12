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
                    if(isset($_COOKIE['conta_base'])){
                        unset($_COOKIE['conta_base']);
                    }
                    if(isset($_COOKIE['conta_server'])){
                        unset($_COOKIE['conta_server']);
                    }
                    if(isset($_COOKIE['conta_subdomain'])){
                        unset($_COOKIE['conta_subdomain']);
                    }
                    if(isset($_COOKIE['conta_id_company'])){
                        unset($_COOKIE['conta_id_company']);
                    }
                    setcookie('conta_base',$company['base'], time()+64800,'/',false);
                    setcookie('conta_server',$company['server'], time()+64800,'/',false);
                    setcookie('conta_subdomain',$company['subdomain'], time()+64800,'/',false);
                    setcookie('conta_id_company',$company['idCompany'], time()+64800,'/',false);
                    $_SESSION['conta_base'] = $company['base'];
	                $_SESSION['conta_server'] = $company['server'];
                    $_SESSION['conta_subdomain'] = $company['subdomain'];
                    $_SESSION['conta_id_company'] = $company['idCompany'];
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
                            if(isset($_COOKIE['conta_id_user'])){
                                unset($_COOKIE['conta_id_user']);
                            }
                            if(isset($_COOKIE['conta_user'])){
                                unset($_COOKIE['conta_user']);
                            }
                            setcookie('conta_id_user',$data['idUsuario'], time()+64800,'/',false);
                            setcookie('conta_user',$data['nombre'], time()+64800,'/',false);
                            $_SESSION['conta_id_user'] = $data['idUsuario'];
                            $_SESSION['conta_user'] = $data['nombre'];
                            
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