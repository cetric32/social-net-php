<?php
require_once('header.php');

if (!$loggedin) {
    die();
}
echo "<div class='main'>";

if (isset($_GET['view']))
{
    $view = $_GET['view'];
    if ($view == $user){
         $name = "Your";
    }
    else
    {
        $name = "$view's";
    }
    echo "<h3>$name Profile</h3>";
    show_profile($view);    
    echo "<a class='button' href='messages.php?view=$view'>" .
    "View $name messages</a><br><br>";
    die("</div></div></body></html>");
}
if (isset($_GET['add']))
{
    $add = $_GET['add'];
    $sql = "SELECT * FROM friends WHERE user=:add  AND friend=:user";
    try{
        $stmt = $DBH->prepare($sql);
        $stmt->bindParam(':add',$add);
        $stmt->bindParam(':user',$user);
        $stmt->execute();
        if(!$stmt->rowCont()){
            $sql = "INSERT INTO friends VALUES (:add, :user)";
            $stmt = $DBH->prepare($sql);
            $stmt->bindParam(':add',$add);
            $stmt->bindParam(':user',$user);
            $stmt->execute();
        }
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }
}
elseif (isset($_GET['remove']))
{
    $remove = $_GET['remove'];
    $sql = "DELETE FROM friends WHERE user=:remove AND friend=:user";
    try{
        $stmt = $DBH->prepare($sql);
        $stmt->bindParam(':remove',$remove);
        $stmt->bindParam(':user',$user);
        $stmt->execute();
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }
}

$sql = "SELECT user FROM members ORDER BY user";

try{
    $stmt = $DBH->prepare($sql);
    $stmt->execute();
    $num = $stmt->rowCount();
    echo "<h3>Other Members</h3><ul>";
    while($row = $stmt->fetch()){
        if ($row['user'] == $user) {
            continue;
        }

        echo "<li><a href='members.php?view=" .
            $row['user'] . "'>" . $row['user'] . "</a>";
        $follow = "follow";
      
        $sql = "SELECT * FROM friends WHERE user=:user AND friend=:friend";
        $stmt1 = $DBH->prepare($sql);
        $stmt1->bindParam(':user',$row['user']);
        $stmt1->bindParam(':friend',$user);
        $stmt1->execute();
        $t1 = $stmt1->rowCount();
        $sql = "SELECT * FROM friends WHERE user='$user' AND friend='" . $row['user'] . "'";
        $sql = "SELECT * FROM friends WHERE user=:user AND friend=:friend";
        $stmt1 = $DBH->prepare($sql);
        $stmt1->bindParam(':user',$user);
        $stmt1->bindParam(':friend',$row['user']);
        $stmt1->execute();
        $t2 = $stmt1->rowCount();

        if (($t1 + $t2) > 1){
            echo " &harr; is a mutual friend";
        }
        elseif ($t1){
            echo " &larr; you are following";
        }
        elseif ($t2){
            echo " &rarr; is following you";
            $follow = "recip";
        }

        if (!$t1){
            echo "[<a href='members.php?add=".
                $row['user'] . "'>$follow</a>]";
        }
        else{
            echo "[<a href='members.php?remove=" . 
                $row['user'] . "'>drop</a>]";
        }

    }
}
catch(PDOException $e){
    echo $e->getMessage();
}
?>
</ul></div>
</div>
</body>
</html>