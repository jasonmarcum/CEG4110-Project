
<?php

$target_dir = "/var/www/html/uploads/";

echo print_r($_FILES);
if(isset($_POST["submit"])) {
foreach($_FILES["uploads"]["name"] as $key=>$name){
	$target_file = $target_dir . basename($_FILES["uploads"]["name"][$key]);
    $uploadOk = 1;
    echo $target_file . " is the target_file</br>";
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	
		$check = getimagesize($_FILES["uploads"]["tmp_name"][$key]);
		if($check !== false) {
			//echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			//echo "File is not an image.";
			$uploadOk = 0;
		}
    echo '<img src="$target_file">'
	// // Check if file already exists
	// if (file_exists($target_file)) {
	// 	//echo "Sorry, file already exists.";
	// 	$uploadOk = 0;
	// }

	// ///////////////
	// //echo "file is unique";
	// ///////////////

	// // Check file size
	// if ($file["size"] > 5000000) {
	// 	echo "Your file is too large.</br>";
	// 	$uploadOk = 0;
	// }

	// ///////////////
	// //echo "size is ok";
	// ///////////////

	// // Allow certain file formats
	// if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	// && $imageFileType != "gif" ) {
    //     echo "Only JPG, JPEG, PNG & GIF files are allowed.</br>";
	// 	$uploadOk = 0;
	// }
	// // Check if $uploadOk is set to 0 by an error
	// if ($uploadOk == 0) {
	// 	echo "Your file was not uploaded.\n";
	// // if everything is ok, try to upload file
	// } else {
	// 	//echo "well we got here";
	// 	if (move_uploaded_file($_FILES["tmp_name"][$key], $target_file)) {
	// 		//echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</br>";
	// 	} else {
	// 		echo "There was an error uploading your file.</br>";
	// 	}
    // }
}
}

//}
//echo $target_file;
////////////////////////////
//echo $target_file;
//$command = escapeshellcmd('~/seefood-core-ai/find_food.py ' . $target_file);
//$output = shell_exec($command);
//echo "\n" . $command;
//echo $output;

//exec('sudo -u www-data python ~/seefood-core-ai/find_food.py ' . $target_file); 

//$python = `python findFood.py {$target_file}`;
//echo $python;


//$mysqli = new mysqli("54.88.201.92", "root", "seefood", "ProcessedImages");
//$result = $mysqli->query("SELECT 'Hello, dear MySQL user!' AS _message FROM DUAL");
//$row = $result->fetch_assoc();
//echo htmlentities($row['_message']);
 
//echo "now you're connected to the db";
?>


