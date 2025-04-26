<?
require_once($_SERVER['DOCUMENT_ROOT']."/dev/api/config/database.php");
require_once($_SERVER['DOCUMENT_ROOT']."/dev/api/config/core.php");
$db = new Database();

class Product {
    private $tbl = "products";

    public $id;
    public $name;
    public $description;
    public $price;
    public $icon;
    
    public function getAll(){
        return R::getAll("SELECT * FROM ".$this->tbl);
    }
    
}
?>