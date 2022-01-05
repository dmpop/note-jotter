<?php
$title = "Scratch that";
$password = 'password';
$theme = "light";
$dir = 'versions';
$mdfile = "content.md";
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
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
			file_put_contents($mdfile, '');
		}

		function Alert($message)
		{
			echo '<script>';
			echo 'alert("' . $message . '")';
			echo '</script>';
		}

		function Read()
		{
			global $mdfile;
			echo file_get_contents($mdfile);
		}
		function Write()
		{
			global $dir;
			global $mdfile;
			copy($mdfile, $dir . DIRECTORY_SEPARATOR . date('Y-m-d-H-i-s') . '.md');
			$fp = fopen($mdfile, "w");
			$data = $_POST["text"];
			fwrite($fp, $data);
			fclose($fp);
		}
		if (isset($_POST["save"])) {
			if ($_POST['password'] != $password) {
				Alert("Wrong password!");
			} else {
				Write();
				Alert("Changes have been saved.");
			}
		}

		if (isset($_POST["clean"])) {
			if ($_POST['password'] != $password) {
				Alert("Wrong password!");
			} else {
				foreach (glob($dir . DIRECTORY_SEPARATOR . "*") as $filename) {
					unlink($filename);
					Alert("All versions have been removed.");
				}
			}
		}

		if (isset($_POST["show"])) {
			echo "<h3> Version: " . pathinfo($_POST['version'])['filename'] . "</h3>";
			echo "<hr>";
			echo file_get_contents($_POST["version"]);
		}
		?>

		<form action="" method="POST">
			<textarea name="text"><?php Read(); ?></textarea>
			<div>
				<label for='password'>Password:</label>
			</div>
			<div>
				<input type="password" name="password">
			</div>
			<button style="margin-bottom: 1.5em;" type="submit" name="save">Save</button>
			<button style="margin-top: 1.5em;" type="submit" name="clean">Clean</button>
		</form>
		<hr>
		<form style="margin-bottom: 1.5em;" action="" method="POST">
			<select name="version">
				<option value="--" selected>Versions</option>
				<?php
				$files = glob($dir . DIRECTORY_SEPARATOR . "*");
				foreach ($files as $file) {
					echo "<option value='" . $file . "'>" . pathinfo($file)['filename'] . "</option>";
				}
				?>
			</select>
			<button type='submit' role='button' name='show'>Show version</button>
		</form>
		<div style="margin-bottom: 1em;">
			<?php echo $footer; ?>
		</div>
	</div>
</body>

</html>