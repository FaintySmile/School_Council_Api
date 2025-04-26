<?
require_once("../objects/record.php");

$record = new Record;
$data = json_decode(file_get_contents("php://input"));

if (empty($data->shanyrak_id)){
   Core\Error::send("api/not-enough-params");
}
else{
    $record->shanyrak_id = $data->shanyrak_id;
    $result = $record->getMany();
    if($result['status']=='error'){
        Core\Error::send($result['code']);
    }
    else{
        Core\Responser::send($result['payload']);
    }
}
?>