<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mystudents";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully";
}

$documentation = $_GET['documentation'];

// Check if at least one documentation option is selected
if (empty($documentation)) {
    // Handle the error, such as displaying a message to the user or redirecting back to the form page
    die("Error: Please select at least one valid documentation option.");
}

$validDocumentation = implode(', ', $documentation);

// Prepare and bind the form data to insert into the database
$stmt = $conn->prepare("INSERT INTO students (name, email, studentID, course, year, address, phone, dob, gender, nationality, documentation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssss", $name, $email, $studentID, $course, $year, $address, $phone, $dob, $gender, $nationality, $validDocumentation);

// Get the form data from query parameters
$name = $_GET['name'];
$email = $_GET['email'];
$studentID = $_GET['studentID'];
$course = $_GET['course'];
$year = $_GET['year'];
$address = $_GET['address'];
$phone = $_GET['phone'];
$dob = $_GET['dob'];
$gender = $_GET['gender'];
$nationality = $_GET['nationality'];

if (empty($studentID)) {
    // Handle the error, such as displaying a message to the user or redirecting back to the form page
    die("Error: Student ID field cannot be empty.");
}

$stmt->execute();
$stmt->close();
$conn->close();
header("Location: sucess.php");
exit();
?>