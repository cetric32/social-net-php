<?php
//get db connection
require_once('db.php');

//establishing database connection
try{
    $DBH = new PDO("mysql:host=$hn;dbname=$db",$un,$pw);
    $DBH->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);    
}
catch(PDOException $e){
    echo $e->getMessage();
}

//module to store all functions
function create_table($tbl_name,$query){
    global $DBH;
    //function for creating tables
    try{
        $sql = "CREATE TABLE IF NOT EXISTS $tbl_name($query)";
        $stmt = $DBH->prepare($sql);
        $stmt->execute();
        echo "Table '$tbl_name' created or already exists.<br>";
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }
}

function destroySession()
{
    //logging out facility
    $_SESSION=array();
    if (session_id() != "" || isset($_COOKIE[session_name()]))
    setcookie(session_name(), '', time()-2592000, '/');
    session_destroy();
}

function show_profile($user){
    global $DBH;
    if(file_exists("static/images/profiles/$user.jpg")){
        echo "<img class='img-thumbnail img-fluid mr-3' src='static/images/profiles/$user.jpg' style='float:left;'>";
    }
    $sql = "SELECT * FROM profiles WHERE user=:user";
    //preparing statememnts
    try{
        $stmt = $DBH->prepare($sql);
        $stmt->bindParam(':user',$user);
        $stmt->execute();
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }

    //if user exists
    if($stmt->rowCount()){
        $row = $stmt->fetch();
        echo stripslashes($row['text']) . "<br style='clear:left;'><br>";
    }
}


?>