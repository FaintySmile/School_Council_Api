<?
require_once("../objects/article.php");

$article = new Article;

$data = json_decode(file_get_contents("php://input"));

if (empty($data->id) || empty($data->count)){
   Core\Error::send("api/not-enough-params");
}
else{
    $result = $article->getMany($data->id, $data->count, $data->type, $data->offset);
    if($result['status']=='error'){
        Core\Error::send($result['code']);
    }
    else{
        Core\Responser::send($result['payload']);
    }
}
?>