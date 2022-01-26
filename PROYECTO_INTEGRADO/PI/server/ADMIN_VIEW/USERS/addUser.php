<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Context-type: application/json;");
$data=array();
$name=str_replace("+"," ",strtoupper($_GET["name"]));
$id=str_replace("+"," ",strtoupper($_GET["id"]));
$surnames=str_replace("+"," ",strtoupper($_GET["surnames"]));
$password=str_replace("+"," ",$_GET["passw"]);
$email=str_replace("+"," ",$_GET["mail"]);
/*
    @name=Name of the Student
    @id=Dni of the student
    @surnames=Surnames of the student
    @passw=Password of the student
    @mail=email of this student
*/
//Add a new student to the data base
//If that student already exists, update the year
$type="STUDENT";
try{
    $conexion = new PDO('mysql:host=localhost;dbname=biblioteca;charset=UTF8','root','');
    try{
        $sql="INSERT INTO USERS VALUES('$id','$name','$surnames','$password','$email',CURDATE(),'$type')";
        $select=$conexion->prepare($sql);
        $select->execute();
    }catch(PDOException $e){
        $sql="UPDATE USERS
        SET ACT_YEAR=CURDATE() WHERE ID='$id'";
        $select=$conexion->prepare($sql);
        $select->execute();
    }
    $data=["answer"=>"Usuario actualizado en la base de datos"];
}catch(PDOException $e){
    $data=["error"=>"La base de datos no está disponible"];
}
echo json_encode($data);
?>