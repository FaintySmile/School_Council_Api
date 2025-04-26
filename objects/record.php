<?
require_once($_SERVER['DOCUMENT_ROOT']."/dev/api/config/database.php");
require_once($_SERVER['DOCUMENT_ROOT']."/dev/api/config/core.php");
$db = new Database();

class Record {
    private $tbl = "records";

    public $id;
    public $type;
    public $points;
    public $proof;
    public $service_proof;
    public $shanyrak_id;
    
    public $service;
    
    private static $fill_manager = [
        "dynamic_projects"=>['Школьные проекты', 'Шанырак', 'Нарушения'],
        "static_projects"=>[
            "school"=>[
                "first"=>8,
                "second"=>6,
                "third"=>4,
                "participation"=>1
            ],
            "city"=>[
                "first"=>12,
                "second"=>10,
                "third"=>8,
                "participation"=>4
            ],
            "district"=>[
                "first"=>20,
                "second"=>16,
                "third"=>12,
                "participation"=>8
            ],
            "republic"=>[
                "first"=>40,
                "second"=>30,
                "third"=>20,
                "participation"=>12
            ],
            "international"=>[
                "first"=>100,
                "second"=>80,
                "third"=>60,
                "participation"=>30
            ],
            "dist_republic"=>[
                "first"=>18,
                "second"=>15,
                "third"=>12,
                "participation"=>5
            ],
            "dist_international"=>[
                "first"=>24,
                "second"=>20,
                "third"=>16,
                "participation"=>8
            ]
        ],
        "school_projects"=>[
            "event_organization"=>[
                "shanyrak"=>3,
                "parallel"=>8,
                "school"=>10,
                "city"=>15
            ],
            "nis_talks"=>[
                "school"=>4,
                "city"=>10
            ],
            "wikipedia"=>[
                "general"=>6,
            ],
            "smart_thursday"=>[
                "organization"=>10
            ],
            "gardening"=>[
                "general"=>2
            ],
            "society_service"=>[
                "organization"=>7,
                "participation"=>1
            ],
        ]
    ];
    
    public function create(){
        $record = R::dispense($this->tbl);
        $record->type = $this->type;
        $record->shanyrak_id = $this->shanyrak_id;
        $record->proof = $this->proof;
        $record->service_proof = $this->service_proof;
        if($this->service['type_i']!='manual'){
            if(in_array($this->type, self::$fill_manager['dynamic_projects'])){
                $record->points = self::$fill_manager[$this->service['type_i']][$this->service['info_1']][$this->service['info_2']]*
                $this->service['students_count'];
            }
            else{
                $record->points = self::$fill_manager['static_projects'][$this->service['info_1']][$this->service['info_2']]*
                $this->service['students_count'];
            }
        }
        else{
            $record->points = $this->points;
        }
        
        $result = R::store($record);
        if($result){
            return ["status"=>"success", "payload"=>[
            ]];
        }
        else{
            return ["status"=>"error", "code"=>"record/create/fail"];
        }
    }
    
    public function getMany(){
        $query = "SELECT * FROM records WHERE shanyrak_id=:shanyrak_id";
        $records = R::getAssoc($query,[
            ":shanyrak_id"=>$this->shanyrak_id,
        ]);

        if($records){
            return ["status"=>"success", "payload"=>[
                "records"=>$records
            ]];
        }
        else{
            return ["status"=>"error", "code"=>"record/fetch/no-records"];
        }
    }
    
    public function getTop(){
        $query = "SELECT shanyraks.id AS shanyrak_id, shanyraks.name, SUM(records.points) AS points FROM shanyraks JOIN records ON shanyraks.id=records.shanyrak_id GROUP BY shanyraks.name ORDER BY points DESC";
        $top = R::getAll($query,[
            ":shanyrak_id"=>$this->shanyrak_id,
        ]);

        if($top){
            return ["status"=>"success", "payload"=>[
                "top"=>$top
            ]];
        }
        else{
            return ["status"=>"error", "code"=>"record/fetch/no-top"];
        }
    }
    
    public function delete(){
        $result = R::hunt($this->tbl, "id = ? AND shanyrak_id = ?", [$this->id, $this->shanyrak_id]);
        if($result){
            return ["status"=>"success", "payload"=>[
            ]];
        }
        else{
            return ["status"=>"error", "code"=>"record/delete/error"];
        }
    }
}
?>