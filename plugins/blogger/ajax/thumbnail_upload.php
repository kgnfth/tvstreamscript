<?php
session_start();
require_once("../../../vars.php");

$res = array();
if (isset($_SESSION['admin_user_id']) && $_SESSION['admin_user_id']){
    header('Content-type: text/html; charset=utf-8');

    
    $uploaddir = "$basepath/thumbs/";
    $basename = $_FILES['userfile']['name'];
    $cleanname = "blogger_".md5(basename($_FILES['userfile']['name']).date("H:i:s")).".jpg";

    $uploadfile = $uploaddir .  $cleanname;
    
    
    if ((substr_count($basename,".jpg")) || (substr_count($basename,".jpeg")) || (substr_count($basename,".gif")) || (substr_count($basename,".png"))){
        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
            $res['status'] = 1;
            $res['message'] = $cleanname;
        } else {
            $res['status'] = 0;
            $res['message'] = "Unexpected error occured. Please try again";
        }
    } else {
          $res['status'] = 0;
          $res['message'] = "Invalid file type";
    }
} else {
    $res['status'] = 0;
    $res['message'] = "Session timeout error. Please login again";
}

print(json_encode($res));

?>