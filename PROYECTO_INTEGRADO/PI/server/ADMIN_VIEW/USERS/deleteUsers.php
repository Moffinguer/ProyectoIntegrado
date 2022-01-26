<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Context-type: application/json;");
$data=array();
//Delete all users of years before
try{
    $conexion = new PDO('mysql:host=localhost;dbname=biblioteca;charset=UTF8','root','');
    $sql="DELETE FROM USERS WHERE YEAR(ACT_YEAR)<YEAR(CURDATE()) AND TYPE='STUDENT'";
    $select=$conexion->prepare($sql);
    $select->execute();
    $data=["answer"=>"Estudiantes que ya no cursan este año, han sido borrados"];
}catch(PDOException $e){
    $data=["error"=>"La base de datos no está disponible"];
}
echo json_encode($data);
?>