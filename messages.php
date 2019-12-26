<?php
require_once('header.php');

if (!$loggedin){
    die();
}
if (isset($_GET['view'])){ 
    $view = $_GET['view'];
}
else{
    $view = $user;
}

try{
    if (isset($_POST['text'])){
        $text = $_POST['text'];
        if ($text != "")
        {
            $pm    = substr($_POST['pm'],0,1);
            $time= time();
            $sql = "INSERT INTO messages VALUES(NULL, :user, :view, :pm, :tim, :text)";

            $stmt = $DBH->prepare($sql);
            $stmt->bindParam(':user',$user);
            $stmt->bindParam(':view',$view);
            $stmt->bindParam(':pm',$pm);
            $stmt->bindParam(':tim',$time);
            $stmt->bindParam(':text',$text);
            $stmt->execute();
        }
    }
    if ($view != ""){
        if ($view == $user){
             $name1 = $name2 = "Your";
        }
        else{
            $name1 = "<a href='members.php?view=$view'>$view</a>'s";
            $name2 = "$view's";
        }

        echo "<div class='main'><h3>$name1 Messages</h3>";
        show_profile($view);
        echo <<<_END
<form class='form-group' method='post' action='messages.php?view=$view'>
Type here to leave a message:<br>
<textarea class='form-control' name='text' cols='40' rows='3' 
placeholder='your message...'
></textarea><br>

<div class='form-check form-check-inline'>
<input type='radio' name='pm' id='public' value='0' checked='checked' class="form-check-input">
<label class="form-check-label" for="public">
Public
</label>
</div>
<div class='form-check form-check-inline'>
<input type='radio' name='pm' id='private' value='1' class="form-check-input">
<label class="form-check-label" for="private">
Private
</label>
</div>
<br>
<input class='btn btn-secondary mt-3' type='submit' value='Post Message'></form><br>
_END;

    if (isset($_GET['erase'])){
        $erase = $_GET['erase'];
        $sql = "DELETE FROM messages WHERE id=:erase AND recip=:user";
        $stmt = $DBH->prepare($sql);
        $stmt->bindParam(':user',$user);
        $stmt->bindParam(':erase',$erase);
        $stmt->execute();        
    }

    $sql = "SELECT * FROM messages WHERE recip=:view ORDER BY time DESC";
    $stmt = $DBH->prepare($sql);
    $stmt->bindParam(':view',$view);
    $stmt->execute(); 
    $num = $stmt->rowCount();
    for ($j = 0 ; $j < $num ; ++$j){
        $row = $stmt->fetch();
        if ($row['pm'] == 0 || $row['auth'] == $user || $row['recip'] == $user){
            echo date('M jS \'y g:ia:', $row['time']);
            echo " <a href='messages.php?view=" . $row['auth'] . "'>" .
            $row['auth']. "</a> ";
            if ($row['pm'] == 0){
                echo "wrote: &quot;" . $row['message'] . "&quot; ";
            }            
            else{
                echo "whispered: <span class='whisper'>&quot;" .
                     $row['message'] . "&quot;</span> ";
            }            
            if ($row['recip'] == $user){
                echo "[<a href='messages.php?view=$view" .
                     "&erase=" . $row['id'] . "'>erase</a>]";
            }            
            echo "<br>";
        }
    }
    }
    if (!$num){ 
        echo "<div class='info text-info my-2'>No messages yet</div>";
    }
    echo "<a class='button' href='messages.php?view=$view'>Refresh messages</a>";

}
catch(PDOException $e){
    echo $e->getMessage();
}
?>
</div><br>
</div>
</body>
</html>