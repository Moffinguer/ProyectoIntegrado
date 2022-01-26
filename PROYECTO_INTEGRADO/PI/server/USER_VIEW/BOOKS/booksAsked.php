<?php
//Este servicio devolverá en función de la vista los libros que estén registrados por ese usuario
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Context-type: application/json;");
$data=array();
$id=$_GET["id"];
$status=$_GET["status"];
/*
    @id=dni of the student
    @status= situation of that book 
 */
//Return books of a specific student in a specific situation
try{
    $conexion = new PDO('mysql:host=localhost;dbname=biblioteca;charset=UTF8','root','');
    $sql="SELECT IS_BORROWED.ISBN AS ISBN,TITLE,PUBLISHING_HOUSE,TOPIC,SUBJECT,STOCK,REQUEST_DATE,BORROW_DATE,RETURN_DATE FROM IS_BORROWED,BOOKS WHERE ID_USER='$id' AND STATUS_BOOK='$status' AND IS_BORROWED.ISBN=BOOKS.ISBN";
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