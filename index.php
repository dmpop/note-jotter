<?php
//error_reporting(E_ERROR);
$title = "Scratch that";
$password = 'password';
$theme = "light";
$footer = "I really 🧡 <a href='https://www.paypal.com/paypalme/dmpop'>coffee</a>";
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo $theme; ?>">

<!-- Author: Dmitri Popov, dmpop@linux.com
	 License: GPLv3 https://www.gnu.org/licenses/gpl-3.0.txt -->

<head>
	<title><?php echo $title; ?></title>
	<meta charset="utf-8">
	<link rel="shortcut icon" href="favicon.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/classless.css" />
	<link rel="stylesheet" href="css/themes.css" />
	<style>
		textarea {
			font-size: 15px;
			width: 100%;
			height: 15em;
			line-height: 1.9;
			margin-top: 2em;
		}
	</style>
	<!-- Suppress form re-submit prompt on refresh -->
	<script>
		if (window.history.replaceState) {
			window.history.replaceState(null, null, window.location.href);
		}
	</script>
</head>

<body>
	<div class="card text-center">
		<div style="margin-top: 1em; margin-bottom: 1em;">
			<img style="display: inline; height: 2.5em; vertical-align: middle;" src="favicon.svg" alt="logo" />
			<h1 style="display: inline; margin-top: 0em; vertical-align: middle; letter-spacing: 3px;"><?php echo $title; ?></h1>
		</div>
		<hr>
		<?php
		$dir = 'content';
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
			$mdfile = $dir . "/content.md";
			file_put_contents($mdfile, '');
		}
		$mdfile = $dir . "/content.md";
		function Read()
		{
			$dir = 'content';
			$mdfile = $dir . "/content.md";
			echo file_get_contents($mdfile);
		}
		function Write()
		{
			$dir = 'content';
			$mdfile = $dir . "/content.md";
			copy($mdfile, $dir . '/' . date('Y-m-d-H-i-s') . '.md');
			$fp = fopen($mdfile, "w");
			$data = $_POST["text"];
			fwrite($fp, $data);
			fclose($fp);
		}
		?>
		<?php
		if (isset($_POST["save"])) {
			if ($_POST['password'] != $password) {
				print '<p>Wrong password</p>';
				exit();
			}
			Write();
			echo '<div>Changes have been saved</div>';
		};
		?>
		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<textarea name="text"><?php Read(); ?></textarea>
			<div>
				<label for='password'>Password:</label>
			</div>
			<div>
				<input type="password" name="password">
			</div>
			<button style="margin-bottom: 1.5em;" type="submit" name="save">Save</button>
		</form>
	</div>
	<div class="text-center">
		<?php echo $footer; ?>
	</div>
</body>

</html>