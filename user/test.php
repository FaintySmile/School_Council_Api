<?
date_default_timezone_set('Asia/Almaty');
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once($_SERVER['DOCUMENT_ROOT']."/dev/api/config/database.php");

$db = new Database();
print_r(R::getAll("SELECT * FROM products WHERE time>=:date1 AND time<=:date2",[
    "date1"=>"2021-09-21",
    "date2"=>date('Y-m-d H:i:s')
    ]));
?>