<?php
if(!defined('BASEPATH')) {
   die('Direct access to the script is not allowed');
}
if( $admin["access"]["admin_access"] != 1  ){
    header("Location:".site_url());
    exit();
}
require admin_view('access');
?>
