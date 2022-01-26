<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Context-type: application/json;");
$data=array();
/*
    @isbn=isbn of the book
*/
//We check if that book is borrowed before
$isbn=$_GET["isbn"];
try{
    $conexion = new PDO('mysql:host=localhost;dbname=biblioteca;charset=UTF8','root','');
    $sql="SELECT COUNT(*) FROM IS_BORROWED WHERE ISBN='$isbn'";
    $select=$conexion->prepare($sql);
    $select->execute();
    $n=array();
    foreach($select as $dumb){
        $n[]=$dumb;
    }
    $data=["answer"=>$n[0][0]=="0"?"true":"false"];
}catch(PDOException $e){
    $data=["error"=>"La base de datos no está disponible"];
}
echo json_encode($data);
?>