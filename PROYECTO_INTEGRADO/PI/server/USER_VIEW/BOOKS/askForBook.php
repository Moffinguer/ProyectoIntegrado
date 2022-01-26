<?php
//Este servicio insertará una nueva reserva
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Context-type: application/json;");
$data=array();
$isbn=$_GET["isbn"];
$id=$_GET["id"];
/*
    @isbn=Isbn of the book
    @id=dni of the student
*/
//Ask for a book, add into the database, a new solitude
try{
    $conexion = new PDO('mysql:host=localhost;dbname=biblioteca;charset=UTF8','root','');
    $sql="INSERT INTO IS_BORROWED(ID_USER,ISBN,REQUEST_DATE) VALUES('$id','$isbn',CURDATE())";
    $update=$conexion->prepare($sql);
    $update->execute();
    $data=["answer"=>"Espere a que se lo presten, vaya a biblioteca"];
}catch(PDOException $e){
    $data=["error"=>"La base de datos no está disponible"];
}
echo json_encode($data);
?>