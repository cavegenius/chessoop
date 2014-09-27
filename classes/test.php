<?php

	include("includes/loadclasses.php");
	$piece = new rook();
	$piecetype = 'wk';
	$from = 'a1';
	$to = 'a3';
	$shortcode = 'wr';
	$auth = 'UWuo3hHH1KFUhmDVrpJ8NZJrjWOIcLfW';

	echo $piece->whiterookvalidate($from, $to, $shortcode, $auth);
	


?>