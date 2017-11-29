<!DOCTYPE HTML>
<html>
	<head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" type="image/jpg" href="css/images/taco.jpg">

		<title>Food Stats</title>

		<!--[if lte IE 8]><script src="/js/ie/html5shiv.js"></script><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="/css/ie9.css" /><![endif]-->
        <link rel="stylesheet" href="css/sub-main.css" />
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
 <?php
$mysqli = new mysqli("localhost", "root", "seefood", "ProcessedImages");
if ($mysqli->connect_error) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_error . ") " . $mysqli->connect_error;
}
// need code to get values form txt file for donut chart
$dfile = fopen("donut.txt", "r") or die("unable to open file");
$sure_food = intval(fgets($dfile));
$sure_not_food = intval(fgets($dfile));
$unsure = intval(fgets($dfile));
fclose($dflie); 
$slist = "[" . $sure_food . ", " . $sure_not_food . ", " . $unsure . "]";
// get IP address for client
$ip = getenv('HTTP_CLIENT_IP')?:
getenv('HTTP_X_FORWARDED_FOR')?:
getenv('HTTP_X_FORWARDED')?:
getenv('HTTP_FORWARDED_FOR')?:
getenv('HTTP_FORWARDED')?:
getenv('REMOTE_ADDR');
$foodiness = "";
$surety = 0;


