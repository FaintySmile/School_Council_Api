<?
require_once("../objects/user.php");

$user = new User;

if (empty($_POST['login']) || empty($_POST['access_token'])){
   Core\Error::send("api/not-enough-params");
}
else{
    if(!Core\JWT::check($_POST['access_token'])){
        Core\Error::send("api/token-not-correct");
    }
    else{
        $payload = json_decode(base64_decode(explode(".", $_POST['access_token'])[1]));
        if(!Core\Security::checkRole('user/create', $payload->role)){
            Core\Error::send("api/user-no-permission");
        }
        else{
            $user->login = $_POST['login'];
            $user->name = $_POST['name'];
            $user->password = $_POST['password'];
            $user->role = $_POST['role'];
            $result = $user->create();
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