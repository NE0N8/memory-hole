<?php
require('DB.php');
if (isset($_POST['delete']) && isset($_POST['id'])) { //delete record
    $id = sanitize($cnct, $_POST['id']);
    $query = "DELETE FROM images WHERE id='$id'";
    $result = $cnct->query($query);
    if (!$result) {
        echo "DELETE failed: $query<br>" .
            $cnct->error . "<br><br>";
    }
    $query = "DELETE FROM image_tag WHERE imageID='$id'"; //delete from join table
    $result = $cnct->query($query);
    if (!$result) {
        echo "DELETE failed: $query<br>" .
            $cnct->error . "<br><br>";
    }
}
if (isset($_POST['ttl']) && isset($_POST['tag']) && !empty($_FILES['img'])) {   //check for uploaded file
    $img = cleanImg($cnct, $_FILES['img']['name']);    //sanitize upload form input
    $tag = CSV($cnct, $_POST['tag']); //format input as CSV
    $ttl = sanitize($cnct, $_POST['ttl']);
    move_uploaded_file($_FILES['img']['tmp_name'], "./images/" . $img); //save file to folder
    //create thumbnail from uploaded image
    $FPath = "./images/" . $img;
    $validFile = true;
    switch ($_FILES['img']['type']) {   //verify file type
        case "image/gif":
            $src = imagecreatefromgif($FPath);
            break;
        case "image/jpeg":
            $src = imagecreatefromjpeg($FPath);
            break;
        case "image/png":
            $src = imagecreatefrompng($FPath);
            break;
        default:
            $validFile = false;
            break;
    }
    if ($validFile) {
        list($W, $H) = getimagesize($FPath);
        $max = 227;     //max width and/or height
        $th_W = $W;
        $th_H = $H;
        if ($W > $H && $max < $W) {   //reduce width
            $th_H = $max / $W * $H;
            $th_W = $max;
        } elseif ($H > $W && $max < $H) {   //reduce height
            $th_W = $max / $H * $W;
            $th_H = $max;
        } elseif ($max < $W) {  //set height to max
            $th_W = $th_H = $max;
        }
        $tmp = imagecreatetruecolor($th_W, $th_H);  //generate thumbnail
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $th_W, $th_H, $W, $H);
        imagejpeg($tmp, "./thumbs/th_" . $img);   //save thumbnail to folder
        imagedestroy($tmp);
        imagedestroy($src);
    }
    $stmt = $cnct->prepare("INSERT INTO images VALUES (?, ?, 'NULL')");  //auto-increment record ID
    $stmt->bind_param("ss", $ttl, $img);
    $stmt->execute();
    $result = $stmt->store_result();
    if (!$result) {
        echo "<script>alert('Upload failed.');</script>";
        die("<script>location.replace('index.php');</script>");
    }
    $newID = $stmt->insert_id; //get ID of new record
    $tagList = explode(',',$tag);
    foreach ($tagList as $t) {
        $stmt = $cnct->prepare("INSERT INTO image_tag VALUES (?, ?, 'NULL')");  //insert tags
        $stmt->bind_param("ii", $newID, $t);
        $stmt->execute();
        $result = $stmt->store_result();
        if (!$result) {
            echo "<script>alert('Upload failed.');</script>";
            die("<script>location.replace('index.php');</script>");
        }
    }
}
function cleanImg($cnct, $str) {   //sanitize input - no htmlentities()
    $str = $cnct->real_escape_string($str);
    $str = trim(strip_tags(stripslashes($str)));
    $str = preg_replace("/[^A-Za-z0-9.\s+-]/", "", $str);
    return $str;
}
function CSV($cnct, $str) {     //convert input to CSV format
    $str = $cnct->real_escape_string($str);
    $str = trim(strip_tags(stripslashes($str)));
    $str = preg_replace("/[^0-9]/", " ", $str);
    $str = preg_replace("/\s+/", ",", $str);
    return $str;
}