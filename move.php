<?php 
session_start();
include("includes/loadclasses.php");
$auth = "{$_GET['auth']}";
$db = new database();
if (isset($_POST['Submit'])) { //return any errors first

	if (!empty($_POST['from'])) {
		$from = $_POST['from'];
	} else {
			$from = FALSE;
			echo 'Please enter a valid location to move from.<br />';
	}
	
	if (!empty($_POST['to'])) {
		$to = $_POST['to'];
	} else {
			$to = FALSE;
			echo 'Please enter a valid location to move to.<br />';
	}
	$promoteto = $_POST['promoteto'];
	
	$piece = new piece();
	$type = $piece->movingtype($from, $auth);
	$color = $piece->movingcolor($from, $auth);
	if ((($type=='pawn' && $color=='white' && $from[1]=='7' && $to[1]=='8') || ($type=='pawn' && $color=='black' && $from[1]=='2' && $to[1]=='1')) && empty($promoteto)) {
		?><form method="post" action="move.php?auth=<?php echo $auth;?>">
			<input type="hidden" name="from" value="<?php echo $from; ?>"> 
			<input type="hidden" name="to" value="<?php echo $to; ?>">
			<select name="promoteto">
				<option value="q">Queen</option>
				<option value="n">Knight</option>
				<option value="r">Rook</option>
				<option value="b">Bishop</option>
			</select> 
			<input type="Submit" value="Submit" name="Submit">
		</form><?php
	} else if(isset($promoteto)) {
		$promote = $promoteto;
		if ($color=='white') {
			$colorcode = 'w';
		} else if($color=='black') {
			$colorcode = 'b';
		}
		$promoteto2 = $colorcode.$promote;
		$piece = new $type($color);
		$piece->set_shortcode($color);		
		$piece->move($from, $to, $promoteto2, $auth);
		header("Location: index.php?auth=$auth&from=$from&to=$to");
	} else {
		$piece = new $type($color);
		$piece->set_shortcode($color);
		$shortcode = $piece->get_shortcode();
		$piece->move($from, $to, $shortcode, $auth);
		header("Location: index.php?auth=$auth&from=$from&to=$to");
	}
} else {
	$_SESSION['error'] = 'Nothing was submitted';
	header("Location: index.php?auth=$auth&from=$from&to=$to");
}
?>