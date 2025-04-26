<?
require_once("../objects/user.php");

$user = new User;

$data = json_decode(file_get_contents("php://input"));

if (empty($data->login) || empty($data->password)){
   Core\Error::send("api/not-enough-params");
}
else{
    $user->login = $data->login;
    $user->password = $data->password;
    $result = $user->auth();
    
    if($result['status']=='error'){
        Core\Error::send($result['code']);
    }
    else{
        Core\Responser::send($result['payload']);
    }
}
?>