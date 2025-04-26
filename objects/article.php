<?
require_once($_SERVER['DOCUMENT_ROOT']."/dev/api/config/database.php");
require_once($_SERVER['DOCUMENT_ROOT']."/dev/api/config/core.php");
$db = new Database();

class Article {
    private $tbl = "articles";

    public $id;
    public $title;
    public $body;
    public $preview;
    public $creator_id;
    public $date;
    
    public function getMany($id, $count, $type, $offset){
        if($type=="club"){
            $query = "SELECT articles.id AS article_id, title, body, preview, creator_id, date FROM 
            articles JOIN clubs ON articles.creator_id=clubs.leader_id WHERE clubs.id=:id ORDER BY article_id DESC LIMIT :count OFFSET
            :offset";
            $articles = R::getAll($query,[
                ":id"=>$id,
                ":count"=>$count,
                ":offset"=>$offset
            ]);
        }
        else if($type=="council"){
            $query = "SELECT articles.id AS article_id, title, body, preview, creator_id, date FROM 
            articles JOIN users ON articles.creator_id=users.id WHERE users.role='admin' ORDER BY article_id DESC 
            LIMIT :count OFFSET :offset";
            $articles = R::getAll($query,[
                ":count"=>$count,
                ":offset"=>$offset
            ]);
        }

        if($articles){
            return ["status"=>"success", "payload"=>[
                "articles"=>$articles
            ]];
        }
        else{
            return ["status"=>"error", "code"=>"article/fetch/no-records"];
        }
    }
    
    public function get(){
        $query = "SELECT clubs.name AS entity_name, articles.id AS article_id, title, body, preview, creator_id, date FROM 
            articles LEFT JOIN clubs ON articles.creator_id=clubs.leader_id WHERE articles.id=:id";
        $article = R::getRow($query,[
            ":id"=>$this->id
        ]);
        if($article){
            return ["status"=>"success", "payload"=>[
                "article"=>$article
            ]];
        }
        else{
            return ["status"=>"error", "code"=>"article/fetch/no-article"];
        }
    }
    
    public function create(){
       /* $target = "../../uploads/img/".basename($this->preview);
        move_uploaded_file($this->preview, $target); */
        $article = R::dispense($this->tbl);
        $article->title = $this->title;
        $article->body = $this->body;
        $article->preview =/* basename($this->preview); */ $this->preview;
        $article->creator_id = $this->creator_id;
        $article->date = $this->date;
        $result = R::store($article);
        if($result){
            return ["status"=>"success", "payload"=>[
            ]];
        }
        else{
            return ["status"=>"error", "code"=>"article/create/no-article"];
        }
    }
}
?>