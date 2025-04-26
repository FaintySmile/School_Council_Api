<?
require_once("../objects/record.php");

$record = new Record;

$data = json_decode(file_get_contents("php://input"));

if (empty($data->id) || empty($data->access_token)){
   Core\Error::send("api/not-enough-params");
}
else{
    if(!Core\JWT::check($data->access_token)){
        Core\Error::send("api/token-not-correct");
    }
    else{
        $payload = json_decode(base64_decode(explode(".", $data->access_token)[1]));
        if(!Core\Security::checkRole('record/delete', $payload->role)){
            Core\Error::send("api/user-no-permission");
        }
        else{
            $record->shanyrak_id = $payload->role=='admin' ? $data->shanId : $payload->shanId;
            $record->id = $data->id;
            $result = $record->delete();
            if($result['status']=='error'){
                Core\Error::send($result['code']);
            }
            else{
                Core\Responser::send($result['payload']);
            }
        }
    }
}
?>