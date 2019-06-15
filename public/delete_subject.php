<?php include("../includes/session.php"); ?>
<?php include("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?> 

<?php confirm_logged_in(); ?>

<?php 
	$current_subject = find_subject_by_id($_GET['subject'], false);
	if(!$current_subject) {
		redirect_to("manage_content.php");
	} 

	$pages_set = find_pages_for_subject($current_subject['id'], false);
	if(mysqli_num_rows($pages_set) > 0) {
		//This means this subject has pages.
		$_SESSION['message'] = "Can't delete subject with pages";
		redirect_to("manage_content.php?subject={$current_subject['id']}");
	}

	$id = $current_subject["id"];
	$query = "DELETE FROM subjects WHERE id = {$id} LIMIT 1";

	$result = mysqli_query($connection, $query);
	confirm_query($result, $query);

	if($result && mysqli_affected_rows($connection) == 1) {
		$_SESSION['message'] = "Subject deleted successfully";
		redirect_to("manage_content.php");
	} else {
		$_SESSION['message'] = "There was some problem deleting the subject";
		redirect_to("manage_content.php?subject={$id}");
	}
?>