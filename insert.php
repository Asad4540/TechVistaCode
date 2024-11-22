<?php

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "techvistacode"; // Change to your database name

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Database connection failed: " .htmlspecialchars($conn->connect_error));
}

// Check if the form is submitted via POST
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validate input fields
    if (empty($name) || empty($email) || empty($contact) || empty($message)) {
        echo"<script>
            alert('All fields are required!');
            window.location = 'index.html';
        </script>";
    }

    // Prepare SQL statement to prevent SQL injection
    // $stmt = $conn->prepare("INSERT INTO formdata (name,email,contact,message) VALUES (?, ?, ?, ?)");
    // if (!$stmt) {
    //     echo "Failed to prepare statement: " . htmlspecialchars($conn->error);
    //     exit();
    // }
    // $stmt->bind_param("ssss", $name, $email,$contact,$message);

    $stmt = "INSERT INTO formdata(name,email,contact,message) VALUES ('$name','$email','$contact','$message')";

    // Execute and check the result
    // if ($stmt->execute()) {
    //     // echo "Form submitted successfully!";
    //     // header("Location:index.html");
    //     echo"<script>
    //         alert('Form Submitted Successfully!');
    //         window.location = 'index.html';
    //     </script>";
    // } else {
    //     echo "Error inserting data: " . htmlspecialchars($stmt->error);
    // }

    $response=array();
    if ($conn->query($stmt)===TRUE){
        $response['success']=true;
        $response['message']="Form submitted successfully";
    } else {
        $response['success']=false;
        $response['message']="Error: " .$stmt . "<br>" .$conn->error;
    }

  
// } else {
//     echo "Invalid request method.";
// }

$conn->close();
header('Content-Type:application/json');
echo json_encode($response)

?>
