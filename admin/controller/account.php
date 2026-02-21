<?php
if(!defined('BASEPATH')) {
   die('Direct access to the script is not allowed');
}

if (!$admin["access"]["super_admin"]){
header("Location:" . site_url(""));
}

if( route(1) == "account" && $_POST ){

  $pass     = md5(sha1(md5($_POST["current_password"])));
  $new_pass = $_POST["password"];
  $new_again = $_POST["confirm_password"];
$old = $admin["password"];
  if(	$old != $pass ){
$error      = 1;
                $errorText  = "Error";
                $icon       = "error";
  }elseif( strlen($new_pass) < 8 ){
    $error    = 1;
    $errorText= $languageArray["error.account.password.length"];
  }elseif( $new_pass != $new_again ){
    $error    = 1;
    $errorText= $languageArray["error.account.passwords.notmach"];
  }else{
   
$update = $conn->prepare("UPDATE admins SET password=:pass WHERE admin_id=:id ");
$update = $update->execute(array("id"=>$admin["admin_id"],"pass"=>md5(sha1(md5($new_pass))) ));
header("Location:".site_url("admin/logout"));
  }

}
require admin_view('account');
?>
