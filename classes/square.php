<?php
	class square {
		public $cell_number;
		public $color; 
		public $width="25";
		public $height="25";
		public $auth;
		
		function __construct($cell_number, $color, $auth) {
			$this->cell_number = $cell_number;
			$this->color = $color;
			$this->auth = $auth;
		}
		
		function set_cell_number($new_cell_number) {
			$this->cell_number = $new_cell_number;
		}
		
		function get_cell_number() {
			return $this->cell_number;
		}
		
		function set_color($new_color) {
			$this->color = $new_color;
		}
		
		function get_color() {
			return $this->color;
		}
		
		function set_width($new_width) {
			$this->width = $new_width;
		}
		
		function get_width() {
			return $this->width;
		}
		
		function set_height($new_height) {
			$this->height = $new_height;
		}
		
		function get_height() {
			return $this->height;
		}
		
		public function draw_square() {
			$db = new Database();
			$db->connect();
			$query = "SELECT ".$this->cell_number." FROM `chessboard` WHERE `auth`='".$this->auth."'";
			$result = mysql_query ($query);
			$row = mysql_fetch_array ($result, MYSQL_ASSOC);	
			if ($row[$this->cell_number] != NULL) { 
				$piece = new piece();
				$type = $piece->findtype($row[$this->cell_number]);
				$color = $piece->findcolor($row[$this->cell_number]);
				$piece = new $type($color);				
				return "<td id=\"".$this->cell_number."\" onclick=\"moveSelection('".$this->cell_number."')\" bgcolor='".$this->color."'>".$piece->displaypiece($color)."</td>";
			} else {
				return "<td id=\"".$this->cell_number."\" onclick=\"moveSelection('".$this->cell_number."')\" bgcolor='".$this->color."'><img src=\"images/blank.gif\" /></td>";
			}
		}
	
	}
?>