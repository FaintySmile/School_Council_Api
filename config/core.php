<?
namespace Core;
    /*ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);*/
    
    //Configuring headers to create a corresponding api response in the form of json
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    //Class that is responsible for security functions     
    class Security{
        private static $masterSalt = ENV['master_salt']; //Random string цhich is added to the password to avoid hash recognition through the hash table
        private static $roles_commands = [
            "user/create"=>['admin'],
            "record/delete"=>['shan_leader', 'admin'],
            "record/create"=>['shan_leader', 'admin']
        ];
        
        public static function hash($password){
            return md5($password.self::$masterSalt);
        }
        
        public static function checkRole($command, $role){
            if(in_array($role, self::$roles_commands[$command])){
                return true;
            }
            else{
                return false;
            }
        }
    }
    
    //Class responsible for generating json web token
    class JWT{
        private static $secret = "REyr6TdsSvy3QMLs";
        public static function get($payload){
            $header = base64_encode(json_encode((object)["alg"=>"HS256","typ"=>"JWT"]));
            $payload = base64_encode(json_encode((object)$payload));
            $signature = hash_hmac("sha256", $header.$payload, self::$secret);
            return $header.".".$payload.".".$signature;
        }
        
        public static function check($token){
            $token = explode(".", $token);
            $signature = hash_hmac("sha256", $token[0].$token[1], self::$secret);
            if($token[2]==$signature){
                return true;
            }
            else{
                return false;
            }
        }
    }
    
    //Class responsible for generating response for client-side in the form of json
    class Responser{
        private static $response = [
            "status"=>"success"    
        ];
        
        public static function send($payload = ""){
            self::$response["payload"] = $payload;
            exit(json_encode(self::$response, JSON_UNESCAPED_UNICODE));
        }
    }
    
    //Class responsible for generating error for client-side in the form of json
    class Error{
        private static $errors = [
            "user/auth/incorrect"=>"Неверное имя пользователя или пароль, либо такого пользователя не существует.",
            "api/not-enough-params"=>"Недостаточно параметров для выполнения запроса.",
            "api/token-not-correct"=>"Вы не тот, за кого себя выдаёте.",
            "article/fetch/no-records"=>"Отсутствует записи для данной сущности(клуба).",
            "article/fetch/no-article"=>"Данная новость была удалена.",
            "user/create/error"=>"Ошибка при создании нового аккаунта.",
            "api/user-no-permission"=>"Пользователь не имеет прав на выполнение данной команды",
            "user/create/login-already-exists"=>"Пользователь с таким логином уже существует",
            "record/fetch/no-records"=>"Отсутствуют какие-либо записи."
        ];
        private static $response = [
            "status"=>"error"   
        ];
        public static function send($code){
            self::$response["code"] = $code;
            self::$response["description"] = self::$errors[$code];
            exit(json_encode(self::$response, JSON_UNESCAPED_UNICODE));
        }
    }
    
    class Permission{
        
    }
    
    //Class with service functions
    class Service{
        public static function resizePhoto($path,$filename,$filesize,$type,$tmp_name){ // function for decreasing image size
            $quality = 45; 
            $size = 10485760; 
            switch($type){
                case 'image/jpeg': $source = imagecreatefromjpeg($tmp_name); break; 
                case 'image/png': $source = imagecreatefrompng($tmp_name); break;    
                case 'image/gif': $source = imagecreatefromgif($tmp_name); break; 
                default: return false;
            }
            imagejpeg($source, $path.$filename, $quality); 
            imagedestroy($source);
            return true;
        }
    }

?>