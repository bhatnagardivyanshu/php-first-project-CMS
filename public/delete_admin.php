<?php include("../includes/session.php"); ?>
<?php include("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?> 

<?php confirm_logged_in(); ?>

<?php 
	$current_admin = find_admin_by_id($_GET['admin']);
	if(!$current_admin) {
		redirect_to("manage_admins.php");
	}
 ?>

 <?php 
 	$query  = "DELETE from admins ";
 	$query .= "WHERE id = {$current_admin['id']}";
 	$query .= " LIMIT 1";

	$result = mysqli_query($connection, $query);
	confirm_query($result, $query);

	if($result && mysqli_affected_rows($connection) == 1)
	{
		$_SESSION['message'] = "The admin has been deleted successfully";
		redirect_to("manage_admins.php");
	} else {
		$_SESSION['message'] = "There was some problem deleting the admin.";
		redirect_to("manage_admins.php");
	}
  ?>

<div id="main">
	<div id="navigation"></div>
	<div id="page">
		
	</div>
</div>

<?php require_once("../includes/layouts/footer.php"); ?> 
