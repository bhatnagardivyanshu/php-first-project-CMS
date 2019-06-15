<?php include("../includes/session.php") ?>
<?php include("../includes/db_connection.php") ?>
<?php require_once("../includes/functions.php") ?>
<?php confirm_logged_in(); ?>

<?php 
	$current_page = find_page_by_id($_GET['page'], false);
	if(!$current_page) {
		redirect_to("manage_content.php");
	}

	$id = $current_page['id'];
	$query  = "DELETE from pages ";
	$query .= "WHERE id = {$id} ";
	$query .= "LIMIT 1";

	$result = mysqli_query($connection, $query);

	if($result && mysqli_affected_rows($connection) == 1 ) {
		$_SESSION['message'] = "{$current_page['menu_name']} has been deleted successfully";
		redirect_to("manage_content.php");
	} else {
		$_SESSION['message'] = "There was some problem deleting the page";
		redirect_to("manage_content.php?page={$current_page['id']}");
	}

 ?>