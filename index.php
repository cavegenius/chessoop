<?php
		// version 1.0
		session_start();
?>
<html> 
<head>
	<title>Chess Board using PHP</title>
	<link rel="stylesheet" type="text/css" media="all" href="includes/style.css" />
	<?php 		 
		include("includes/loadclasses.php");	
	?>
	<script type="text/javascript" src="includes/scripts.js"></script>
</head>
<body>
	<h2>Chess Board</h2>
	<div align='center'>
		<?php 
			if (isset($_SESSION['error'])) {
				echo $_SESSION['error'];
				unset($_SESSION['error']);
			}
		?>
	<?php
		$auth = "{$_GET['auth']}";
		$from = "{$_GET['from']}";
		$to = "{$_GET['to']}";
		if ($auth) {
			$chessgame = new chess_game();			
			$chessgame->load_game($auth);
			?>
			<div align="center">
			<form name="moveForm" method="post" action="move.php?auth=<?php echo $auth;?>">
				<input type="hidden" name="from" value="">
				<input type="hidden" name="to" value="">
				<input type="hidden" name="Submit" value="Submit">
			</form>
			</div>
			<div align='center'>
				<font face='arial' size='-2'>The last move was: <? echo $from ?> to <?php echo $to; ?></font>
			</div>
			<div align='center'>
				<form action='newgame.php?auth=<?php echo $auth;?>' method='post'>
					<input type='submit' value='Start A New Game >>'>
				</form>
			</div>
			<div align='center'>
				Your auth code for this game is <?php echo $auth; ?>.
			</div>
			<?php
		} else { ?>		
				<div align="center">
					<form method="post" action="auth.php">
					<input type="text" name="auth" />
					<input type="Submit" value="Submit">
					</form>
				</div>
				<div align='center'>
					<form action='newgame.php' method='post'>
					<input type='submit' value='Start A New Game >>'>
					</form>
				</div> <?php
		}
	?>
	</div>
<br /><br />
</body>
</html>