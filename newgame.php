<?php
 
	include("includes/loadclasses.php");

	$chessgame = new chess_game();

	$chessgame->new_game();

	header('Location: index.php?auth='.$chessgame->get_auth().'');

?>