
<?php

/* TODO
Better validation of images, i.e. file size, file type, etc
Integrate this code into uploads page for GUI
*/

echo ' 
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html> <h1>SEEFOOD</h1>';
//<img src="/images/image1.png" width="280" height="125" title="Logo of a company" alt="Logo of a company" />

$target_dir = "/var/www/html/uploads/";
$all_images = "";
$target = $_POST["submit"];
$uploadOk = 1;

//if($target=="submit"){ // files to be posted

// if(!isset($_FILES['uploads']) || $_FILES['uploads']['error'] == UPLOAD_ERR_NO_FILE) {
//     echo "Error no file selected"; 
// } else {
//     print_r($_FILES);
// }



foreach($_FILES['uploads']['tmp_name'] as $key => $tmp_name){
	$filename = $_FILES['uploads']['name'][$key];
	$target_file = $target_dir . basename($filename);
    $uploadOk = 1;
	//echo $target_file . " is the full path </br>";
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
		$check = getimagesize($_FILES["uploads"]["tmp_name"]);
		if($check !== false) {
			//echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			//echo "File is not an image.";
			$uploadOk = 0;
		}

		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 5000000) {
			echo "Your file is too large.</br>";
			$uploadOk = 0;
		}

		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			echo "Only JPG, JPEG, PNG & GIF files are allowed.</br>";
			echo "This is imageFileType: " . $imageFileType . "</br>";
			$uploadOk = 0;
		}

		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Your file was not uploaded.\n";
		// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES['uploads']['tmp_name'][$key], $target_file)) {
				echo "The file ". $filename . " has been uploaded.</br>";
			} else {
				echo "There was an error uploading your file.</br>";
			}
		}
        //echo "<img src='/uploads/$filename' height='400'>";
        $all_images .= ('uploads/' . $filename . " ");
}
//echo "</br>All images: " . $all_images . "</br>";
$python = `python findFood.py {$all_images}`;

$mysqli = new mysqli("localhost", "root", "seefood", "ProcessedImages");
if ($mysqli->connect_error) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_error . ") " . $mysqli->connect_error;
}
//echo "connected successfully";
// each image run and scores are in output.txt
$myfile = fopen("output.txt", "rw") or die("unable to open file");

$dfile = fopen("donut.txt", "r") or die("unable to open file");
$sure_food = intval(fgets($dfile));
$sure_not_food = intval(fgets($dfile));
$unsure = intval(fgets($dfile));
fclose($dflie);
echo $sure_food . '</br>' . $sure_not_food . '</br>' . $unsure;

$index = 0;
foreach($_FILES['uploads']['tmp_name'] as $key => $tmp_name){
	// get url
	$filename = $_FILES['uploads']['name'][$key];
	$url = "uploads/" . $filename;

	$food_score = fgets($myfile);
	$not_food_score = fgets($myfile);
	//echo $score1 . "</br>" . $score2 . "</br>";
	// turn food scores into floats
	$food_score = floatval($food_score);
	$not_food_score = floatval($not_food_score);
	$composite_score = abs($food_score - $not_food_score);

	// is it food?
	if($food_score > $not_food_score){
		$is_food = 1;
	} else {$is_food = 0;}

	$ip = getenv('HTTP_CLIENT_IP')?:
	getenv('HTTP_X_FORWARDED_FOR')?:
	getenv('HTTP_X_FORWARDED')?:
	getenv('HTTP_FORWARDED_FOR')?:
	getenv('HTTP_FORWARDED')?:
	getenv('REMOTE_ADDR');
	
	//echo "IP is : " . $ip . '</br>';


	$sql = "INSERT INTO Image_Info (url, food_score, not_food_score,
	is_food, ip, composite_score)
	VALUES ('$url', '$food_score', '$not_food_score', '$is_food', 
			'$ip', '$composite_score')";
	
	//echo "</br>SQL Query: $sql </br>";

	if($mysqli->query($sql) === FALSE){
		echo "Error: " . $sql . "<br>" . $mysqli->error;
	}

	// code below is used for donut chart information
	// We need to know how sure the algorithm is
	// if the distance between the two scores is less than 1, we're not sure.
	
	$i = $food_score - $not_food_score;
	
	if($composite_score < 1){
		$unsure++;
	} else{
		if($is_food == 1){ $sure_food++;}
		else{$sure_not_food++;}
	}
}

// now write updated donut data to file
$dfile = fopen("donut.txt", "w") or die("unable to open file");
fwrite($dfile, $sure_food . "\n");
fwrite($dfile, $sure_not_food . "\n" );
fwrite($dfile, $unsure . "\n");
fclose($dflie);

// $sql = "SELECT * FROM Image_Info WHERE is_food = 0 ORDER BY composite_score";
// if(!$result = $mysqli->query($sql)){
//     die('There was an error running the query [' . $mysqli->error . ']');
// }

// while($row = $result->fetch_assoc()){
//     echo "</br>" . $row['url'] . $row['food_score']. $row['not_food_score'] . '</br>';
// }

$mysqli->close();
fclose($myfile);

// echo '<script type="text/javascript">
// window.location = "http://www.google.com/"
// </script>';
echo '<a href="assets/stats.php">stats</a></br>';
echo $sure_food . '</br>' . $sure_not_food . '</br>' . $unsure;
//}
 echo '</html>';

?>


