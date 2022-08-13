<?php
	//starting the session
	session_start();
 
	//including the database connection
	include "../admin/db_conn.php";
 
	if(ISSET($_POST['register'])){
		// Setting variables
		$full_name = $_POST['full_name'];
		$email = $_POST['email'];
		$password = $_POST['password'];
 
		// Insertion Query
		$query = "INSERT INTO `admin` (full_name, email, password) VALUES(:full_name, :email, :password)";
		$stmt = $conn->prepare($query);
		$stmt->bindParam(':full_name', $full_name);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':password', $password);
 
		// Check if the execution of query is success
		if($stmt->execute()){
			//setting a 'success' session to save our insertion success message.
			$_SESSION['success'] = "Successfully created an account";
 
			//redirecting to the index.php 
			header('location: ../admin/index.php');
		}
        
	}
    ?>