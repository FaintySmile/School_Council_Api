<?
require_once("../objects/record.php");

$record = new Record;

$result = $record->getTop();
if($result['status']=='error'){
    Core\Error::send($result['code']);
}
else{
    Core\Responser::send($result['payload']);
}
?>