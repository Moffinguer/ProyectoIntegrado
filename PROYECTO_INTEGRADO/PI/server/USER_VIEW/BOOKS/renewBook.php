<?php
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
//Renew book of a student for 7 days more, if the student has renewed the book already once, he wont be able to do it
try{
    $conexion = new PDO('mysql:host=localhost;dbname=biblioteca;charset=UTF8','root','');
    $sql="SELECT TIMES_RENEWED FROM IS_BORROWED WHERE ID_USER='$id' AND ISBN='$isbn' AND REQUEST_DATE=(SELECT MAX(REQUEST_DATE) FROM IS_BORROWED WHERE ID_USER='$id' AND ISBN='$isbn')";
    $select=$conexion->prepare($sql);
    $select->execute();
    foreach($select as $dumb){
        $data[]=$dumb;
    }
    if($data[0][0]==1){
        $data=["answer"=>"No puedes renovarlo más de dos veces seguidas"];
    }else{
        $sql="UPDATE IS_BORROWED SET RETURN_DATE=DATE_ADD(CURDATE(), INTERVAL 7 DAY),TIMES_RENEWED=
                        (SELECT TIMES_RENEWED FROM IS_BORROWED WHERE ISBN='$isbn' AND ID_USER='$id' AND REQUEST_DATE=(SELECT MAX(REQUEST_DATE) FROM IS_BORROWED WHERE ID_USER='$id' AND ISBN='$isbn'))
                         WHERE ISBN='$isbn' AND ID_USER='$id' AND REQUEST_DATE=(SELECT MAX(REQUEST_DATE) FROM IS_BORROWED WHERE ID_USER='$id' AND ISBN='$isbn')";
        $update=$conexion->prepare($sql);
        $update->execute();
        $data=["answer"=>"Ha renovado el libro, tiene 7 días más"];
    }
}catch(PDOException $e){
    $data=["error"=>"La base de datos no está disponible"];
}
echo json_encode($data);
?>