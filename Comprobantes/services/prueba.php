<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $arr = json_decode($_POST['datos'], true);
  print_r($arr);
  print_r($_POST);
  // for($i = 0; $i < 4; $i++){
  //   if(isset($arr[$i])){
  //     echo "Existe - elemento en la posicion $i ";
  //     echo "** NIT: ".$arr[$i]['nit']." **";
  //   }
  // }
}


?>