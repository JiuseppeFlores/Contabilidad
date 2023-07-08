<?php
require('../environments.php');
$server = $SERVER;
$database = $DB;
$user_db = $USER_DB;
$pass_db = $PASS_DB;
$char_set = $CHARACTER_SET;

$connection_info = array(
    "Database" => $database,
    "Uid" => $user_db,
    "PWD" => $pass_db,
    "CharacterSet" => $char_set
);
$con = sqlsrv_connect( $server , $connection_info );
if( !$con ){
    echo 'No puede conectarse con la base de .';
}
 
?>