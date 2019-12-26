<?php
require_once('header.php');
if (!$loggedin) {
    die();
}
if (isset($_GET['view'])) {
    $view = $_GET['view'];
}
else{
    $view = $user;
}
if ($view == $user)
{
    $name1 = $name2 = "Your";
    $name3 = "You are";
}
else
{
    $name1 = "<a href='members.php?view=$view'>$view</a>'s";
    $name2 = "$view's";
    $name3 = "$view is";
}
echo "<div class='main'>";
// Uncomment this line if you wish the user's profile to show here
 show_profile($view);

$followers = array();
$following = array();

$sql = "SELECT * FROM friends WHERE user=:user";
try{
    $stmt = $DBH->prepare($sql);
    $stmt->bindParam(':user',$view);
    $stmt->execute();
    $num = $stmt->rowCount();

    for ($j = 0 ; $j < $num ; ++$j)
    {
        $row = $stmt->fetch();
        $followers[$j] = $row['friend'];
    }

    $sql = "SELECT * FROM friends WHERE friend=:friend";

    $stmt = $DBH->prepare($sql);
    $stmt->bindParam(':friend',$view);
    $stmt->execute();
    $num = $stmt->rowCount();

    for ($j = 0 ; $j < $num ; ++$j)
    {
        $row = $stmt->fetch();
        $following[$j] = $row['user'];
    }

    $mutual = array_intersect($followers, $following);
    $followers = array_diff($followers, $mutual);
    $following = array_diff($following, $mutual);
    $friends = FALSE;

    if (sizeof($mutual))
    {
        echo "<span class='subhead'>$name2 mutual friends</span><ul>";
        foreach($mutual as $friend){
            echo "<li><a href='members.php?view=$friend'>$friend</a>";
        }        
        echo "</ul>";
        $friends = TRUE;
    }
    if (sizeof($followers))
    {
        echo "<span class='subhead'>$name2 followers</span><ul>";
        foreach($followers as $friend){
            echo "<li><a href='members.php?view=$friend'>$friend</a>";
        }        
        echo "</ul>";
        $friends = TRUE;
    }
    if (sizeof($following))
    {
        echo "<span class='subhead'>$name3 following</span><ul>";
        foreach($following as $friend){
            echo "<li><a href='members.php?view=$friend'>$friend</a>";
        }       
        echo "</ul>";
        $friends = TRUE;
    }

    if (!$friends){ 
        echo "<br>You don't have any friends yet.<br><br>";
    }

    echo "<a class='button' href='messages.php?view=$view'>" .
         "View $name2 messages</a>";   

}
catch(PDOException $e){
    echo $e->getMessage();
}

?>
</div><br>
</div>
</body>
</html>