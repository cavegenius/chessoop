<?php
	class chess_game { 
		public $auth;
	
		public function new_game() {
			$db = new Database();
			$db->connect();
			$this->auth =  $this->RandomString(32);
			$query = "INSERT INTO `chessboard` (`id` , `auth` , `turn` ,`a1` ,`a2` ,`a3` , `a4` , `a5` , `a6` , `a7` , `a8` , `b1` , `b2` , `b3` , `b4` , `b5` , `b6` , `b7` , `b8` , `c1` , `c2` , `c3` , `c4` , `c5` , `c6` , `c7` , `c8` , `d1` , `d2` , `d3` , `d4` , `d5` , `d6` , `d7` , `d8` , `e1` , `e2` , `e3` , `e4` , `e5` , `e6` , `e7` , `e8` , `f1` , `f2` , `f3` , `f4` , `f5` , `f6` , `f7` , `f8` , `g1` , `g2` , `g3` , `g4` , `g5` , `g6` , `g7` , `g8` , `h1` , `h2` , `h3` , `h4` , `h5` , `h6` , `h7` , `h8`) VALUES (
			NULL , '".$this->auth."', 'white', 'wr', 'wp', NULL , NULL , NULL , NULL , 'bp', 'br', 'wn', 'wp', NULL , NULL , NULL , NULL , 'bp', 'bn', 'wb', 'wp', NULL , NULL , NULL , NULL , 'bp', 'bb', 'wq', 'wp', NULL , NULL , NULL , NULL , 'bp', 'bq', 'wk', 'wp', NULL , NULL , NULL , NULL , 'bp', 'bk', 'wb', 'wp', NULL , NULL , NULL , NULL , 'bp', 'bb', 'wn', 'wp', NULL , NULL , NULL , NULL , 'bp', 'bn', 'wr', 'wp', NULL , NULL , NULL , NULL , 'bp', 'br'
			);";
			mysql_query($query);
		}
		
		public function load_game($auth) {			
			$chessboard = new board($auth);
			$chessboard->drawboard();
			
			
		}
		
		function get_auth() {
			return $this->auth;
		}
		
		public function RandomString($len){
			$randstr = '';
			srand((double)microtime()*1000000);
			for($i=0;$i<$len;$i++){
				$n = rand(48,120);
				while (($n >= 58 && $n <= 64) || ($n >= 91 && $n <= 96)){
					$n = rand(48,120);
				}
				$randstr .= chr($n);
			}
			return $randstr;
		}
	}
?>