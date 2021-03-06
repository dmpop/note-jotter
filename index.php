<?php
// CORS policy to allow the submitted page to read the response
header('Access-Control-Allow-Origin: *');
include('config.php');
include('inc/parsedown.php');
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo $theme; ?>">

<!--
	Author: Dmitri Popov, dmpop@linux.com
	License: GPLv3 https://www.gnu.org/licenses/gpl-3.0.txt
-->

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
		<button title="Show all notes" style="margin-top: 1em;" onclick='window.location.href = "list.php"'><img style='vertical-align: middle;' src='svg/list.svg' /></button>

		<?php
		if (!file_exists($dir)) {
			mkdir($dir, 0750, true);
			file_put_contents($txt_file, '');
		}

		function Alert($message)
		{
			echo '<script>';
			echo 'alert("' . $message . '")';
			echo '</script>';
		}

		function Read($file)
		{
			echo file_get_contents($file);
		}

		function Write($data)
		{
			global $dir;
			global $txt_file;
			file_put_contents($txt_file, $data);
			if (!empty($_POST["title"])) {
				copy($txt_file, $dir . DIRECTORY_SEPARATOR . $_POST["title"] . '.md');
			} else {
				copy($txt_file, $dir . DIRECTORY_SEPARATOR . date('Ymd-His') . '.md');
			}
		}

		switch (true) {
			case isset($_POST["save"]):
				if ($_POST['password'] !== $password) {
					Alert("Incorrect password!");
				} else {
					Write($_POST["text"]);
					Alert("Changes have been saved.");
				}
				break;

			case isset($_POST["delete"]):
				if ($_POST['password'] !== $password) {
					Alert("Incorrect password!");
				} else {
					foreach (glob($dir . DIRECTORY_SEPARATOR . "*") as $filename) {
						unlink($filename);
						Alert("All versions have been removed.");
					}
				}
				break;

			case isset($_POST["show"]):
				echo "<h3>" . pathinfo($_POST['version'])['filename'] . "</h3>";
				echo "<hr>";
				echo "<div class='text-left'>";
				$Parsedown = new Parsedown();
				echo $Parsedown->text(file_get_contents($_POST['version']));
				echo "</div>";
				echo "<form method='GET' action='" . $_POST['version'] . "'>";
				echo "<button title='Download this version' type='submit'><img src='svg/download.svg' width=24 /></button>";
				echo "</form>";
				echo "<hr style='margin-bottom: 2em;'>";
				break;
		}

		if (isset($_GET['note']) && $_GET['password'] == $password) {
			Write($_GET['note']);
			header("location:" . $_GET['url'] . "");
		}
		?>

		<form action="" method="POST">
			<div style="margin-top: 1em;">
				<label>Title (optional):
					<input type="text" name="title">
				</label>
			</div>
			<textarea name="text"><?php Read($txt_file); ?></textarea>
			<label>Password:
				<input type="password" name="password">
			</label>
			<button title="Save" style="margin-bottom: 1.5em;" type="submit" name="save"><img src='svg/save.svg' width=24 /></button>
			<button title="Delete all versions" style="margin-top: 1.5em;" type="submit" name="delete"><img src='svg/trash.svg' width=24 /></button>
		</form>
		<hr style="margin-bottom: 1.5em;">
		<form style="margin-bottom: 1.5em;" action="" method="POST">
			<select style="vertical-align: middle;" name="version">
				<option value="<?php echo $txt_file; ?>" selected>Versions</option>
				<?php
				$files = glob($dir . DIRECTORY_SEPARATOR . "*");
				foreach ($files as $file) {
					echo "<option value='" . $file . "'>" . pathinfo($file)['filename'] . "</option>";
				}
				?>
			</select>
			<button style="vertical-align: middle;" title="Show the selected note" type="submit" name="show"><img src="svg/view.svg" width=24 /></button>
		</form>
		<div style="margin-bottom: 1em;">
			<?php echo $footer; ?>
		</div>
	</div>
</body>

</html>