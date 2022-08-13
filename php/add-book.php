<?php  
session_start();


// Using sessions to check if the admin is the admin
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	// Database file conn
	include "../admin/db_conn.php";

    # Validation helper function
    include "func-validation.php";

    # File Upload helper function
    include "func-file-upload.php";


    // Check if all the input form have been entered
	if (isset($_POST['book_title'])       &&
        isset($_POST['book_description']) &&
        isset($_POST['isbn'])    &&
        isset($_POST['book_author'])      &&
        isset($_POST['book_category'])    &&
        isset($_FILES['book_cover'])      &&
        isset($_FILES['file'])) {
		
			
			// Store the values from the form in each variables
		$title       = $_POST['book_title'];
		$description = $_POST['book_description'];
		$isbn = $_POST['isbn'];
		$author      = $_POST['book_author'];
		$category    = $_POST['book_category'];


		$user_input = 'title='.$title.'&category_id='.$category.'&desc='.$description.'&isbn='.$isbn.'&author_id='.$author;

		
		// Validate the input form to make sure it is entered

        $text = "Book title";
        $location = "../admin/add-book.php";
        $ms = "error";
		is_empty($title, $text, $location, $ms, $user_input);

		$text = "Book description";
        $location = "../admin/add-book.php";
        $ms = "error";
		is_empty($description, $text, $location, $ms, $user_input);

		$text = "Isbn";
        $location = "../admin/add-book.php";
        $ms = "error";
		is_empty($isbn, $text, $location, $ms, $user_input);

		$text = "Book author";
        $location = "../admin/add-book.php";
        $ms = "error";
		is_empty($author, $text, $location, $ms, $user_input);

		$text = "Book category";
        $location = "../admin/add-book.php";
        $ms = "error";
		is_empty($category, $text, $location, $ms, $user_input);
        
        # book cover Uploading
        $allowed_image_exs = array("jpg", "jpeg", "png");
        $path = "cover";
        $book_cover = upload_file($_FILES['book_cover'], $allowed_image_exs, $path);

        /**
	    If error occurred while 
	    uploading the book cover
	    **/
	    if ($book_cover['status'] == "error") {
	    	$em = $book_cover['data'];

	    	/**
	    	  Redirect to '../admin/add-book.php' 
	    	  and passing error message & user_input
	    	**/
	    	header("Location: ../admin/add-book.php?error=$em&$user_input");
	    	exit;
	    }else {
	    	# file Uploading
            $allowed_file_exs = array("pdf", "docx", "pptx");
            $path = "files";
            $file = upload_file($_FILES['file'], $allowed_file_exs, $path);

            /**
		    If error occurred while 
		    uploading the file
		    **/
		    if ($file['status'] == "error") {
		    	$em = $file['data'];

		    	/**
		    	  Redirect to '../admin/add-book.php' 
		    	  and passing error message & user_input
		    	**/
		    	header("Location: ../admin/add-book.php?error=$em&$user_input");
		    	exit;
		    }else {
		    	/**
		          Getting the new file name 
		          and book cover name 
		        **/
		        $file_URL = $file['data'];
		        $book_cover_URL = $book_cover['data'];
                
                # Insert the data into database
                $sql  = "INSERT INTO books (title,
                                            author_id,
                                            description,
											isbn,
                                            category_id,
                                            cover,
                                            file)
                         VALUES (?,?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
			    $res  = $stmt->execute([$title, $author, $description, $isbn, $category, $book_cover_URL, $file_URL]);

			/**
		      If there is no error while 
		      inserting the data
		    **/
		     if ($res) {
		     	# success message
		     	$sm = "The book successfully created!";
				header("Location: ../admin/add-book.php?success=$sm");
	            exit;
		     }else{
		     	# Error message
		     	$em = "Unknown Error Occurred!";
				header("Location: ../admin/add-book.php?error=$em");
	            exit;
		     }

		    }
	    }

		
	}else {
      header("Location: ../admin.php");
      exit;
	}

}else{
  header("Location: ../login.php");
  exit;
}