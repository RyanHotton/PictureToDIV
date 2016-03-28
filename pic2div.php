<?php
	//$image = new Imagick('pictures/batman_icon.jpg');
	//$image = new Imagick('pictures/marvel_icon.png');
	$image = new Imagick($_POST['picURL']);
	

	$d = $image->getImageGeometry(); 
	$w = $d['width']; 
	$h = $d['height'];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Picture to Div</title>
		<style>
			div.pic {
				border-style: solid;
			}
			div.pixel {
				width: 1px;
				height: 1px;
				float:left;
			}
			input#picURL {
				width: 100%;
			}
		</style>
	</head>
	<body>
		<h1>Picture to Div</h1>
		<form action="pic2div.php" method="POST">
			<label for="picURL">Picture URL: </label>
			<?php
				echo "<input type=\"text\" id=\"picURL\" name=\"picURL\" value=\"{$_POST['picURL']}\"/>";
			?>
			<br />
			<input type="submit"/>
		</form>
		<h4>Picture Details:</h4>
		<ul>
			<li>Width: <?=$w?>px</li>
			<li>Height: <?=$h?>px</li>
		</ul>
		<hr />
		
			<?php
				echo "<div class=\"pic\" style=\"width: {$w}px; height: {$h}px;\">";
				for ($y = 1; $y <= $h; $y++)
				{
					for ($x = 1; $x <= $w; $x++)
					{
						$pixel = $image->getImagePixelColor($x, $y);
						$color = $pixel->getColor(); 
						echo "<div class=\"pixel\" style=\"background-color: rgb({$color[r]},{$color[g]},{$color[b]});\"></div>";
					}
				}
				echo "</div>";
			?>
	</body>
</html>