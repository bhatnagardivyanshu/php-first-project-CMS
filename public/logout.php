<?php include("../includes/session.php"); ?>
<?php include("../includes/functions.php"); ?>

<?php 
	$_SESSION["admin_id"] = null;
	$_SESSION["username"] = null;
	redirect_to("login.php");
?>