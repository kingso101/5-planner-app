<?php 
// var_dump($_FILES)."\n";
// var_dump($_POST);
	
	// if ( 0 < $_FILES['file']['error'] ) {
 //        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
 //    }
 //    else {
 //    	// var_dump($_POST);
 //    		echo $_POST['username'] ."\n";
	// 		echo $_POST['email'] ."\n";
	// 		echo $_POST['password'] ."\n";
	// 		echo $_POST['gender'] ."\n";
 //       echo $_FILES['file']['name'];
 //       // echo $_POST['username'];
 //        // move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $_FILES['file']['name']);
 //    }


// if(isset($_GET["firstname"]) && isset($_GET["profile_img"]) ){
// 		var_dump($_GET);
// 		$_id =  $_GET['_id'];
// 		$firstname =  $_GET['firstname'];
// 		$lastname =  $_GET['lastname'];
// 		$email =  $_GET['email'];
// 		$password1 = $_GET['password1'];
// 		$password2 =  $_GET['password2'];
// 		$gender =  $_GET['gender'];
// 		$ip_address =  $_GET['ip_address'];
// 		$contact_number =  $_GET['contact_number'];
// 		$access_level =  $_GET['access_level'];
// 		$address =  $_GET['address'];
// 		// name of the uploaded file
// 		$filename =  $_GET['profile_img'];
// 		echo $filename;


	   	
	if(isset($_GET["firstname"]) && isset($_GET["lastname"]) && isset($_GET["email"]) && isset($_GET["password1"])  && isset($_GET["password2"]) && isset($_GET["_id"]) && isset($_GET["gender"]) && isset($_GET["access_level"]) && isset($_GET["address"]) && isset($_GET["contact_number"])  && isset($_GET["ip_address"]) && isset($_GET["profile_img"])){
		var_dump($_GET);
		$_id =  $_GET['_id'];
		$firstname =  $_GET['firstname'];
		$lastname =  $_GET['lastname'];
		$email =  $_GET['email'];
		$password1 = $_GET['password1'];
		$password2 =  $_GET['password2'];
		$gender =  $_GET['gender'];
		$ip_address =  $_GET['ip_address'];
		$contact_number =  $_GET['contact_number'];
		$access_level =  $_GET['access_level'];
		$address =  $_GET['address'];
		if ($password1 == $password2) {
			$password = $password2;
		}
		// name of the uploaded file
		$filename =  $_GET['profile_img'];
		$arr = explode(".", $filename);
		$file_ext = end($arr);

		// salt for clarity in file name
		$salt = time();
		// destination of the file on the server
	    $destination = '../../uploads/record-label_' . $filename .$salt;

	    // get the file extension
	    $extension = pathinfo($filename, PATHINFO_EXTENSION);

	    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'zip', 'pdf', 'docx'])) {
	        echo "You file extension must be .jpg, .jpeg, .png, .gif, .zip, .pdf or .docx";
	    }else {
	        // move the uploaded (temporary) file to the specified destination
	        if (move_uploaded_file($filename, $destination)) {

	            echo json_encode(
			        array('status' => true,
			    		'message' => 'File uploaded successfully.')
			    );
	        } else {
	            echo json_encode(
			        array('status' => false,
			    		'message' => 'Failed to upload file.')
			    );
	        }
	    }
	}




	// if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["ip_address"]) && isset($_POST["gender"]) && isset($_POST["access_level"]) && !empty($_FILES['profile_img'])){

	// 	$name = $_FILES['profile_img']['name'];
	//    // list($txt, $ext) = explode(".", $name);
	//    // $image_name = time().".".$ext;
	//    // $tmp = $_FILES['profile_img']['tmp_name'];

	//    echo $name."\n";
	//    echo $_POST["username"]."\n";
	//    echo $_POST["email"]."\n";
	//    echo $_POST["gender"]."\n";
	//    // var_dump($_POST);
	//    // foreach ($_POST as $value) {
	//    // 		$email = $value['email'];
	//    // 		echo $email;
	//    // }
	//    // if(move_uploaded_file($tmp, 'uploads/'.$image_name)){
	//    //    echo "<img width='300px' src='uploads/".$image_name."' class='preview'>";
	//    // }else{
	//    //    echo "image uploading failed";
	//    // }
	// }else{
	//    echo "Form inputs cannot be empty.";
	// }


	// $uploadDir = '../../uploads/'; 
	// $response = array( 
	//     'status' => 0, 
	//     'message' => 'Form submission failed, please try again.' 
	// ); 
	 
	// // If form is submitted 
	// // if(isset($_POST['name']) || isset($_POST['email']) || isset($_POST['file'])){ 
	 
	// if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password1"]) && isset($_POST["ip_address"]) && isset($_POST["gender"]) && isset($_POST["access_level"]) && !empty($_FILES['profile_img'])){
	//     // Get the submitted form data 
	//     $username = $_POST['username']; 
	//     $email = $_POST['email']; 
	     
	//     // Check whether submitted data is not empty 
	//     if(!empty($username) && !empty($email)){ 
	//         // Validate email 
	//         if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){ 
	//             $response['message'] = 'Please enter a valid email.'; 
	//         }else{ 
	//             $uploadStatus = 1; 
	             
	//             // Upload file 
	//             $uploadedFile = ''; 
	//             if(!empty($_FILES["profile_img"]["name"])){ 
	                 
	//                 // File path config 
	//                 $fileName = basename($_FILES["profile_img"]["name"]); 
	//                 $targetFilePath = $uploadDir . $fileName; 
	//                 $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
	                 
	//                 // Allow certain file formats 
	//                 $allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg'); 
	//                 if(in_array($fileType, $allowTypes)){ 
	//                     // Upload file to the server 
	//                     if(move_uploaded_file($_FILES["profile_img"]["tmp_name"], $targetFilePath)){ 
	//                         $uploadedFile = $fileName; 
	//                     }else{ 
	//                         $uploadStatus = 0; 
	//                         $response['message'] = 'Sorry, there was an error uploading your file.'; 
	//                     } 
	//                 }else{ 
	//                     $uploadStatus = 0; 
	//                     $response['message'] = 'Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.'; 
	//                 } 
	//             } 
	             
	//             if($uploadStatus == 1){ 
	//                 // // Include the database config file 
	//                 // include_once 'dbConfig.php'; 
	                 
	//                 // // Insert form data in the database 
	//                 // $insert = $db->query("INSERT INTO form_data (name,email,file_name) VALUES ('".$name."','".$email."','".$uploadedFile."')"); 
	                 
	//                 // if($insert){ 
	//                 //     $response['status'] = 1; 
	//                 //     $response['message'] = 'Form data submitted successfully!'; 
	//                 // } 
	//                 $response['message'] = 'Form data submitted successfully!'; 
	//             } 
	//         } 
	//     }else{ 
	//          $response['message'] = 'Form inputs cannot be empty.'; 
	//     } 
	// } 
	 
	// // Return response 
	// echo json_encode($response);



?>