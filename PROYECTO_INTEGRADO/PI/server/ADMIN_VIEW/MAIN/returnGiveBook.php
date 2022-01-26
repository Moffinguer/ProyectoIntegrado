<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Context-type: application/json;");
$data=array();
$isbn=$_GET["isbn"];
$id=$_GET["id"];
$action=$_GET["action"];
try{
    $conexion = new PDO('mysql:host=localhost;dbname=biblioteca;charset=UTF8','root','');
    $sql="SELECT COUNT(*) FROM BOOKS
            WHERE ISBN='$isbn' AND STOCK>0";
        $select=$conexion->prepare($sql);
        $select->execute();
        $select=$select->fetchAll();
        $try=array();
        foreach($select as $dumb){
            $try[]=$dumb;
        }
        if($try[0][0]=="1" || $action=="return"){
            if($action=="give"){
                //Check if that book has been asked by that student, if not, we will give it to the student
                $sql="SELECT COUNT(*) FROM IS_BORROWED
                    WHERE isbn='$isbn' AND id_user='$id' AND STATUS_BOOK='SOLICITADO'";
                $select=$conexion->prepare($sql);
                $select->execute();
                $select=$select->fetchAll();
                $try=array();
                foreach($select as $dumb){
                    $try[]=$dumb;
                }
                if($try[0][0]=="0"){
                    $sql="INSERT INTO IS_BORROWED(ID_USER,ISBN,BORROW_DATE,REQUEST_DATE,RETURN_DATE,STATUS_BOOK) VALUES
                    ('$id','$isbn',CURDATE(),CURDATE(),DATE_ADD(CURDATE(), INTERVAL 7 DAY),'PRESTADO');
                    UPDATE BOOKS
                      SET STOCK=(SELECT STOCK FROM BOOKS WHERE ISBN='$isbn')-1 WHERE ISBN='$isbn';";
                }else{
                    $sql="UPDATE IS_BORROWED
                    SET STATUS_BOOK='PRESTADO',RETURN_DATE=DATE_ADD(CURDATE(), INTERVAL 7 DAY), BORROW_DATE=CURDATE() WHERE ISBN='$isbn' AND ID_USER='$id' AND REQUEST_DATE=(SELECT REQUEST_DATE FROM IS_BORROWED WHERE ISBN='$isbn' AND ID_USER='$id');
                    UPDATE BOOKS
                      SET STOCK=(SELECT STOCK FROM BOOKS WHERE ISBN='$isbn')-1 WHERE ISBN='$isbn';";
                }
                $data=["answer"=>"Libro prestado"];
            }else if($action=="return"){
                $sql="UPDATE IS_BORROWED
                      SET STATUS_BOOK='DEVUELTO',RETURN_DATE=CURDATE() WHERE ISBN='$isbn' AND ID_USER='$id' AND REQUEST_DATE=(SELECT REQUEST_DATE FROM IS_BORROWED WHERE ISBN='$isbn' AND ID_USER='$id');
                      UPDATE BOOKS
                      SET STOCK=(SELECT STOCK FROM BOOKS WHERE ISBN='$isbn')+1 WHERE ISBN='$isbn';";
                $data=["answer"=>"Libro devuelto"];
            }
            $select=$conexion->prepare($sql);
            $select->execute();
        }else{
            $data=["answer"=>"Ya no quedan más libros que prestar"];
        }
}catch(PDOException $e){
    $data=["error"=>"La base de datos no está disponible"];
}
echo json_encode($data);
?>