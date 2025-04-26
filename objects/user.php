<?
require_once($_SERVER['DOCUMENT_ROOT']."/dev/api/config/database.php");
require_once($_SERVER['DOCUMENT_ROOT']."/dev/api/config/core.php");
$db = new Database();

class User {
    private $tbl = "users";

    public $id;
    public $login;
    public $name;
    public $role;
    public $password;
    
    public function auth(){
        $query = "SELECT users.id, users.name AS user_name, users.role, shanyraks.name AS shan_name,
        shanyraks.id AS shan_id, clubs.name AS club_name, clubs.id AS club_id FROM users LEFT JOIN shanyraks ON
        users.id=shanyraks.leader_id 
        LEFT JOIN clubs ON users.id=clubs.leader_id WHERE users.login=:login AND users.password=:password";
        $auth = R::getRow($query,[
            ":login"=>$this->login,
            ":password"=>Core\Security::hash($this->password)
        ]);
        if($auth){
            $payload = [
                "id"=>$auth['id'],
                "role"=>$auth['role'],
                "userName"=>$auth['user_name']
            ];
            $auth['shan_name']!=null ? $payload['shanName'] = $auth['shan_name'] : null;
            $auth['shan_id']!=null ? $payload['shanId'] = $auth['shan_id'] : null;
            
            $auth['club_name']!=null ? $payload['clubName'] = $auth['club_name'] : null;
            $auth['club_id']!=null ? $payload['clubId'] = $auth['club_id'] : null;
            return ["status"=>"success", "payload"=>[
                "token"=>Core\JWT::get($payload),
            ]];
        }
        else{
            return ["status"=>"error", "code"=>"user/auth/incorrect"];
        }
    }
    
    public function create(){
        if(R::find($this->tbl, 'login = ?', [$this->login])){
            return ["status"=>"error", "code"=>"user/create/login-already-exists"];
        }
        else{
            $user = R::dispense($this->tbl);
            $user->login = $this->login;
            $user->name = $this->name;
            $user->password = Core\Security::hash($this->password);
            $user->role = $this->role;
            $result = R::store($user);
            if($result){
                return ["status"=>"success", "payload"=>[
                ]];
            }
            else{
                return ["status"=>"error", "code"=>"user/create/error"];
            }
        }
    }
}
?>