<?php
require_once('functions.php');
if (isset($_POST['user']))
{
$user = $_POST['user'];

$sql = 'SELECT * FROM members WHERE user=:user';
try{
    $stmt = $DBH->prepare($sql);
    $stmt->bindParam(':user',$user);
    $stmt->execute();
    if($stmt->rowCount() > 0){
        echo "<span class='taken text-danger'>&nbsp;&#x2718; " .
                "This username is taken</span>";
    }
    else{
        echo "<span class='available text-info'>&nbsp;&#x2714; " .
            "This username is available</span>";
    }
}
catch(PDOException $e){
    echo $e->getMessage();
}
}
?>