<?php

	class king extends piece {
		public $shortcode;
		public $color;

		public function displaypiece($color) {
			if($color=='white') {
				return "<img src=\"images/wk.gif\" />";
			} else if($color=='black') { 
				return "<img src=\"images/bk.gif\" />";
			}
		} 
		
		public function set_shortcode($color){
			if ($color=="black") {
				$this->shortcode = "bk";
			} else if ($color=="white") {
				$this->shortcode = "wk";
			}
		}
		
		public function get_shortcode() {
			return $this->shortcode;
		}
		
		public function move($from, $to, $shortcode, $auth) {		
			if ($this->findcolor($shortcode)=="white") {
				if ($this->checkturn($auth)=="white") {
					if($this->whitekingvalidate($from, $to, $shortcode, $auth)==True) {
						if ($this->incheck($from, $to, $shortcode, $auth)==false) {
							$query = "UPDATE chessboard SET turn='black', ".$from."='', ".$to."='".$shortcode."', last_from='".$from."', last_to='".$to."'  WHERE `auth`='".$auth."'";	
							$result = mysql_query($query);	
							if (!$result) {
								$message  = 'Invalid query: ' . mysql_error() . "\n";
								$message .= 'Whole query: ' . $query;
							}
						} else {
							$_SESSION['error'] = 'You can not make this move as you would be in check';
						}
					} else {
						$_SESSION['error'] = "You made an invalid move";
					}
				} else {
					$_SESSION['error'] = "it is not your turn";
				}
			} else if ($this->findcolor($shortcode)=="black") {
				if ($this->checkturn($auth)=="black") {
					if($this->blackkingvalidate($from, $to, $shortcode, $auth)==True) {
						if ($this->incheck($from, $to, $shortcode, $auth)==false) {
							$query = "UPDATE chessboard SET turn='white', ".$from."='', ".$to."='".$shortcode."', last_from='".$from."', last_to='".$to."'  WHERE `auth`='".$auth."'";	
							$result = mysql_query($query);	
							if (!$result) {
								$message  = 'Invalid query: ' . mysql_error() . "\n";
								$message .= 'Whole query: ' . $query;
							}
						} else {
							$_SESSION['error'] = 'You can not make this move as you would be in check';
						}
					} else {
						$_SESSION['error'] = "You made an invalid move";
					}
				} else {
					$_SESSION['error'] = "it is not your turn";
				}
			}
		}
		
		public function whitekingvalidate($from, $to, $shortcode, $auth) {
			$v = $from[1];
			$h = ord($from[0]);
			$validmoves = array();
			$upleft=chr(($h-1)).($from[1]+1);
			$up= $from[0].($from[1]+1);
			$upright = chr(($h+1)).($from[1]+1);
			$right = chr(($h+1)).$from[1];
			$downright = chr(($h+1)).($from[1]-1);
			$down =  $from[0].($from[1]-1);
			$downleft = chr(($h-1)).($from[1]-1);
			$left = chr(($h-1)).$from[1];
			$movelist = array($upleft, $up, $upright, $right, $downright, $down, $downleft, $left);
			$db = new Database();
			$db->connect();
			foreach($movelist as $movelists) {
				if (($movelists[1] <= '8') && ($movelists[1] >= '1') && (ord($movelists[0]) <= '104') && (ord($movelists[0]) >= '97')){
					$query = "SELECT $movelists FROM `chessboard` WHERE `auth`='$auth'";
					$result = mysql_query($query) or die(mysql_error());
					$row = mysql_fetch_array($result, MYSQL_ASSOC);
					$vmove = "{$row[$movelists]}";
					if ($vmove[0] == '' || $vmove[0] == 'b') {
						array_push($validmoves, $movelists);
					}
				}
			}
			
			if (empty($validmoves)) {
				
			} else {
				$key = array_search($to, $validmoves);
			}
			if (is_int($key)) {

			} else {
				$key = '-1';
			}
				
			if ($key >= '0') {	
				if ($from && $to) {
					return True;
				}
			} else {
				return False;
			}
		}
		
		public function blackkingvalidate($from, $to, $shortcode, $auth) {
			$v = $from[1];
			$h = ord($from[0]);
			$validmoves = array();
			$upleft=chr(($h-1)).($from[1]+1);
			$up= $from[0].($from[1]+1);
			$upright = chr(($h+1)).($from[1]+1);
			$right = chr(($h+1)).$from[1];
			$downright = chr(($h+1)).($from[1]-1);
			$down =  $from[0].($from[1]-1);
			$downleft = chr(($h-1)).($from[1]-1);
			$left = chr(($h-1)).$from[1];
			$movelist = array($upleft, $up, $upright, $right, $downright, $down, $downleft, $left);
			$db = new Database();
			$db->connect();
			foreach($movelist as $movelists) {
				if (($movelists[1] < '8') && ($movelists[1] > '1') && (ord($movelists[0]) < '104') && (ord($movelists[0]) > '97')){
					$query = "SELECT $movelists FROM `chessboard` WHERE `auth`='$auth'";
					$result = mysql_query($query) or die(mysql_error());
					$row = mysql_fetch_array($result, MYSQL_ASSOC);
					$vmove = "{$row[$movelists]}";
					if ($vmove[0] == '' || $vmove[0] == 'w') {
						array_push($validmoves, $movelists);
					}
				}
			}
			
			if (empty($validmoves)) {
				
			} else {
				$key = array_search($to, $validmoves);
			}
			if (is_int($key)) {

			} else {
				$key = '-1';
			}
				
			if ($key >= '0') {	
				if ($from && $to) {
					return True;
				}
			} else {
				return False;
			}
		}

	}

?>