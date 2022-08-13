/* Name: Alawode Kindness
   Email: kindnessalawode2017@gmail.com
   Instagram: @kindyy01
 */

<?php  
session_start();

// Using sessions to check if the admin is the admin
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

		// Database file conn
	include "../admin/db_conn.php";


    // Get the author name value from the input form 
	// and check if author name is submitted
	if (isset($_POST['author_name'])) {

		// Store the data in the variable
		$name = $_POST['author_name'];

		// Validate the input form to make sure it is entered
		// if not entered, error message
		if (empty($name)) {
			$em = "The author name is required";
			header("Location: ../admin/add-author.php?error=$em");
            exit;
			// If entered, store in db
		}else {
			$sql  = "INSERT INTO authors (name)
			         VALUES (?)";
			$stmt = $conn->prepare($sql);
			$res  = $stmt->execute([$name]);

			// if no error, show confirmation message
			// and reload the same page
		     if ($res) {
		     	# success message
		     	$sm = "Successfully created!";
				header("Location: ../admin/add-author.php?success=$sm");
	            exit;
		     }else{
		     	// Error message if not stored
		     	$em = "Unknown Error Occurred!";
				header("Location: ../add-author.php?error=$em");
	            exit;
		     }
		}
	}else {
      header("Location: ../admin/admin.php");
      exit;
	}

}else{
  header("Location: ../admin.index.php");
  exit;
}


/* Name: Alawode Kindness
   Email: kindnessalawode2017@gmail.com
   Instagram: @kindyy01
 */