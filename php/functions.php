<?php
    function isSessionStarted(){
        return(
            isset($_COOKIE['conta_id_user']) && 
            isset($_COOKIE['conta_user']) && 
            isset($_COOKIE['conta_server']) && 
            isset($_COOKIE['conta_base'])
        );
    }

    function isSessionActive($level = false){
        $dir = isset($level) ? $level : '';
        if(isSessionStarted()){
            return true;
        }else{
            header('Location: '.$level.'InicioSesion/');
            return;
        }
    }

    function closeSession(){
        setcookie('conta_base',null,-1,'/',false);
        setcookie('conta_server',null,-1,'/',false);
        setcookie('conta_id_user',null,-1,'/',false);
        setcookie('conta_user',null,-1,'/',false);
        if(isset($_COOKIE['conta_base'])){
            unset($_COOKIE['conta_base']);
        }
        if(isset($_COOKIE['conta_server'])){
            unset($_COOKIE['conta_server']);
        }
        if(isset($_COOKIE['conta_id_user'])){
            unset($_COOKIE['conta_id_user']);
        }
        if(isset($_COOKIE['conta_user'])){
            unset($_COOKIE['conta_user']);
        }
    }

    function obtenerDatosEmpresa(){
        // Obteniendo el ID de la empresa
        $id = $_COOKIE['conta_id_company'];
        $default = array(
            'nombre' => '',
            'direccion' => '',
            'nit' => '',
            'ciudad' => '',
            'celular' => '',
            'telefono' => ''
        );
        // Preparando la consulta SQL para la obtencion de datos de la empresa
        require_once('../conexion_empresa.php');
        $sql = "SELECT TOP 1 *
                FROM tblEmpresas te
                WHERE te.idEmpresa = ? ;";
        $params = array($id);
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $stmt = sqlsrv_query( $con_com, $sql , $params, $options );

        if( $stmt ){
            $count = sqlsrv_num_rows($stmt);
            if($count > 0){
                $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC );
                return $row;
            }else{
                return $default;
            }
        }else{
            return $default;
        }
    }

    function obtenerDatosGestion($con){
        $default = array(
            'gestion' => '2023',
            'fechaInicial' => '2023-01-01',
            'fechaFinal' => '2023-06-30'
        );
        // Consulta para obtener datos de la gestion activa de la empresa
        $estado = "ACTIVO";
        $sql = "SELECT TOP 1 *
                FROM tblGestiones tg
                WHERE tg.estado = ? 
                ORDER BY idGestion DESC ;";
        $params = array($estado);
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $stmt = sqlsrv_query( $con, $sql , $params, $options );

        if( $stmt ){
            $count = sqlsrv_num_rows($stmt);
            if($count > 0){
                $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC );
                $row['fechaInicial'] = $row['fechaInicial']->format('Y-m-d');
                $row['fechaFinal'] = $row['fechaFinal']->format('Y-m-d');
                return $row;
            }else{
                return $default;
            }
        }else{
            return $default;
        }
    }

?>