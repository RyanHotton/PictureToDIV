<?php
	/*
		process.php - finishes processing unfinished images
		TO BE COMPLETED AT A LATER DATE
		Updates 50 rows at a time
	*/
	// get Database class
	include_once "/var/www/html/PictureToDIV/Class/Credentials.class.php";
	// database class
	$database = new Credentials;
	// connect to database
	$database->connectDB();
	
	// finds the oldest unfinished image
	$query = "SELECT `id`, `name`, `source`, `height`, `width`, `status` FROM `db_pictures`.`pixels` WHERE `height` != `status` ORDER BY `id` ASC LIMIT 1";
	$sth = $database->prepareSQL($query);
	$sth->execute();
	$result = $sth->fetch(PDO::FETCH_ASSOC);
	//var_dump($result);
	if ($result["height"] > $result["status"]) {
		// get image
		$image = new Imagick($result["source"]);
		$d = $image->getImageGeometry(); 
		$w = $d['width']; 
		$h = $d['height'];
		// id
		$id = $result["id"];
		$add = 75;
		for ($y = $result["status"]+1; $y <= $result["status"]+$add; $y++) {
			if ($y > $h) {
				echo "Breaking Loop";
				break;
			}
			else {
				$row_string = "";
				for ($x = 1; $x <= $w; $x++) {
					$pixel = $image->getImagePixelColor($x, $y);
					$color = $pixel->getColor(); 
					$row_string .= "<div class=\"pixel\" style=\"background-color: rgba({$color['r']},{$color['g']},{$color['b']},{$color['a']});\"></div>";
				}
				// inserts row html into database
				$query2 = "INSERT INTO `db_pictures`.`pic_rows` (`position`, `data`, `pic_id`) VALUES (:pos, :row, :picId)";
				$params2 = array(':pos' => $y, ':row' => $row_string, ':picId' => $id);
				$sth2 = $database->prepareSQL($query2);
				$sth2->execute($params2);
				// update position`
				$query3 = "UPDATE `db_pictures`.`pixels` SET `status` = :status WHERE `pixels`.`id` = :id;";
				$params3 = array(':status' => $y, ':id' => $id);
				$sth3 = $database->prepareSQL($query3);
				$sth3->execute($params3);
			}	
		}
		echo "Complete";
	}
	
	$database->closeDB();
?>