<?php
require_once('db.php');

//establishing database connection
try{
    $DBH = new PDO("mysql:host=$hn;dbname=$db",$un,$pw);
    $DBH->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);    
}
catch(PDOException $e){
    echo $e->getMessage();
}
echo 'connect';
?>