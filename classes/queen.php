<?php

	class queen extends piece { 
		public $shortcode;
		public $color;

		public function displaypiece($color) {
			if($color=='white') {
				return "<img src=\"images/wq.gif\" />";
			} else if($color=='black') { 
				return "<img src=\"images/bq.gif\" />";
			}
		}	
		
		public function set_shortcode($color){
			if ($color=="black") {
				$this->shortcode = "bq";
			} else if ($color=="white") {
				$this->shortcode = "wq";
			}
		} 
		
		public function get_shortcode() {
			return $this->shortcode;
		}
		
		public function move($from, $to, $shortcode, $auth) {		
			if ($this->findcolor($shortcode)=="white") {
				if ($this->checkturn($auth)=="white") {
					if($this->whitequeenvalidate($from, $to, $shortcode, $auth)==True) {
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
					if($this->blackqueenvalidate($from, $to, $shortcode, $auth)==True) {
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
		
		public function whitequeenvalidate($from, $to, $shortcode, $auth) {
			$v = $from[1];
			$v2 = $from[1];
			$h = ord($from[0]);
			$h2 = ord($from[0]);
			// begin straight line moves
			$validmoves = array();
			if ($from[0] == $to[0]) {
				while (($vmove == NULL) && ($v < '8') && ($v >= '1')) {
					$move = $from[0].($v+1);
					$next = $v+1;
					$query = "SELECT $move FROM `chessboard` WHERE `auth`='$auth'";
					$result = mysql_query($query) or die(mysql_error());
					while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
						$vmove = "{$row[$move]}";
			
						if ($vmove[0] == '' || $vmove[0] == 'b') {
						array_push($validmoves, $move);		
						}
					}
					$v = $next++;
				}	
				while (($vmove2 == NULL) && ($v2 <= '8') && ($v2 > '1')) {
					$move2 = $from[0].($v2-1);
					$next2 = $v2-1;
					$query2 = "SELECT $move2 FROM `chessboard` WHERE `auth`='$auth'";
					$result2 = mysql_query($query2) or die(mysql_error());
					while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
						$vmove2 = "{$row2[$move2]}";
			
						if ($vmove2[0] == '' || $vmove2[0] == 'b') {
							array_push($validmoves, $move2);		
						}
					}
					$v2 = $next2--;
				}		
			} else if ($from[1] == $to[1]) {
				while (($hmove == NULL) && ($h < '104') && ($h >= '97')) {
					$move = chr(($h+1)).$from[1];
					$next = $h+1;
					$query = "SELECT $move FROM `chessboard` WHERE `auth`='$auth'";
					$result = mysql_query($query) or die(mysql_error());
					while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
						$hmove = "{$row[$move]}";
			    
						if ($hmove[0] == '' || $hmove[0] == 'b') {
							array_push($validmoves, $move);		
						}
					}
					$h = $next++;
				}	
				while (($hmove2 == NULL) && ($h2 <= '104') && ($h2 > '97')) {
					$move2 = chr(($h2-1)).$from[1];
					$next2 = $h2-1;
					$query2 = "SELECT $move2 FROM `chessboard` WHERE `auth`='$auth'";
					$result2 = mysql_query($query2) or die(mysql_error());
					while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
						$hmove2 = "{$row2[$move2]}";
			
						if ($hmove2[0] == '' || $hmove2[0] == 'b') {
							array_push($validmoves, $move2);		
						}
					}
					$h2 = $next2--;
				}
			}
			// end straight line moves
			
			// begin diagonal moves
			if ($from[1] < $to[1]) { 
				while (($vmove == NULL) && ($h < '103') && ($h >= '97') && ($v < '8') && ($v >= '1')) {
					$move = chr(($h+1)).($v+1);
					$next = $v+1;
					$next02 = $h+1;
					$query = "SELECT $move FROM `chessboard` WHERE `auth`='$auth'";
					$result = mysql_query($query) or die(mysql_error());
					while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
						$vmove = "{$row[$move]}";
			
						if ($vmove[0] == '' || $vmove[0] == 'b') {
							array_push($validmoves, $move);		
						}
					} 
					$v = $next++;
					$h = $next02++;
				}	
				while (($vmove2 == NULL) && ($h2 <= '103') && ($h2 > '97') && ($v2 < '8') && ($v2 >= '1')) {
					$move2 = chr(($h2-1)).($v2+1);
					$next2 = $h2-1;
					$next3 = $v2+1;
					$query2 = "SELECT $move2 FROM `chessboard` WHERE `auth`='$auth'";
					$result2 = mysql_query($query2) or die(mysql_error());
					while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
						$vmove2 = "{$row2[$move2]}";
			
						if ($vmove2[0] == '' || $vmove2[0] == 'b') {
							array_push($validmoves, $move2);		
						}
					}
					$h2 = $next2--;
					$v2 = $next3++;
				}
			} else if ($from[1] > $to[1]) { 
				while (($hmove == NULL) && ($h < '103') && ($h >= '97') && ($v <= '8') && ($v > '1')) {
					$move = chr(($h+1)).($v-1);
					$next = $h+1;
					$next02 = $v-1;
					$query = "SELECT $move FROM `chessboard` WHERE `auth`='$auth'";
					$result = mysql_query($query) or die(mysql_error());
					while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
						$hmove = "{$row[$move]}";
			    
						if ($hmove[0] == '' || $hmove[0] == 'b') {
							array_push($validmoves, $move);		
						}
					}
					$h = $next++;
					$v = $next02--;
				}
				while (($hmove2 == NULL) && ($v2 <= '8') && ($v2 > '1') && ($h2 <= '103') && ($h2 > '97')) {
					$move2 = chr(($h2-1)).($v2-1);
					$next2 = $v2-1;
					$next3 = $h2-1;
					$query2 = "SELECT $move2 FROM `chessboard` WHERE `auth`='$auth'";
					
					$result2 = mysql_query($query2) or die(mysql_error());
					while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
						$hmove2 = "{$row2[$move2]}";
			
						if ($hmove2[0] == '' || $hmove2[0] == 'b') {
							array_push($validmoves, $move2);		
						}
					}
					$v2 = $next2--;
					$h2 = $next3--;
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
		
		public function blackqueenvalidate($from, $to, $shortcode, $auth) {
			$v = $from[1];
			$v2 = $from[1];
			$h = ord($from[0]);
			$h2 = ord($from[0]);
			// begin straight line moves
			$validmoves = array();
			if ($from[0] == $to[0]) {
				while (($vmove == NULL) && ($v < '8') && ($v >= '1')) {
					$move = $from[0].($v+1);
					$next = $v+1;
					$query = "SELECT $move FROM `chessboard` WHERE `auth`='$auth'";
					$result = mysql_query($query) or die(mysql_error());
					while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
						$vmove = "{$row[$move]}";
			
						if ($vmove[0] == '' || $vmove[0] == 'w') {
						array_push($validmoves, $move);		
						}
					}
					$v = $next++;
				}	
				while (($vmove2 == NULL) && ($v2 <= '8') && ($v2 > '1')) {
					$move2 = $from[0].($v2-1);
					$next2 = $v2-1;
					$query2 = "SELECT $move2 FROM `chessboard` WHERE `auth`='$auth'";
					$result2 = mysql_query($query2) or die(mysql_error());
					while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
						$vmove2 = "{$row2[$move2]}";
			
						if ($vmove2[0] == '' || $vmove2[0] == 'w') {
							array_push($validmoves, $move2);		
						}
					}
					$v2 = $next2--;
				}		
			} else if ($from[1] == $to[1]) {
				while (($hmove == NULL) && ($h < '104') && ($h >= '97')) {
					$move = chr(($h+1)).$from[1];
					$next = $h+1;
					$query = "SELECT $move FROM `chessboard` WHERE `auth`='$auth'";
					$result = mysql_query($query) or die(mysql_error());
					while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
						$hmove = "{$row[$move]}";
			    
						if ($hmove[0] == '' || $hmove[0] == 'w') {
							array_push($validmoves, $move);		
						}
					}
					$h = $next++;
				}	
				while (($hmove2 == NULL) && ($h2 <= '104') && ($h2 > '97')) {
					$move2 = chr(($h2-1)).$from[1];
					$next2 = $h2-1;
					$query2 = "SELECT $move2 FROM `chessboard` WHERE `auth`='$auth'";
					$result2 = mysql_query($query2) or die(mysql_error());
					while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
						$hmove2 = "{$row2[$move2]}";
			
						if ($hmove2[0] == '' || $hmove2[0] == 'w') {
							array_push($validmoves, $move2);		
						}
					}
					$h2 = $next2--;
				}
			}
			// end straight line moves
			
			// begin diagonal moves
			if ($from[1] < $to[1]) { 
				while (($vmove == NULL) && ($h < '103') && ($h >= '97') && ($v < '8') && ($v >= '1')) {
					$move = chr(($h+1)).($v+1);
					$next = $v+1;
					$next02 = $h+1;
					$query = "SELECT $move FROM `chessboard` WHERE `auth`='$auth'";
					$result = mysql_query($query) or die(mysql_error());
					while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
						$vmove = "{$row[$move]}";
			
						if ($vmove[0] == '' || $vmove[0] == 'w') {
							array_push($validmoves, $move);		
						}
					} 
					$v = $next++;
					$h = $next02++;
				}	
				while (($vmove2 == NULL) && ($h2 <= '103') && ($h2 > '97') && ($v2 < '8') && ($v2 >= '1')) {
					$move2 = chr(($h2-1)).($v2+1);
					$next2 = $h2-1;
					$next3 = $v2+1;
					$query2 = "SELECT $move2 FROM `chessboard` WHERE `auth`='$auth'";
					$result2 = mysql_query($query2) or die(mysql_error());
					while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
						$vmove2 = "{$row2[$move2]}";
			
						if ($vmove2[0] == '' || $vmove2[0] == 'w') {
							array_push($validmoves, $move2);		
						}
					}
					$h2 = $next2--;
					$v2 = $next3++;
				}
			} else if ($from[1] > $to[1]) { 
				while (($hmove == NULL) && ($h < '103') && ($h >= '97') && ($v <= '8') && ($v > '1')) {
					$move = chr(($h+1)).($v-1);
					$next = $h+1;
					$next02 = $v-1;
					$query = "SELECT $move FROM `chessboard` WHERE `auth`='$auth'";
					$result = mysql_query($query) or die(mysql_error());
					while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
						$hmove = "{$row[$move]}";
			    
						if ($hmove[0] == '' || $hmove[0] == 'w') {
							array_push($validmoves, $move);		
						}
					}
					$h = $next++;
					$v = $next02--;
				}
				while (($hmove2 == NULL) && ($v2 <= '8') && ($v2 > '1') && ($h2 <= '103') && ($h2 > '97')) {
					$move2 = chr(($h2-1)).($v2-1);
					$next2 = $v2-1;
					$next3 = $h2-1;
					$query2 = "SELECT $move2 FROM `chessboard` WHERE `auth`='$auth'";
					
					$result2 = mysql_query($query2) or die(mysql_error());
					while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
						$hmove2 = "{$row2[$move2]}";
			
						if ($hmove2[0] == '' || $hmove2[0] == 'w') {
							array_push($validmoves, $move2);		
						}
					}
					$v2 = $next2--;
					$h2 = $next3--;
				}		
			}
			//end diagonal moves
			
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