<?
require_once("../objects/record.php");

$record = new Record;

if (empty($_POST['type']) || empty($_POST['access_token'])){
   Core\Error::send("api/not-enough-params");
}
else{
    if(!Core\JWT::check($_POST['access_token'])){
        Core\Error::send("api/token-not-correct");
    }
    else{
        $payload = json_decode(base64_decode(explode(".", $_POST['access_token'])[1]));
        if(!Core\Security::checkRole('record/create', $payload->role)){
            Core\Error::send("api/user-no-permission");
        }
        else{
            $record->shanyrak_id = $payload->role=='admin' ? $_POST['shanId'] : $payload->shanId;
            $record->type = $_POST['type'];
            if($_POST['type_i']!='manual'){
                $record->type = $_POST['type'];
                $record->service = [
                    "type_i"=>$_POST['type_i'],
                    "info_1"=>$_POST['info_1'],
                    "info_2"=>$_POST['info_2'],
                    "students_count"=>$_POST['students_count']
                ];
                $record->proof = $_POST['proof'];
            }
            else{
                $record->service = [
                    "type_i"=>$_POST['type_i'],
                ];
                $record->type = $_POST['type'];
                $record->points = $_POST['points_count'];
                $record->proof = $_POST['proof'];
            }
            $record->service_proof = $_POST['service_proof'];
            $result = $record->create();
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