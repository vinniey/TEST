<?php
class RedisTest {
	private $_host = "172.17.12.31";
	private $_port = "6379";
	private $_redis;
	
	
	public function __construct() {
		$this->_redis = new Redis();
		$this->_redis->connect($this->_host, $this->_port);
	}

	public function setData($len) {
		if ($len <=0 ) {
			exit("\$len must > 0 \n");
		}
		$start  = time();
		$list_name = "test";
		if ($this->_redis->exists($list_name)) {
			$this->_redis->del($list_name);
		}
		for ($i = 0; $i < $len ; $i++) {
			$data = array();
			$data['cntvid'] =  $this->randomString(16);
			$data['mac']	   = $this->randomString(16);
			$data['userid']	   = $this->randNumber(11);
			$data['adid']	   = $this->randNumber(10);
			$data['pgid']	   = $this->randNumber(10);
			$data['typeid']	   = $this->randNumber(10);
			$data['showtime'] = $this->randNumber(10);
			$data_str = json_encode($data);
			$r = $this->_redis->lpush($list_name, $data_str);
		}
		$end = time();
		$t = $end-$start;
		echo "set Data End, cost $t s\n";
	}
	
	public function randomString($len) {
		//65 - 90   97 - 122
		$rand_arr = array(65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,
				89,90,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,
				117,118,119,120,121,122);
	
		$s_arr = array();
		for($i = 0; $i < $len; $i++) {
			$s_arr[] = chr($rand_arr[array_rand($rand_arr, 1)]);
		}
		shuffle($s_arr);
		return implode('', $s_arr);
	}
	
	public function randNumber($len) {
		$s = (string) rand(1, 9);
		$len--;
		for($i = 0 ; $i < $len; $i++) {
			$s .= rand(0, 9);
		}
		return $s;
	}
	
}

$redistest = new RedisTest();
$redistest->setData(1000000);
