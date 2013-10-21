<?php
session_start();

if (!isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username'])){
    exit();
}

require_once("../../vars.php");
require_once("../../includes/user.class.php");

if (isset($_POST['user_ids']) && $_POST['user_ids']){
    $user = new User();
    $user_ids = explode(",",$_POST['user_ids']);
    if (count($user_ids)){
        foreach($user_ids as $key => $user_id){
            $user->deleteUser($user_id);
        }
    }
}