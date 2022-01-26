<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Context-type: application/json;");
$data=array();
/*
    @isbn=isbn of the book
*/
//Delete that book from database
$isbn=$_GET["isbn"];
try{
    $conexion = new PDO('mysql:host=localhost;dbname=biblioteca;charset=UTF8','root','');
    $sql="DELETE FROM BOOKS WHERE ISBN='$isbn'";
    $select=$conexion->prepare($sql);
    $select->execute();
    $data=["answer"=>"Libro borrado, si no se ha borrado, ese libro lo ha pedido un alumno"];
}catch(PDOException $e){
    $data=["error"=>"La base de datos no está disponible"];
}
echo json_encode($data);
?>