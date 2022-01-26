<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Context-type: application/json;");
/*
    @isbn=Isbn of the book
    @title=Title of the book
    @ph=Publishing house
    @topic=Theme of the book
    @subject=Subject of the book
    @stock=number of books we have
    @author=Writter or Writters of that book
*/
$data=array();
$isbn=strtoupper($_GET["isbn"]);
$title=str_replace("+"," ",strtoupper($_GET["title"]));
$publishing_house=str_replace("+"," ",strtoupper($_GET["ph"]));
$topic=str_replace("+"," ",strtoupper($_GET["topic"]));
$subject=str_replace("+"," ",strtoupper($_GET["subject"]));
$stock=intval($_GET["stock"]);
$author=str_replace("+"," ",strtoupper($_GET["author"]));
//Add a new book
try{
    $conexion = new PDO('mysql:host=localhost;dbname=biblioteca;charset=UTF8','root','');
    $sql="INSERT INTO BOOKS VALUES('$isbn','$title','$publishing_house','$topic','$subject',$stock,'$author')";
    $select=$conexion->prepare($sql);
    $select->execute();
    $data=["answer"=>"Libro añadido con éxito"];
}catch(PDOException $e){
    $data=["error"=>"Error, revise si no ha introducido un libro ya existente. Si este error persiste, contacte con el administrador"];
}
echo json_encode($data);
?>