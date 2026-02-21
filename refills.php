<?php
ignore_user_abort(true);
define("BASEPATH", true);
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/app/init.php';


$a = $conn->prepare("ALTER TABLE `admin_constants` ADD `paidRent` BOOLEAN NOT NULL DEFAULT FALSE AFTER `brand_logo`;");

$a->execute();


?>