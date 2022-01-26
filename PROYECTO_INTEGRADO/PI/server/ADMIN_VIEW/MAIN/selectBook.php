<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Context-type: application/json;");
$data=array();
//Return all books who have a relation with a student
try{
    $conexion = new PDO('mysql:host=localhost;dbname=biblioteca;charset=UTF8','root','');
    $sql="SELECT IS_BORROWED.ISBN AS `ISBN`,TITLE,PUBLISHING_HOUSE,TOPIC,NAME,SURNAMES,SUBJECT,STOCK,ID_USER,REQUEST_DATE,BORROW_DATE,RETURN_DATE,STATUS_BOOK FROM IS_BORROWED 
    INNER JOIN BOOKS ON IS_BORROWED.ISBN=BOOKS.ISBN
    INNER JOIN USERS ON IS_BORROWED.ID_USER=USERS.ID;";
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