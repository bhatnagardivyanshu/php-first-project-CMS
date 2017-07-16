<?php 
	
	define("DB_SERVER", "localhost");
	define("DB_USER", "widget_cms");
	define("DB_PASS", "divyanshu");
	define("DB_NAME", "widget_corp"); 

	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

	if(mysqli_connect_errno()){
		die("Connection to Database failed!") . mysqli_connect_error() . " ( " . mysqli_connect_errno() . " ) ";
	} else { ?>
		<!-- <script>alert("Database connection successfully created");</script> -->
<?php	}

 ?>