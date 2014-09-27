<?php
	class piece {
		public $shortcode; 
		public $color;
		
		
		public function findtype($piece){
			if ($piece == "wr"){
				return rook;
			} else if ($piece == "wn") {
				return knight;
			} else if ($piece == "wb") {
				return bishop;
			} else if ($piece == "wq") {
				return queen;
			} else if ($piece == "wk") {
				return king;
			} else if ($piece == "wp") {
				return pawn;
			} else if ($piece == "br") {
				return rook;
			} else if ($piece == "bn") {
				return knight;
			} else if ($piece == "bb") {
				return bishop;
			} else if ($piece == "bq") {
				return queen;
			} else if ($piece == "bk") {
				return king;
			} else if ($piece == "bp") {
				return pawn;
			}
		}
		
		public function findcolor($piece) {
			if ($piece == "wr"){
				return 'white';
			} else if ($piece == "wn") {
				return 'white';
			} else if ($piece == "wb") {
				return 'white';
			} else if ($piece == "wq") {
				return 'white';
			} else if ($piece == "wk") {
				return 'white';
			} else if ($piece == "wp") {
				return 'white';
			} else if ($piece == "br") {
				return 'black';
			} else if ($piece == "bn") {
				return 'black';
			} else if ($piece == "bb") {
				return 'black';
			} else if ($piece == "bq") {
				return 'black';
			} else if ($piece == "bk") {
				return 'black';
			} else if ($piece == "bp") {
				return 'black';
			}
		}
		
		public function movingtype($from, $auth) {
			$db = new Database();
			$db->connect();
			$query = "SELECT ".$from." FROM `chessboard` WHERE `auth`='".$auth."'";
			$result = mysql_query($query);
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			return $this->findtype($row[$from]);
		}
		
		public function movingcolor($from, $auth) {
			$db = new Database();
			$db->connect();
			$query = "SELECT ".$from." FROM `chessboard` WHERE `auth`='".$auth."'";
			$result = mysql_query($query);
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			return $this->findcolor($row[$from]);
		}
		
		public function checkturn($auth) {
			$db = new Database();
			$db->connect();
			$query = "SELECT turn FROM `chessboard` WHERE `auth`='".$auth."'";
			$result = mysql_query($query);
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			return $row['turn'];
		}
		
		
		public function incheck($from, $to, $shortcode, $auth) {
			// set kings color
			if ($this->findcolor($shortcode) == "black") {
				$king = "bk";
				$other = 'w';
			} else if ($this->findcolor($shortcode) == "white") {
				$king = "wk";
				$other = 'b';
			}
			// end kings color
			// get the kings location pull database then loop through to find the king
			$db = new Database();
			$db->connect();
			$query = "SELECT * FROM `chessboard` WHERE `auth`='".$auth."'";
			$result = mysql_query($query);
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			foreach($row as $key =>$rows) {
				if ($rows==$king) {
					$toking = $key;
				}
				if($key==$to) {
					$previous=$rows;
				}
			}
			// end getting kings location
			// Update the database with the move.
			$query = "UPDATE chessboard SET ".$from."='', ".$to."='".$shortcode."'  WHERE `auth`='".$auth."'";	
			$result = mysql_query($query);
			// end updating the database with the move
			// grab the board again
			$query = "SELECT * FROM `chessboard` WHERE `auth`='".$auth."'";
			$result = mysql_query($query);
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			foreach($row as $key=>$rows) {
				if ($rows[0]==$other) {
					//run through the options to validate 
					if ($other=='b') {
						switch ($rows) {
							case 'bp':
								$bp = new pawn();
								if($bp->blackpawnvalidate($key, $toking, $rows, $auth)) {
									$check = true;
								}									
								break;
							case 'br':
								$br = new rook();
								if($br->blackrookvalidate($key, $toking, $rows, $auth)) {
									$check = true;
								}
								break;
							case 'bn':
								$bn = new knight();
								if($bn->blackknightvalidate($key, $toking, $rows, $auth)) {
									$check = true;
								}	
								break;
							case 'bb':
								$bb = new bishop();
								if($bb->blackbishopvalidate($key, $toking, $rows, $auth)) {
									$check = true;
								}	
								break;
							case 'bq':
								$bq = new queen();
								if($bq->blackqueenvalidate($key, $toking, $rows, $auth)) {
									$check = true;
								}	
								break;
							case 'bk':
								$bk = new king();
								if($bk->blackkingvalidate($key, $toking, $rows, $auth)) {
									$check = true;
								}	
								break;
						}
					} else if ($other=='w') {
						switch ($rows) {
							case 'wp':
								$wp = new pawn();
								if($wp->whitepawnvalidate($key, $toking, $rows, $auth)) {
									$check = true;
								}
								break;
							case 'wr':
								$wr = new rook();
								if($wr->whiterookvalidate($key, $toking, $rows, $auth)) {
									$check = true;
								}
								break;
							case 'wn':
								$wn = new knight();
								if($wn->whiteknightvalidate($key, $toking, $rows, $auth)) {
									$check = true;
								}
								break;
							case 'wb':
								$wb = new bishop();
								if($wb->whitebishopvalidate($key, $toking, $rows, $auth)) {
									$check = true;
								}
								break;
							case 'wq':
								$wq = new queen();
								if($wq->whitequeenvalidate($key, $toking, $rows, $auth)) {
									$check = true;
								}
								break;
							case 'wk':
								$wk = new king();
								if($wk->whitekingvalidate($key, $toking, $rows, $auth)) {
									$check = true;
								}
								break;
						}
					}
					//end validation options
				}
			}
			// end the validation loop
			// undo the db update
			
			$query = "UPDATE chessboard SET ".$to."='".$previous."', ".$from."='".$shortcode."'  WHERE `auth`='".$auth."'";	
			$result = mysql_query($query) or die(mysql_error());
			// end db undo
			// return true if in check or false if not
			if ($check==true) {
				return true;
			} else if ($check==false) {
				return false;
			}
			// end return true if in check or false if not
		}
		
	}
?>