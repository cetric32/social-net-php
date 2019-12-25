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

<script>
function checkUser(user){
    if(user.value == ''){
        $('$info').html('');
    }
    $.post('checkuser.php',{'user':user.value},function(resp,status){
        if(status == 200){
            $('$info').html(resp);
        }
    } );
}
</script>