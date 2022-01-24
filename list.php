<?php
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
    <!-- Suppress form re-submit prompt on refresh -->
</head>

<body>
    <div class="card text-center">
        <div style="margin-top: 1em; margin-bottom: 1em;">
            <img style="display: inline; height: 2.5em; vertical-align: middle;" src="favicon.svg" alt="logo" />
            <h1 style="display: inline; margin-top: 0em; vertical-align: middle; letter-spacing: 3px;"><?php echo $title; ?></h1>
        </div>
        <hr>
        <button title="Back" style="margin-top: 1.5em; margin-bottom: 1.5em;" onclick='window.location.href = "index.php"'><img style='vertical-align: middle;' src='svg/back.svg' /></button>
        <?php
        $files = glob($dir . DIRECTORY_SEPARATOR . "*");
        foreach ($files as $file) {
            echo "<h3>" . pathinfo($file)['filename'] . "</h3>";
            echo "<hr>";
            echo "<div class='text-left'>";
            $Parsedown = new Parsedown();
            echo $Parsedown->text(file_get_contents($file));
            echo "</div>";
            echo "<form method='GET' action='" . $file . "'>";
            echo "<button title='Download this version' type='submit'><img src='svg/download.svg' width=24 /></button>";
            echo "</form>";
            echo "<hr style='margin-bottom: 2em;'>";
        }
        ?>
        </form>
        <div style="margin-bottom: 1em;">
            <?php echo $footer; ?>
        </div>
    </div>
</body>

</html>