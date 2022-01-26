<?php
//Este servicio devolverá los libros que pueda reservar un alumno
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Context-type: application/json;");
$data=array();
try{
    $conexion = new PDO('mysql:host=localhost;dbname=biblioteca;charset=UTF8','root','');
    $sql="SELECT * FROM BOOKS WHERE STOCK>0";
    $select=$conexion->prepare($sql);
    $select->execute();
    $select=$select->fetchAll();
    foreach($select as $dumb){
        $data[]=$dumb;
    }
    for($i=0;$i<sizeof($data);$i++){
        for($j=0;$j<sizeof($data[0]);$j++){
            unset($data[$i][$j]);
        }
    }
}catch(PDOException $e){
    $data=["error"=>"La base de datos no está disponible"];
}
echo json_encode($data);
?>