<?php
date_default_timezone_set('PRC');
class Milk{
	public $redis ;
	public $name = "ziqing" ;
	public $set_key = "milkset" ;
	public $hash_key = "milkhash" ;
	public function getConnect(){
		$this->redis = new Redis();
		$this->redis->connect("redis",6379) ;
		// $this->redis->auth('pass');
	}
	public function getmsg(){
		
		$page = 1 ;
		$pageSize = !empty($_POST['pageSize'])?$_POST['pageSize']:5;
		$start = ($page-1)*$pageSize;
		$end = $start+$pageSize-1;

		$uuids = $this->redis->zRevRange($this->set_key.":".$this->name,$start,$end);
		$giftlist = [] ;
		$msg_arr = [] ;
		foreach($uuids as $uuid){
			# 从hash表取出记录
			$msg = $this->redis->hget($this->hash_key.":".$this->name,$uuid);
			if(empty($msg)){
				continue ;
			}
			$msg = json_decode($msg,true) ;
			
			if(!is_array($msg)){
				continue ;
			}
			$created_at = "[".$msg['created_at']."]" ;
			$minute = $msg['minute'] ?? "10";
			$msg_arr[] = "吃了" . $minute ."分钟 ". $created_at ;
		}
		return $msg_arr ;

	}

	public function save($minute){
		$created_at = date('Y-m-d H:i:s');
		$uuid = $this->UUID();

		$this->redis->zadd($this->set_key.":".$this->name,  time(), $uuid);
		$this->redis->hset($this->hash_key.":".$this->name, $uuid, json_encode(['minute' => $minute, 'created_at' => $created_at]));
		return $uuid;
	}

	public function UUID(){
        $uuid = '';
        if (function_exists('uuid_create') === true){
            $uuid = uuid_create(1);
        }else{
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40); 
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80); 
            $uuid =  vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }
        return $uuid;
}
}

$milk = new Milk() ;
$milk->getConnect() ;
$action = $_GET['action'];
switch($action){
	case 'list':
		$list = $milk->getmsg() ;
		echo json_encode(["list"=>$list]) ;
		break;
	case 'save':
		$minute = $_POST['minute'] ?? 10;
		$result = $milk->save($minute);
		echo $result;
		break;
	default:

}


