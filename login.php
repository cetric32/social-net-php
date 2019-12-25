<?php
require_once('header.php');
echo "<div class='main'><h3>Please enter your details to log in</h3>";
$error = $user = $pass = "";
if (isset($_POST['user']))
{
$user = $_POST['user'];
$pass = $_POST['pass'];
if ($user == "" || $pass == ""){
    $error = "<span class='text-danger'>Not all fields were entered</span><br>";
}
else
{
    $sql = "SELECT user,pass FROM members  WHERE user=:user AND pass=:pass";
    try{
        $stmt = $DBH->prepare($sql);
        $stmt->bindParam(':user',$user);
        $stmt->bindParam(':pass',$pass);
        $stmt->execute();
        if($stmt->rowCount() == 0){
            $error = "<span class='text-danger'>Username/Password
                        invalid</span><br><br>";
        }
        else{
            $_SESSION['user'] = $user;
            $_SESSION['pass'] = $pass;
            die("You are now logged in. Please <a href='members.php?view=$user'>" .
            "click here</a> to continue.<br><br>");
        }
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }
}
}
echo <<<_END
<form class='form-group' method='post' action='login.php'>$error
<label for='user'>Username</label><input type='text' id='user' class='form-control'
maxlength='16' name='user' value='$user'><br>
<label for='pass'>Password</label><input type='password' id='pass' class='form-control'
maxlength='16' name='pass' value='$pass'>
_END;
?>

<br>
<span class='fieldname'>&nbsp;</span>
<input class="btn btn-secondary btn-lg" type='submit' value='Login'>
</form><br></div>
</div>
</body>
</html>