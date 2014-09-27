<?php

	class pawn extends piece {
		public $shortcode;
		public $color;

		public function displaypiece($color) {
			if($color=='white') {
				return "<img src=\"images/wp.gif\" />";
			} else if($color=='black') { 
				return "<img src=\"images/bp.gif\" />";
			}
		}
		
		public function set_shortcode($color){
			if ($color=="black") {
				$this->shortcode = "bp";
			} else if ($color=="white") {
				$this->shortcode = "wp";
			}
		} 
		
		public function get_shortcode() {
			return $this->shortcode;
		} 
				
		public function move($from, $to, $shortcode, $auth) {
			if ($this->findcolor($shortcode)=="white") {
				if ($this->checkturn($auth)=="white") {
					if($this->whitepawnvalidate($from, $to, $shortcode, $auth)==True) {
						if ($this->incheck($from, $to, $shortcode, $auth)==false) {
							$query = "UPDATE chessboard SET turn='black', ".$from."='', ".$to."='".$shortcode."', last_from='".$from."', last_to='".$to."'  WHERE `auth`='".$auth."'";	
							$result = mysql_query($query);	
							if (!$result) {
								$message  = 'Invalid query: ' . mysql_error() . "\n";
								$message .= 'Whole query: ' . $query;
							}
							
							$query2 = "INSERT INTO 'moves' (`id` , 'from', 'to', 'auth') VALUES (NULL, $from, $to, $auth)";	
							$result2 = mysql_query($query2);	
							if (!$result2) {
								$message2  = 'Invalid query: ' . mysql_error() . "\n";
								$message2 .= 'Whole query: ' . $query2;
							}
							
						} else {
							$_SESSION['error'] = 'You can not make this move as you would be in check';
						}
					} else {
						$_SESSION['error'] = 'You made an invalid move';
					}
				} else {
					$_SESSION['error'] = 'it is not your turn';
				}
			} else if ($this->findcolor($shortcode)=="black") {
				if ($this->checkturn($auth)=="black") {
					if($this->blackpawnvalidate($from, $to, $shortcode, $auth)==True) {
						if ($this->incheck($from, $to, $shortcode, $auth)==false) {
							$query = "UPDATE chessboard SET turn='white', ".$from."='', ".$to."='".$shortcode."', last_from='".$from."', last_to='".$to."'  WHERE `auth`='".$auth."'";	
							
							$result = mysql_query($query);	
							if (!$result) {
								$message  = 'Invalid query: ' . mysql_error() . "\n";
								$message .= 'Whole query: ' . $query;
							}
							$query2 = "INSERT INTO 'moves' (`id` , 'from', 'to', 'auth') VALUES (NULL, ".$from.", ".$to.", ".$auth.")";	
							$result2 = mysql_query($query2);	
							if (!$result2) {
								$message2  = 'Invalid query: ' . mysql_error() . "\n";
								$message2 .= 'Whole query: ' . $query2;
							}
						} else {
							$_SESSION['error'] = 'You can not make this move as you would be in check';
						}
					} else {
						$_SESSION['error'] = 'You made an invalid move';
					}
				} else {
					$_SESSION['error'] = 'it is not your turn';
				}
			}
		}
		
		public function whitepawnvalidate($from, $to, $shortcode, $auth) {
			if (preg_match("/(a2|b2|c2|d2|e2|f2|g2|h2)/", $from, $matches)) {
			$move1 = $from[0].'3';
			$move2 = $from[0].'4';

			$query3 = "SELECT $move1, $move2 FROM `chessboard` WHERE `auth`='$auth'";
			$result3 = mysql_query($query3) or die(mysql_error());
			while ($row3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
				$vmove1 = "{$row3[$move1]}";
				$vmove2 = "{$row3['move2']}";
				if ($vmove1[0] == '') {
					if ($vmove2[0] == '') {			
						$validmoves = array($from[0].'3',$from[0].'4');
					}
				}
			}
			} else {
				$move1 = $from[0].($from[1]+1);

				$query3 = "SELECT $move1 FROM `chessboard` WHERE `auth`='$auth'";
				$result3 = mysql_query($query3) or die(mysql_error());
				while ($row3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
					$vmove1 = "{$row3[$move1]}";
			
					if ($vmove1[0] == '') {
						$validmoves = array($from[0].($from[1]+1));		
					}
				}
			}

			if ($from[0] != 'h') {
				$takeup = ord($from[0]);
				$takeup = ($takeup+1);
				$takeup2 = chr($takeup).($from[1]+1);
			}
			
			if ($from[0] != 'a') {
				$takeup3 = ord($from[0]);
				$takeup3 = ($takeup3-1);
				$takeup4 = chr($takeup3).($from[1]+1);
			}
			
			if ($takeup2 && $takeup4) {
				$query2 = "SELECT $takeup2, $takeup4 FROM chessboard WHERE auth='$auth'";      
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$takeup5 = "{$row2[$takeup2]}";
					$takeup6 = "{$row2[$takeup4]}";
					
					if ($takeup5[0] == 'b') {
						if (is_array($validmoves)) {
							array_push($validmoves, $takeup2);
						} else {
							$validmoves = array($takeup2);
						}
					}
					if ($takeup6[0] == 'b') {
						if (is_array($validmoves)) {
							array_push($validmoves, $takeup4);
						} else {
							$validmoves = array($takeup4);
						}
					}
				}
			} else if ($takeup2 && !$takeup4) {
				$query2 = "SELECT $takeup2 FROM chessboard WHERE auth='$auth'";   
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$takeup5 = "{$row2[$takeup2]}";
					
					if ($takeup5[0] == 'b') {
						if (is_array($validmoves)) {
							array_push($validmoves, $takeup2);
						} else {
							$validmoves = array($takeup2);
						}
					}
				}
			} else if (!$takeup2 && $takeup4) {
				$query2 = "SELECT $takeup4 FROM chessboard WHERE auth='$auth'";   
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$takeup6 = "{$row2[$takeup4]}";
					
					
					if ($takeup6[0] == 'b') {
						if (is_array($validmoves)) {
							array_push($validmoves, $takeup4);
						} else {
							$validmoves = array($takeup4);
						}
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
		
		public function blackpawnvalidate($from, $to, $shortcode, $auth) {
			if (preg_match("/(a7|b7|c7|d7|e7|f7|g7|h7)/", $from, $matches)) {
				$move1 = $from[0].'6';
				$move2 = $from[0].'5';
				$validmoves = array();
				
				$query3 = "SELECT $move1, $move2 FROM `chessboard` WHERE `auth`='$auth'";
				$result3 = mysql_query($query3) or die(mysql_error());
				while ($row3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
					$vmove1 = "{$row3[$move1]}";
					$vmove2 = "{$row3['move2']}";
					if ($vmove1[0] == '') {
						if ($vmove2[0] == '') {			
							$validmoves = array($from[0].'6',$from[0].'5');
						}
					}
				}
			} else {
				$move1 = $from[0].($from[1]-1);
				
				$query3 = "SELECT $move1 FROM `chessboard` WHERE `auth`='$auth'";
				$result3 = mysql_query($query3) or die(mysql_error());
				while ($row3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
					$vmove1 = "{$row3[$move1]}";
					
					if ($vmove1[0] == '') {
						$validmoves = array($from[0].($from[1]-1));		
					}
				}
			}

			if ($from[0] != 'a') {
				$takeup = ord($from[0]);
				$takeup = ($takeup-1);
				$takeup2 = chr($takeup).($from[1]-1);
			}

			if ($from[0] != 'h') {
				$takeup3 = ord($from[0]);
				$takeup3 = ($takeup3+1);
				$takeup4 = chr($takeup3).($from[1]-1);
			}

			if ($takeup2 && $takeup4) {
				$query2 = "SELECT $takeup2, $takeup4 FROM chessboard WHERE auth='$auth'";   
				
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$takeup5 = "{$row2[$takeup2]}";
					$takeup6 = "{$row2[$takeup4]}";
					
					if ($takeup5[0] == 'w') {
						if (is_array($validmoves)) {
							array_push($validmoves, $takeup2);
						} else {
							$validmoves = array($takeup2);
						}
					}
					if ($takeup6[0] == 'w') {
						if (is_array($validmoves)) {
							array_push($validmoves, $takeup4);
						} else {
							$validmoves = array($takeup4);
						}
					}
				}
			} else if ($takeup2 && !$takeup4) {
				$query2 = "SELECT $takeup2 FROM chessboard WHERE auth='$auth'";   
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$takeup5 = "{$row2[$takeup2]}";
					
					if ($takeup5[0] == 'w') {
						if (is_array($validmoves)) {
							array_push($validmoves, $takeup2);
						} else {
							$validmoves = array($takeup2);
						}
					}
				}
			} else if (!$takeup2 && $takeup4) {
				$query2 = "SELECT $takeup4 FROM chessboard WHERE auth='$auth'";   
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$takeup6 = "{$row2[$takeup4]}";
					
					
					if ($takeup6[0] == 'w') {
						if (is_array($validmoves)) {
							array_push($validmoves, $takeup4);
						} else {
							$validmoves = array($takeup4);
						}
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
