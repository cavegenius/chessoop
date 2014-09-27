<?php

	class knight extends piece {
		public $shortcode;
		public $color;

		public function displaypiece($color) {
			if($color=='white') {
				return "<img src=\"images/wn.gif\" />";
			} else if($color=='black') { 
				return "<img src=\"images/bn.gif\" />";
			}
		}
		
		public function set_shortcode($color){
			if ($color=="black") {
				$this->shortcode = "bn";
			} else if ($color=="white") {
				$this->shortcode = "wn";
			}
		}
		
		public function get_shortcode() {
			return $this->shortcode;
		}
		
		public function move($from, $to, $shortcode, $auth) {
			if ($this->findcolor($shortcode)=="white") {
				if ($this->checkturn($auth)=="white") {
					if($this->whiteknightvalidate($from, $to, $shortcode, $auth)==True) {
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
					if($this->blackknightvalidate($from, $to, $shortcode, $auth)==True) {
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
		
		public function blackknightvalidate($from, $to, $shortcode, $auth) {
			$alpha = ord($from[0]);
			$validmoves = array();

			$upright0 = $alpha+1;
			$upright1 = chr(($alpha+1));
			$upright2 = $from[1]+2;
			if (($upright0 <= '104') && ($upright0 >= '97') && ($upright2 >= '1') && ($upright2 <= '8')) {
				$upright = $upright1.$upright2;
				$query2 = "SELECT $upright FROM `chessboard` WHERE `auth`='$auth'";
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$move = "{$row2[$upright]}";
			
					if ($move[0] == '' || $move[0] == 'w') {
						array_push($validmoves, $upright);
					}
				}
			}

			$upleft0 = $alpha-1;
			$upleft1 = chr(($alpha-1));
			$upleft2 = $from[1]+2;
			if (($upleft0 <= '104') && ($upleft0 >= '97') && ($upleft2 >= '1') && ($upleft2 <= '8')) {
				$upleft = $upleft1.$upleft2;
				$query2 = "SELECT $upleft FROM `chessboard` WHERE `auth`='$auth'";
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$move = "{$row2[$upleft]}";
			
					if ($move[0] == '' || $move[0] == 'w') {
						array_push($validmoves, $upleft);
					}
				}
			}

			$downright0 = $alpha+1;
			$downright1 = chr(($alpha+1));
			$downright2 = $from[1]-2;
			if (($downright0 <= '104') && ($downright0 >= '97') && ($downright2 >= '1') && ($downright2 <= '8')) {
				$downright = $downright1.$downright2;
				$query2 = "SELECT $downright FROM `chessboard` WHERE `auth`='$auth'";
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$move = "{$row2[$downright]}";
			
					if ($move[0] == '' || $move[0] == 'w') {
						array_push($validmoves, $downright);
					}
				}
			}

			$downleft0 = $alpha-1;
			$downleft1 = chr(($alpha-1));
			$downleft2 = $from[1]-2;
			if (($downleft0 <= '104') && ($downleft0 >= '97') && ($downleft2 >= '1') && ($downleft2 <= '8')) {
				$downleft = $downleft1.$downleft2;
				$query2 = "SELECT $downleft FROM `chessboard` WHERE `auth`='$auth'";
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$move = "{$row2[$downleft]}";
			
					if ($move[0] == '' || $move[0] == 'w') {
						array_push($validmoves, $downleft);
					}
				}
			}

			$leftup0 = $alpha-2;
			$leftup1 = chr(($alpha-2));
			$leftup2 = $from[1]+1;
			if (($leftup0 <= '104') && ($leftup0 >= '97') && ($leftup2 >= '1') && ($leftup2 <= '8')) {
				$leftup = $leftup1.$leftup2;
				$query2 = "SELECT $leftup FROM `chessboard` WHERE `auth`='$auth'";
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$move = "{$row2[$leftup]}";
			
					if ($move[0] == '' || $move[0] == 'w') {
						array_push($validmoves, $leftup);
					}
				}
			}

			$leftdown0 = $alpha-2;
			$leftdown1 = chr(($alpha-2));
			$leftdown2 = $from[1]-1;
			if (($leftdown0 <= '104') && ($leftdown0 >= '97') && ($leftdown2 >= '1') && ($leftdown2 <= '8')) {
				$leftdown = $leftdown1.$leftdown2;
				$query2 = "SELECT $leftdown FROM `chessboard` WHERE `auth`='$auth'";
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$move = "{$row2[$leftdown]}";
			
					if ($move[0] == '' || $move[0] == 'w') {
						array_push($validmoves, $leftdown);
					}
				}
			}

			$rightup0 = $alpha+2;
			$rightup1 = chr(($alpha+2));
			$rightup2 = $from[1]+1;
			if (($rightup0 <= '104') && ($rightup0 >= '97') && ($rightup2 >= '1') && ($rightup2 <= '8')) {
				$rightup = $rightup1.$rightup2;
				$query2 = "SELECT $rightup FROM `chessboard` WHERE `auth`='$auth'";
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$move = "{$row2[$rightup]}";
			
					if ($move[0] == '' || $move[0] == 'w') {
						array_push($validmoves, $rightup);
					}
				}
			}

			$rightdown0 = $alpha+2;
			$rightdown1 = chr(($alpha+2));
			$rightdown2 = $from[1]-1;
			if (($rightdown0 <= '104') && ($rightdown0 >= '97') && ($rightdown2 >= '1') && ($rightdown2 <= '8')) {
				$rightdown = $rightdown1.$rightdown2;
				$query2 = "SELECT $rightdown FROM `chessboard` WHERE `auth`='$auth'";
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$move = "{$row2[$rightdown]}";
			
					if ($move[0] == '' || $move[0] == 'w') {
						array_push($validmoves, $rightdown);
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
		
		public function whiteknightvalidate($from, $to, $shortcode, $auth) {
			$alpha = ord($from[0]);
			$validmoves = array();

			$upright0 = $alpha+1;
			$upright1 = chr(($alpha+1));
			$upright2 = $from[1]+2;
			if (($upright0 <= '104') && ($upright0 >= '97') && ($upright2 >= '1') && ($upright2 <= '8')) {
				$upright = $upright1.$upright2;
				$query2 = "SELECT $upright FROM `chessboard` WHERE `auth`='$auth'";
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$move = "{$row2[$upright]}";
			
					if ($move[0] == '' || $move[0] == 'b') {
						array_push($validmoves, $upright);
					}
				}
			}

			$upleft0 = $alpha-1;
			$upleft1 = chr(($alpha-1));
			$upleft2 = $from[1]+2;
			if (($upleft0 <= '104') && ($upleft0 >= '97') && ($upleft2 >= '1') && ($upleft2 <= '8')) {
				$upleft = $upleft1.$upleft2;
				$query2 = "SELECT $upleft FROM `chessboard` WHERE `auth`='$auth'";
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$move = "{$row2[$upleft]}";
			
					if ($move[0] == '' || $move[0] == 'b') {
						array_push($validmoves, $upleft);
					}
				}
			}

			$downright0 = $alpha+1;
			$downright1 = chr(($alpha+1));
			$downright2 = $from[1]-2;
			if (($downright0 <= '104') && ($downright0 >= '97') && ($downright2 >= '1') && ($downright2 <= '8')) {
				$downright = $downright1.$downright2;
				$query2 = "SELECT $downright FROM `chessboard` WHERE `auth`='$auth'";
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$move = "{$row2[$downright]}";
			
					if ($move[0] == '' || $move[0] == 'b') {
						array_push($validmoves, $downright);
					}
				}
			}

			$downleft0 = $alpha-1;
			$downleft1 = chr(($alpha-1));
			$downleft2 = $from[1]-2;
			if (($downleft0 <= '104') && ($downleft0 >= '97') && ($downleft2 >= '1') && ($downleft2 <= '8')) {
				$downleft = $downleft1.$downleft2;
				$query2 = "SELECT $downleft FROM `chessboard` WHERE `auth`='$auth'";
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$move = "{$row2[$downleft]}";
			
					if ($move[0] == '' || $move[0] == 'b') {
						array_push($validmoves, $downleft);
					}
				}
			}

			$leftup0 = $alpha-2;
			$leftup1 = chr(($alpha-2));
			$leftup2 = $from[1]+1;
			if (($leftup0 <= '104') && ($leftup0 >= '97') && ($leftup2 >= '1') && ($leftup2 <= '8')) {
				$leftup = $leftup1.$leftup2;
				$query2 = "SELECT $leftup FROM `chessboard` WHERE `auth`='$auth'";
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$move = "{$row2[$leftup]}";
			
					if ($move[0] == '' || $move[0] == 'b') {
						array_push($validmoves, $leftup);
					}
				}
			}

			$leftdown0 = $alpha-2;
			$leftdown1 = chr(($alpha-2));
			$leftdown2 = $from[1]-1;
			if (($leftdown0 <= '104') && ($leftdown0 >= '97') && ($leftdown2 >= '1') && ($leftdown2 <= '8')) {
				$leftdown = $leftdown1.$leftdown2;
				$query2 = "SELECT $leftdown FROM `chessboard` WHERE `auth`='$auth'";
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$move = "{$row2[$leftdown]}";
			
					if ($move[0] == '' || $move[0] == 'b') {
						array_push($validmoves, $leftdown);
					}
				}
			}

			$rightup0 = $alpha+2;
			$rightup1 = chr(($alpha+2));
			$rightup2 = $from[1]+1;
			if (($rightup0 <= '104') && ($rightup0 >= '97') && ($rightup2 >= '1') && ($rightup2 <= '8')) {
				$rightup = $rightup1.$rightup2;
				$query2 = "SELECT $rightup FROM `chessboard` WHERE `auth`='$auth'";
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$move = "{$row2[$rightup]}";
			
					if ($move[0] == '' || $move[0] == 'b') {
						array_push($validmoves, $rightup);
					}
				}
			}

			$rightdown0 = $alpha+2;
			$rightdown1 = chr(($alpha+2));
			$rightdown2 = $from[1]-1;
			if (($rightdown0 <= '104') && ($rightdown0 >= '97') && ($rightdown2 >= '1') && ($rightdown2 <= '8')) {
				$rightdown = $rightdown1.$rightdown2;
				$query2 = "SELECT $rightdown FROM `chessboard` WHERE `auth`='$auth'";
				$result2 = mysql_query($query2) or die(mysql_error());
				while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$move = "{$row2[$rightdown]}";
			
					if ($move[0] == '' || $move[0] == 'b') {
						array_push($validmoves, $rightdown);
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