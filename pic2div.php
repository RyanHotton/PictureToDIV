<?php
	// redirects if no POST
	if (!isset($_POST['picURL'])) {
		header("Location: index.html");
	}
	// get Database class
	include_once "Class/Credentials.class.php";
	$database = new Credentials;
	$database->connectDB();
	// get image
	$image = new Imagick($_POST['picURL']);
	$d = $image->getImageGeometry(); 
	$w = $d['width']; 
	$h = $d['height'];
	// test db insert
	$query = "INSERT INTO `db_pictures`.`pixels` (`name`, `source`, `height`, `width`, `status`) VALUES (:name, :url, :height, :width, :status);";
	$params = array(':name' => $_POST['picName'], ':url' => $_POST['picURL'], ':height' => $h, ':width' => $w, ':status' => 1);
	$sth = $database->prepareSQL($query);
	var_dump($sth);
	// execute
	$sth->execute($params);
	$id = $database->lastId();
	var_dump($id);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Picture to Div</title>
		<!-- External Stylesheet -->
		<link rel="stylesheet" type="text/css" href="CSS/pic2div.css" />
	</head>
	<body>
		<h1>Picture to Div</h1>
		<form action="pic2div.php" method="POST">
			<label for="picName">Picture Name: </label><br />
			<input type="text" id="picName" name="picName" value="Picture"/>
			<br />
			<label for="picURL">Picture URL: </label>
			<input type="text" id="picURL" name="picURL"/>
			<br />
			<input type="submit"/>
		</form>
		<h4>Picture Details:</h4>
		<ul>
			<li><strong>Name:</strong> <?=$_POST['picName']?></li>
			<li><strong>Picture URL:</strong> <?php echo "<a href=\"{$_POST['picURL']}\">{$_POST['picURL']}</a>" ?></li>
			<li><strong>Width:</strong> <?=$w?>px</li>
			<li><strong>Height:</strong> <?=$h?>px</li>
		</ul>
		<hr />
			<?php
				// echo each individual pixel of the image as a div tag
				echo "<div class=\"pic\" style=\"width: {$w}px; height: {$h}px;\">";
				for ($y = 1; $y <= $h; $y++) {
					$row_string = "";
					for ($x = 1; $x <= $w; $x++) {
						$pixel = $image->getImagePixelColor($x, $y);
						$color = $pixel->getColor(); 
						echo "<div class=\"pixel\" style=\"background-color: rgba({$color['r']},{$color['g']},{$color['b']},{$color['a']});\"></div>";
						$row_string .= "<div class=\"pixel\" style=\"background-color: rgba({$color['r']},{$color['g']},{$color['b']},{$color['a']});\"></div>";
					}
					// inserts row html into database
					$query2 = "INSERT INTO `db_pictures`.`pic_rows` (`position`, `data`, `pic_id`) VALUES (:pos, :row, :picId)";
					$params2 = array(':pos' => $y, ':row' => $row_string, ':picId' => $id);
					$sth2 = $database->prepareSQL($query2);
					$sth2->execute($params2);
				}
				echo "</div>";
			?>
	</body>
</html>
<?php
	$database->closeDB();
?>