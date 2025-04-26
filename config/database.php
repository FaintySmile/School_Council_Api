<?
require_once($_SERVER['DOCUMENT_ROOT']."/dev/libs/rb-mysql.php");
class Database {
    private $host = "";
    private $db_name = "";
    private $username = "";
    private $password = "";

    function __construct(){
        try {
            R::setup( "mysql:host=localhost;dbname=$this->db_name", $this->username, $this->password, false);
        } 
        catch(Exception $exception){
            echo "Connection error: " . $exception;
        }
    }
}