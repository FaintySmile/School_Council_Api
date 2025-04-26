<?
require_once("../objects/article.php");

$article = new Article;

$data = json_decode(file_get_contents("php://input"));

if (empty($data->id)){
   Core\Error::send("api/not-enough-params");
}
else{
    $article->id = $data->id;
    $result = $article->get();
    if($result['status']=='error'){
        Core\Error::send($result['code']);
    }
    else{
        Core\Responser::send($result['payload']);
    }
}
?>