////////////////////////////////////////////////////////////////
if(isset($_POST["submit"])) {

    $target_dir = "/var/www/html/uploads/";
    $all_images = "";
    $target = $_POST["submit"];
    $uploadOk = 1;
    $error_msg = "";
    
    foreach($_FILES['uploads']['tmp_name'] as $key => $tmp_name){
        $filename = $_FILES['uploads']['name'][$key];
        $target_file = $target_dir . basename($filename);
        $uploadOk = 1;
        //echo $target_file . " is the full path </br>";
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        //echo "image file type is " . $imageFileType;
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
                //echo "Your file is too large.</br>";
                $error_msg .= "Your file is too large.</br>";
                $uploadOk = 0;
            }
    
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                //echo "Only JPG, JPEG, PNG & GIF files are allowed.</br>";
                //echo "This is imageFileType: " . $imageFileType . "</br>";

                $error_msg .= "Only JPG, JPEG, PNG & GIF files are allowed.</br>";
                $error_msg .= "This is imageFileType: " . $imageFileType . "</br>";
                $uploadOk = 0;
            }
    
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                //echo "Your file was not uploaded.\n";

                // //show the modal window
                echo 
                '<!-- Modal -->
                <div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                </button>
                                 <h4 class="modal-title" id="memberModalLabel">Error uploading one or more files</h4>
                
                            </div>
                            <div class="modal-body">
                                <p>' . $error_msg . '</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>';
                echo '<script type="text/javascript">
                $(document).ready(function () {';
                    
                echo "$('#memberModal').modal('show');
                    
                    }); </script>";
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES['uploads']['tmp_name'][$key], $target_file)) {
                    echo "The file ". $filename . " has been uploaded.</br>";
                } else {
                    echo "There was an error uploading your file.</br>";
                }
            }
            //echo "<img src='/uploads/$filename' height='400'>";
            $all_images .= ("'uploads/" . $filename . "' ");
    }
    
    if ($uploadOk == 1){
    echo "</br>All images: " . $all_images . "</br>";
    if($all_images != ""){
        $python = `python findFood4.py {$all_images}`;
    }

    // each image run and scores are in output.txt
    $myfile = fopen("output.txt", "rw") or die("unable to open file");
    
    $dfile = fopen("donut.txt", "r") or die("unable to open file");
    $sure_food = intval(fgets($dfile));
    $sure_not_food = intval(fgets($dfile));
    $unsure = intval(fgets($dfile));
    fclose($dflie);
    //echo $sure_food . '</br>' . $sure_not_food . '</br>' . $unsure;
    

    $index = 0;
    foreach($_FILES['uploads']['tmp_name'] as $key => $tmp_name){
        // get url
        
        $filename = $_FILES['uploads']['name'][$key];
        if($filename == ""){break;}
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
    
        // $ip = getenv('HTTP_CLIENT_IP')?:
        // getenv('HTTP_X_FORWARDED_FOR')?:
        // getenv('HTTP_X_FORWARDED')?:
        // getenv('HTTP_FORWARDED_FOR')?:
        // getenv('HTTP_FORWARDED')?:
        // getenv('REMOTE_ADDR');
        
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
    
    //$mysqli->close();
    fclose($myfile);

    //unset($_POST);
     // Redirect to this page.
   header("Location: " . $_SERVER['REQUEST_URI']);
   exit();
    }
}
?>


	<body class="loading">
        <div class="content">
            <!-- Navbar -->
            <nav class="navbar navbar-fixed-top">
                <div class="container">
                    <div class="row">
                        <div class="col-md-2">
                            <a class="navbar-brand" href="index.html">Find Food</a>
                        </div>
                        <div class="col-md-10">
                            <ul class="nav navbar-nav">
                                <li>
                                    <a href="about.html">About Us <span class="sr-only">(current)</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <!-- Donut Chart -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
                    <div class="chart">
                        <canvas id="myChart" width="40" height="40"></canvas>
                    </div>
				</div>
			</div>
			<div class="row">
                <?php // connect to db, grab pictures ?>
				<!-- loop through gallery -->
                <div class="col-md-3">
                    <!--place image  -->
                </div>
			</div>
		</div>
        <!-- nav pills -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="pill" href="#home">Upload Pictures</a></li>
                        <li><a data-toggle="tab" href="#menu1">Recent Upload</a></li>
                        <li><a data-toggle="tab" href="#menu2">Pictures with food</a></li>
                        <li><a data-toggle="tab" href="#menu3">Pictures without food</a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        <p>
                        <div class="container-fluid navtabs">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <!--<label>Upload Image</label>-->
                                    <div class="input-group">

                                    <form action="stats.php" method="post" enctype="multipart/form-data">
                                    <!--Select image to upload:-->
                                    <input type="file"  name="uploads[]" id="uploads" multiple="multiple" />
                                    <input type="submit" value="Upload Image" name="submit" />
                                    </form>
                                </div>
                            </div>
                        </div>
                        </div>
                        </p>
                    </div>
                    <!-- recent upload tab -->
                    <div id="menu1" class="tab-pane fade">
                        <p>
                            <div class="container-fluid navtabs">
                                <div class="row gallery">

                                    

                                <?php
                                $sql = "SELECT * FROM Image_Info WHERE ip LIKE '%$ip%' AND is_food=0 ORDER BY date_time";
                                if(!$result = $mysqli->query($sql)){
                                    die('There was an error running the query [' . $mysqli->error . ']');
                                }
                                
                                
                                $index = 1;
                                while($row = $result->fetch_assoc()){
                                    echo '
                                    <div class="modal fade" id="modal-' . $row['id'] . '">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button class="close" type="button" data-dismiss="modal">×</button>
                                                    <h3 class="modal-title">Heading</h3>
                                                </div>
                                                <div class="modal-body">
        
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            ';
                                    //echo $row['url'] . $row['food_score']. $row['not_food_score'] . '<br />';
                                    $surety = ($row['composite_score'] / 5) * 100;
                                    if ($row['is_food'] == 1){
                                        $foodiness = "I'm $surety% sure this is food";
                                    }
                                    else{ $foodiness = "I'm $surety% sure this is not food";}
                                    //echo '<div class="col-lg-3 col-sm-4 col-xs-6"><a title="' . $foodiness . '" href="#"><img class="thumbnail img-responsive" src="../' . $row['url'] . '"></a></div>';
                                    echo '<div class="col-lg-3 col-sm-4 col-xs-6"><a title="' . $foodiness . '" data-toggle="modal" data-target="#modal-' . $row['id'] . '"><img class="thumbnail img-responsive" src="' . $row['url'] . '"></a></div>';
                                    $index ++;
                                }
                            ?>
                                </div>
                            </div>
                        </p>
                    </div>
                    <!-- with food gallery tab -->
                    <div id="menu2" class="tab-pane fade">
                        <h3>Look at all these foods</h3>
                        <?php
                            $sql = "SELECT * FROM Image_Info WHERE is_food = 1 ORDER BY composite_score";
                            if(!$result = $mysqli->query($sql)){
                                die('There was an error running the query [' . $mysqli->error . ']');
                            }
                            while($row = $result->fetch_assoc()){
                                ///////////echo $row['url'] . $row['food_score']. $row['not_food_score'] . '<br />';
                                //echo '<div class="col-lg-3 col-sm-4 col-xs-6"><a title="Foodiness ' . $row['composite_score'] . ' / 10" data-toggle="modal" data-target="#modal-' . $row['id'] . '"><img class="thumbnail img-responsive" src="../' . $row['url'] . '"></a></div>';
                                echo '
                                        <div class="modal fade" id="modal-' . $row['id'] . '">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button class="close" type="button" data-dismiss="modal">×</button>
                                                        <h3 class="modal-title">Heading</h3>
                                                    </div>
                                                    <div class="modal-body">
            
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                ';
                                $surety = ($row['composite_score'] / 5) * 100;
                                $foodiness = "I'm $surety% sure this is food";
                                
                                echo '<div class="col-lg-3 col-sm-4 col-xs-6"><a title="' . $foodiness . '" data-toggle="modal" data-target="#modal-' . $row['id'] . '"><img class="thumbnail img-responsive" src="' . $row['url'] . '"></a></div>';
                                $index ++;
                            }
                        ?>
                    </div>
                    <!-- without food gallery tab -->
                        <div id="menu3" class="tab-pane fade">
                            <?php
                                $sql = "SELECT * FROM Image_Info WHERE is_food = 0 ORDER BY composite_score";
                                if (!$result = $mysqli->query($sql)){
                                    die('There was an error running the query [' . $mysqli->error . ']');
                                }
                                while($row = $result->fetch_assoc()){
                                    echo '
                                        <div class="modal fade" id="modal-' . $row['id'] . '">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button class="close" type="button" data-dismiss="modal">×</button>
                                                        <h3 class="modal-title">Heading</h3>
                                                    </div>
                                                    <div class="modal-body">
            
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ';
                                    $surety = ($row['composite_score'] / 5) * 100;
                                    $foodiness = "I'm $surety% sure this is not food";
                                    
                                    echo '<div class="col-lg-3 col-sm-4 col-xs-6"><a title="' . $foodiness . '" data-toggle="modal" data-target="#modal-' . $row['id'] . '"><img class="thumbnail img-responsive" src="' . $row['url'] . '"></a></div>';
                                    $index ++;
                                }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		<!-- Footer -->
		<!-- <footer id="footer">
			<span class="copyright">CS 4110 &nbsp;&bull;&nbsp; Intro to Software Engineering</span>
		</footer> -->

		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.js"></script>
		<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- donut chart -->
		<script type="text/javascript">
            window.onload = function() {
                document.body.className = '';
                var ctx = document.getElementById("myChart").getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ["Very food", "A little bit of food", "No food"],
                        datasets: [{
                            label: 'Amount of Foods',
                            data: <?php echo $slist;?>,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255,99,132,1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                });
            };
            window.ontouchmove = function() { return false; };
            window.onorientationchange = function() { document.body.scrollTop = 0; };
		</script>
        <!-- gallery modal view -->
        <script type="text/javascript">
            $(document).ready(function() {
                $('.thumbnail').click(function(){
                    $('.modal-body').empty();
                    var title = $(this).parent('a').attr("title");
                    $('.modal-title').html(title);
                    $($(this).parents('div').html()).appendTo('.modal-body');
                    $('#myModal').modal({show:true});
                });
            });
        </script>
        <!-- image upload -->
        <script type="text/javascript">
            $(document).ready( function() {
                $(document).on('change', '.btn-file :file', function() {
                    var input = $(this),
                        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                    input.trigger('fileselect', [label]);
                });
                $('.btn-file :file').on('fileselect', function(event, label) {
                    var input = $(this).parents('.input-group').find(':text'),
                        log = label;
                    if( input.length ) {
                        input.val(log);
                    } else {
                        if( log ) alert(log);
                    }
                });
                function readURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('#img-upload').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                }
                $("#imgInp").change(function(){
                    readURL(this);
                });
            });
        </script>
	</body>
</html>

<?php
    $mysqli->close();
?>