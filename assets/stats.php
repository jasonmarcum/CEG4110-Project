<!DOCTYPE HTML>

<?php
$mysqli = new mysqli("localhost", "root", "seefood", "ProcessedImages");
if ($mysqli->connect_error) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_error . ") " . $mysqli->connect_error;
}

// need code to get values form txt file for donut chart
?>
<html>
	<head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" type="image/jpg" href="/css/images/taco.jpg">

		<title>Food Stats</title>

		<!--[if lte IE 8]><script src="/js/ie/html5shiv.js"></script><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="/css/ie9.css" /><![endif]-->
        <link rel="stylesheet" href="/css/sub-main.css" />
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	</head>
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
                                <li class="active">
                                    <a href="about.html">Upload <span class="sr-only">(current)</span></a>
                                </li>
                                <li>
                                    <a href="about.html">About Us <span class="sr-only">(current)</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

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
                                    <label>Upload Image</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <span class="btn btn-default btn-file">
                                                Browse… <input type="file" id="imgInp">
                                            </span>
                                        </span>
                                        <input type="text" class="form-control" readonly>
                                    </div>
                                    <img id='img-upload'/>
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

                                    <div class="col-lg-3 col-sm-4 col-xs-6"><a title="Image 4" href="#"><img class="thumbnail img-responsive" src="//placehold.it/600x350/992233"></a></div>
                                    <div class="col-lg-3 col-sm-4 col-xs-6"><a title="Foodines = 10/10\" href="#"><img class="thumbnail img-responsive" src="//placehold.it/600x350/449955/FFF"></a></div>
                                    <div class="col-lg-3 col-sm-4 col-xs-6"><a title="Image 8" href="#"><img class="thumbnail img-responsive" src="//placehold.it/600x350/777"></a></div>
                                    <div class="col-lg-3 col-sm-4 col-xs-6"><a title="Image 9" href="#"><img class="thumbnail img-responsive" src="//placehold.it/600x350/992233"></a></div>
                                    <div class="col-lg-3 col-sm-4 col-xs-6"><a title="Image 10" href="#"><img class="thumbnail img-responsive" src="//placehold.it/600x350/EEE"></a></div>
                                    <div class="col-lg-3 col-sm-4 col-xs-6"><a title="Image 12" href="#"><img class="thumbnail img-responsive" src="//placehold.it/600x350/DDD"></a></div>
                                    <div class="col-lg-3 col-sm-4 col-xs-6"><a title="Image 13" href="#"><img class="thumbnail img-responsive" src="//placehold.it/600x350/992233"></a></div>

                                    <hr>

                                    <hr>
                                </div>
                            </div>
                            <div tabindex="-1" class="modal fade" id="myModal" role="dialog">
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
                        </p>
                    </div>
                    <!-- with food gallery tab -->
                    <div id="menu2" class="tab-pane fade">
                        <h3>Look at the lack of foods</h3>
                        <p>
                            foreach that queries all pic with food
                        </p>
                        <?php
                            $sql = "SELECT * FROM Image_Info WHERE is_food = 1 ORDER BY -composite_score";
                            if(!$result = $mysqli->query($sql)){
                                die('There was an error running the query [' . $mysqli->error . ']');
                            }
                            
                            $index = 1;
                            while($row = $result->fetch_assoc()){
                                //echo $row['url'] . $row['food_score']. $row['not_food_score'] . '<br />';
                                echo '<div class="col-lg-3 col-sm-4 col-xs-6"><a title="Image $index" href="#"><img class="thumbnail img-responsive" src="../' . $row['url'] . '"></a></div>';
                                $index ++;
                            }
                        ?>
                    </div>
                    <!-- without food gallery tab -->
                    <div>
                        <div id="menu3" class="tab-pane fad">
                            <p>
                                foreach that queries all pic without food
                            </p>
                            <?php
                                $sql = "SELECT * FROM Image_Info WHERE is_food = 0 ORDER BY -composite_score";
                                if(!$result = $mysqli->query($sql)){
                                    die('There was an error running the query [' . $mysqli->error . ']');
                                }
                                
                                $index = 1;
                                while($row = $result->fetch_assoc()){
                                    //echo $row['url'] . $row['food_score']. $row['not_food_score'] . '<br />';
                                    echo '<div class="col-lg-3 col-sm-4 col-xs-6"><a title="Image $index" href="#"><img class="thumbnail img-responsive" src="../' . $row['url'] . '"></a></div>';
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
                            data: [12, 19, 3],
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