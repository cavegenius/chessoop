<?php
	class board {
		public $height;
		public $width;
		public $rows;
		public $columns;
		public $auth; 
		
		function __construct($auth) {
			$this->auth = $auth;
		}
		
		function drawboard() {			
			echo "<table name=\"board\" background=\"images/animknot.gif\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
			
			for($r=8;$r>=1;$r--) { //rows
				echo "<tr>";
				echo "<td width='25' height='25' align='right'><font face='arial'> ".$r."&nbsp; </font></td><br />";
				for($c=97;$c<=104;$c++) { //columns
					$cell_number = chr($c).$r;
					if(($r%2)==0) {
						if(($c%2)==0) {
							$color="#996633";	
						} else {
							$color="tan";
						}
					} else {
						if(($c%2)==0) {
							$color="tan";	
						} else {
							$color="#996633";
						}
					}
					$$cell_number = new square($cell_number, $color, $this->auth);
					echo $$cell_number->draw_square();
				}
				echo "</tr>";
			}
			echo "<tr>";
			echo "<td width='25' height='25'>&nbsp;  </td>";
			echo "<td width='25' height='25' align='center'><font face='arial'> a </font></td>";
			echo "<td width='25' height='25' align='center'><font face='arial'> b </font></td>";
			echo "<td width='25' height='25' align='center'><font face='arial'> c </font></td>";
			echo "<td width='25' height='25' align='center'><font face='arial'> d </font></td>";
			echo "<td width='25' height='25' align='center'><font face='arial'> e </font></td>";
			echo "<td width='25' height='25' align='center'><font face='arial'> f </font></td>";
			echo "<td width='25' height='25' align='center'><font face='arial'> g </font></td>";
			echo "<td width='25' height='25' align='center'><font face='arial'> h </font></td>";
			echo "</tr>";
			echo "</table>";
		}
	}
?>