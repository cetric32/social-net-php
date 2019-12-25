<?php
require_once('header.php');
echo <<<_END
<script>
function checkUser(user){
    if(user.value == ''){
        $('#info').html('');
        return;
    }
    $.post('checkuser.php',{'user':user.value},function(resp,status){
        console.log(status);
        if(status == 'success'){
            $('#info').html(resp);
        }
    } );
}
</script>
<div class='main'><h3>Please enter your details to sign up</h3>
_END;
if (isset($_POST['user']))
{
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    if ($user == "" || $pass == "")
    {
        $error = "Not all fields were entered<br><br>";
    }    
    else
    {
        $sql = "SELECT * FROM members WHERE user=:user";
        //prepare statements
        try{
            $stmt = $DBH->prepare($sql);
            $stmt->bindParam(':user',$user);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                $error = "That username already exists<br><br>";
            }
            else{
                    $sql = "INSERT INTO members VALUES(:user, :pass)";
                    //prepare statemnts
                    try{
                        $stmt = $DBH->prepare($sql);
                        $stmt->bindParam(':user',$user);
                        $stmt->bindParam(':pass',$pass);
                        $stmt->execute();
                        die("<h4>Account created</h4>Please Log in.<br><br>");
                    }
                    catch(PDOException $e){
                        echo $e->getMessage();
                    }           
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }        
    }
}
?>

<form class='form-group' method='post' action='signup.php'>
    <?php
    if(isset($error)){
        echo $error;
    }
    ?>
    
    <label for='user'>Username</label>
    <input class='form-control' type='text' maxlength='16' name='user' id='user' value='
    <?php if(isset($user)) echo  $user; ?>'
    onBlur='checkUser(this)'><span id='info' class="info"></span><br>
    <label for='pass'>Password</label>
    <input class='form-control' type='text' maxlength='16' name='pass' id='pass'
    value='<?php if(isset($pass)) echo  $pass; ?>'>

    <span class='fieldname'>&nbsp;</span>
    <input class='btn btn-secondary btn-lg mt-3' type='submit' value='Sign up'>
</form></div><br>
</div>
</body>
</html>