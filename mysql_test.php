<?php

class DbTest {
	private $host = '172.17.12.31';
	private $user = 'root';
	private $passwd = '123456';
	private $db_select = 'sampledb';
	private $charset = 'utf8';
	private $pdo;
	
	
	public function __construct() {
		$this->pdo = new PDO("mysql:dbname=$this->db_select;host=$this->host" , 
				$this->user, $this->passwd);
		$this->pdo->query("SET NAMES 'utf8'");
	}
	
	public function setData($num) {
		if ($num <= 0) {
			exit("\$num to low");
		}
		$sql = 'INSERT INTO `adlist` (`ad_name`, `ad_desc`, `ad_url`, `ad_time`, `ad_tactics`, `ad_stat`, `ad_add_time`, `ad_last_edit_time`) 
				VALUES ';
		//$stm = $this->pdo->prepare($sql);
		for ($i =0; $i < $num; $i ++) {
			/* $stm->execute(array(
					$this->randomString(10),
					$this->randomString(50),
					$this->randomString(50),
					$this->randNumber(10),
					$this->randNumber(10),
					rand(0,5),
					$this->randNumber(10),
					$this->randNumber(10)
			)); */
		
			$sql_e = $sql . "('". $this->randomString(10). "', '" . $this->randomString(50) . "', '" .$this->randomString(50) ."',".
			 $this->randNumber(8) . "," . $this->randNumber(9) . ",'" . rand(0,5) . "', '". $this->randNumber(9) . 
			 "', '" . $this->randNumber(9) . "')";
			$this->pdo->exec($sql_e);
			/* var_dump(array(
					$this->randomString(10),
					$this->randomString(50),
					$this->randomString(50),
					$this->randNumber(10),
					$this->randNumber(10),
					rand(0,5),
					$this->randNumber(10),
					$this->randNumber(10)
			));exit; */
		}
		
	}

	
	public function selectTest($random = false, $max = 10000) {
		$id = 987;
		if ($random) {
			$id = rand(0, $max);
		}
		$sql = "SELECT * FROM `adlist` WHERE ad_id=?";
		$stm = $this->pdo->prepare($sql);
		$stm->execute(array($id));
		$r = $stm->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($r);
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


$test = new DbTest();
//$test->setData(1000);

$r = $test->selectTest();





