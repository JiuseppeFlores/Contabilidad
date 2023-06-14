<?php

    include("../functions.php");

    closeSession();
    $response = array('success' => true , 'message' => "Sesión finalizada correctamente");

    echo json_encode($response);
?>