<?php
    function isSessionStarted(){
        return(
            isset($_COOKIE['id_user']) && 
            isset($_COOKIE['user']) && 
            isset($_COOKIE['server']) && 
            isset($_COOKIE['base'])
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
        setcookie('base',null,-1,'/',false);
        setcookie('server',null,-1,'/',false);
        setcookie('id_user',null,-1,'/',false);
        setcookie('user',null,-1,'/',false);
        if(isset($_COOKIE['base'])){
            unset($_COOKIE['base']);
        }
        if(isset($_COOKIE['server'])){
            unset($_COOKIE['server']);
        }
        if(isset($_COOKIE['id_user'])){
            unset($_COOKIE['id_user']);
        }
        if(isset($_COOKIE['user'])){
            unset($_COOKIE['user']);
        }
    }

    function dataCompany(){
        // Obteniendo el ID de la empresa
        $id = $_COOKIE['id_company'];
        // Preparando la consulta SQL para la obtencion de datos de la empresa
        require_once('../conexion_empresa.php');
        $sql = "SELECT TOP 1 *
                FROM tblEmpresas te
                WHERE te.idEmpresa = ? ;";
        $params = array($id);
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $stmt = sqlsrv_query( $con, $sql , $params, $options );

        if( $stmt ){
            return sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC );
        }else{
            return false;
        }

    }
?>