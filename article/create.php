<?
date_default_timezone_set('Asia/Almaty');
require_once("../objects/article.php");

$article = new Article;

if (empty($_POST['title']) || empty($_POST['access_token'])){
   Core\Error::send("api/not-enough-params");
}
else{
    if(!Core\JWT::check($_POST['access_token'])){
        Core\Error::send("api/token-not-correct");
    }
    else{
        $payload = explode(".", $_POST['access_token'])[1];
        $article->creator_id = json_decode(base64_decode($payload))->id;
        $article->title = $_POST['title'];
        $article->body = $_POST['body'];
        $article->date = date('Y-m-d H:i:s');
        $prev_name = uniqid().$_FILES['preview']['name'];
        Core\Service::resizePhoto("../../uploads/img/", $prev_name, $_FILES['preview']['size'], $_FILES['preview']['type'], $_FILES["preview"]["tmp_name"]);
        $article->preview = $prev_name;
        $result = $article->create();
        if($result['status']=='error'){
            Core\Error::send($result['code']);
        }
        else{
            Core\Responser::send($result['payload']);
        }
    }
}
?>