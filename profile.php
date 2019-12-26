<?php
require_once('header.php');
if(!$loggedin){
    die();
}

echo "<div class='main'><h3>Your Profile</h3>";
$sql = "SELECT * FROM profiles WHERE user=:user";
try{
    $stmt = $DBH->prepare($sql);
    $stmt->bindParam(':user',$user);
    $stmt->execute();
}
catch(PDOException $e){
    echo $e->getMessage();
}

if(isset($_POST['text'])){
    $text = $_POST['text'];
    $text = preg_replace('/\s\s+/', ' ', $text);
    if($stmt->rowCount()){
        $sql = "UPDATE profiles SET text=:text where user=:user";
    }
    else{
        $sql = "INSERT INTO profiles VALUES(:user, :text)";
    }
    try{
        $stmt = $DBH->prepare($sql);
        $stmt->bindParam(':user',$user);
        $stmt->bindParam(':text',$text);
        $stmt->execute();
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }
}
else{
    if($stmt->rowCount()){
        $row = $stmt->fetch();
        $text = stripslashes($row['text']);
    }
    else{
        $text = "";
    }
}
$text = stripslashes(preg_replace('/\s\s+/', ' ', $text));
if(isset($_FILES['image']['name'])){
    $saveto = "static/images/profiles/$user.jpg";
    move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
    $typeok = TRUE;

    switch($_FILES['image']['type'])
    {
        case "image/gif":
                $src = imagecreatefromgif($saveto);
                break;
        case "image/jpeg": // Both regular and progressive jpegs
        case "image/pjpeg": 
                $src = imagecreatefromjpeg($saveto);
                break;
        case "image/png":
                $src = imagecreatefrompng($saveto); 
                break;
        default:
                $typeok = FALSE; 
                break;
    }
    if ($typeok)
    {
        list($w, $h) = getimagesize($saveto);
        $max = 100;
        $tw = $w;
        $th = $h;
        if ($w > $h && $max < $w)
        {
            $th = $max / $w * $h;
            $tw = $max;
        }
        elseif ($h > $w && $max < $h)
        {
            $tw = $max / $h * $w;
            $th = $max;
        }
        elseif ($max < $w)
        {
            $tw = $th = $max;
        }
        $tmp = imagecreatetruecolor($tw, $th);
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
        imageconvolution($tmp, array(array(-1, -1, -1),
        array(-1, 16, -1), array(-1, -1, -1)), 8, 0);
        imagejpeg($tmp, $saveto);
        imagedestroy($tmp);
        imagedestroy($src);
    }
}
show_profile($user);
echo <<<_END
<form class='form-group' method='post' action='profile.php' enctype='multipart/form-data'>
<h3>Enter or edit your details and/or upload an image</h3>
<textarea class='form-control' name='text' cols='50' rows='3'>$text</textarea><br>
_END;
?>

<label for="file">Profile Image:</label>
 <input class="form-control-file" type='file' name='image' id="file" size='14'><br>
<input class="btn btn-secondary btn-lg" type='submit' value='Save Profile'>
</form></div><br>
</div>
</body>
</html>