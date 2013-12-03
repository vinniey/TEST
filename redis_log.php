<?php
class RedisLog {
	private $_host = "172.17.12.31";
	private $_port = "6379";
	private $_redis;
	private $_list_name = 'test';
	private $_db_host = "172.17.16.5";
	private $_db_user = "icntv";
	private $_db_name = "icntv_kuaibo";
	private $_db_passwd = "icntv";
	private $_pdo;
	private $_log_len = 100000;
	
	public function __construct() {
		$this->_redis = new Redis ();
		$this->_redis->connect ( $this->_host, $this->_port );
		try {
			$this->_pdo = new PDO ( "mysql:dbname=$this->_db_name;host=$this->_db_host;", 
					$this->_db_user, $this->_db_passwd, array(PDO::MYSQL_ATTR_LOCAL_INFILE => true));
			//$this->_db_user, $this->_db_passwd);
			$this->_pdo->query ( "SET NAMES 'utf8'" );
		} catch ( PDOException $e ) {
			echo 'Connect db failed ' . $e->getMessage () . "\n";
		}
	}
	public function logFromRedisInsert() {
		if ($this->_log_len <= 0) {
			exit ( "log_len too small" );
		}
		$len = $this->_redis->lLen ( $this->_list_name );
		if ($len < $this->_log_len) {
			sleep ( 1 );
		} else {
			$sql = "INSERT  INTO `ads_log` (`cntvid`, `mac`, `userid`, `adid`, `pgid`, `typeid`, `showtime`) VALUES ";
			$i = $this->_log_len;
			while ( $i ) {
				$r = $this->_redis->rPop ( $this->_list_name );
				if ($r != false) {
					$r = json_decode ( $r, 1 );
					$sql .= "('{$r['cntvid']}', '{$r['mac']}', '{$r['userid']}', '{$r['adid']}', '{$r['pgid']}', '{$r['typeid']}', '{$r['showtime']}'),";
				}
				$i --;
			}
			$sql = trim ( $sql, ',' );
			try {
				$stmt = $this->_pdo->prepare( $sql );
				$r = $stmt->execute();
			} catch (PDOException $e) {
				$e->getMessage();exit;
			}
		}
		// $this->logFromRedis();
	}
	public function logFromRedisLoad() {
		if ($this->_log_len <= 0) {
			exit ( "log_len too small" );
		}
		$len = $this->_redis->lLen ( $this->_list_name );
		if ($len < $this->_log_len) {
			sleep ( 1 );
		} else {
			
			$buffer = fopen ( 'temp.csv', 'w' );
			$i = $this->_log_len;
			while ( $i ) {
				$r = $this->_redis->rPop ( $this->_list_name );
				if ($r != false) {
					$r = json_decode ( $r, 1 );
					// `cntvid`, `mac`, `userid`, `adid`, `pgid`, `typeid`, `showtime`
					$temp = array (
							$r ['cntvid'],
							$r ['mac'],
							$r ['userid'],
							$r ['adid'],
							$r ['pgid'],
							$r ['typeid'],
							$r ['showtime']
					);
					fputcsv ( $buffer, $temp );
				}
				$i --;
			}
			fclose ( $buffer );
			$start = microtime(true);
			echo "<br/>start -load <br/>";
			$this->loadData();
			$cost = microtime(true) - $start;
			echo "<br/>end -load $cost<br/>";
		}
		// $this->logFromRedisLoad();
	}
	public function loadData() {
		$sql = 'LOAD DATA LOCAL INFILE \'/data/wwwroot/www.yaf.com/temp.csv\' INTO TABLE `ads_log` FIELDS escaped by \'\\\\\' terminated by \',\'  enclosed by \'"\'  lines terminated by \'\n\' (`cntvid`, `mac`, `userid`, `adid`, `pgid`, `typeid`, `showtime`)';
		try {
			$stmt = $this->_pdo->prepare($sql);
			$stmt->execute();
			
		} catch ( PDOException $e ) {
			echo $e->getMessage ();
		}
	}
	
	/* public function test() {
		//INSERT INTO `ads_log` (`cntvid`, `mac`, `userid`, `adid`, `pgid`, `typeid`, `showtime`) VALUES ('wnxGUymBAZuPabkC', 'uFwuWQhqvItspJDb', '53240784446', '7230380873', '9093124675', '2185253708', '4716745832')
		$stmt = $this->_pdo->prepare("INSERT INTO `ads_log` (`cntvid`, `mac`, `userid`, `adid`, `pgid`, `typeid`, `showtime`) VALUES ('wnxGUymBAZuPabkC', 'uFwuWQhqvItspJDb', '53240784446', '7230380873', '9093124675', '2185253708', '4716745832')");
		$stmt->execute();
	} */
	
	
	public function logToMongo() {
		if ($this->_log_len <= 0) {
			exit ( "log_len too small" );
		}
		//phpinfo();exit;
		$this->_mgdb = new Mongo();
		$this->_mgdb->connect();
		$db = $this->_mgdb->foobar;
		$collection = $db->log;
		$len = $this->_redis->lLen ( $this->_list_name );
		if ($len < $this->_log_len) {
			sleep ( 1 );
		} else {
			$i = $this->_log_len;
			while ( $i ) {
				$r = $this->_redis->rPop ( $this->_list_name );
				if ($r != false) {
					$collection->insert(json_decode($r, 1));		
				}
				$i --;
			}
			
		} 
		
	}
	
	
	
	
}



$start = microtime ( true );
$log = new RedisLog ();
//$log->logFromRedisInsert();
$log->logFromRedisLoad();
//$log->test();
//$log->logToMongo();
$cost = microtime ( true ) - $start;

echo "end log $cost";
