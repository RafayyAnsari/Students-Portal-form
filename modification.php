<?php
ob_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mystudents";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle record deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM students WHERE studentID = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();

    // Redirect to the updated student list
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Retrieve all student records
$query = "SELECT * FROM students";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th,
    table td {
        border: 1px solid #ddd;
        padding: 8px;
    }

    table th {
        background-color: #f2f2f2;
    }

    form {
        width: 800px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #f7f7f7;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    form label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
        color: #333;
    }

    form input[type="text"],
    form input[type="email"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        transition: border-color 0.3s ease;
    }

    form input[type="text"]:focus,
    form input[type="email"]:focus {
        border-color: #77b5e5;
        outline: none;
    }

    form input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    form input[type="submit"]:hover {
        background-color: #45a049;
    }
    select {
    width: 100%;
    padding: 8px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    }

    select:focus {
    outline: none;
    border-color: #0066cc;
    box-shadow: 0 0 5px rgba(0, 102, 204, 0.8);
    }
</style>

</head>
<body>
    <h1>Student List</h1>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Student ID</th>
            <th>Course</th>
            <th>Year</th>
            <th>Address</th>
            <th>Phone</th>
            <th>DOB</th>
            <th>Gender</th>
            <th>Nationality</th>
            <th>Documentation</th>
            <th>Action</th>
        </tr>
        <?php
        // Check if there are any student records
        if ($result->num_rows > 0) {
            // Iterate through the result set and display each student record
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row['studentID']."</td>";
                echo "<td>".$row['course']."</td>";
                echo "<td>".$row['year']."</td>";
                echo "<td>".$row['address']."</td>";
                echo "<td>".$row['phone']."</td>";
                echo "<td>".$row['dob']."</td>";
                echo "<td>".$row['gender']."</td>";
                echo "<td>".$row['nationality']."</td>";
                echo "<td>".$row['documentation']."</td>";
                echo "<td>";
                // Add action links for update, view details, and delete
                echo "<a href='?action=update&id=".$row['studentID']."'>Update</a> | ";
                echo "<a href='?action=view&id=".$row['studentID']."'>View</a> | ";
                echo "<a href='?action=delete&id=".$row['studentID']."'>Delete</a>";
                echo "</td>";
                echo "</tr>";
                
            }
        } else {
            echo "<tr><td colspan='4'>No student records found.</td></tr>";
        }
        ?>
    </table>

    <?php
    // Handle record update and view
    if (isset($_GET['action']) && isset($_GET['id'])) {
        $id = $_GET['id'];

        // Retrieve the student record by ID
        $stmt = $conn->prepare("SELECT * FROM students WHERE studentID = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Check if the student record exists
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row['name'];
            $email = $row['email'];
            $studentID = $row['studentID'];
            $course = $row['course'];
            $year = $row['year'];
            $address = $row['address'];
            $phone = $row['phone'];
            $dob = $row['dob'];
            $gender = $row['gender'];
            $nationality = $row['nationality'];
            $documentation = $row['documentation'];
            // Retrieve other fields if needed
                
            // Handle update action
            if ($_GET['action'] === 'update') {
                // Retrieve the courses from the "courses" table
                $stmt = $conn->prepare("SELECT * FROM courses");
                $stmt->execute();
                $coursesResult = $stmt->get_result();
                $courses = [];
                while ($coursesRow = $coursesResult->fetch_assoc()) {
                    $courses[] = $coursesRow['course_name'];
                }

                // Display the update form with pre-filled values
                echo "<h2>Update Student Record</h2>";
                echo "<form action='' method='post'>";
                echo "Name: <input type='text' name='name' value='$name'><br>";
                echo "Email: <input type='text' name='email' value='$email'><br>";
                echo "Student ID: <input type='text' name='studentID' value='$studentID'><br>";
                echo "Course: <select name='course'>";
                foreach ($courses as $courseOption) {
                    $selected = ($courseOption == $course) ? "selected" : "";
                    echo "<option value='$courseOption' $selected>$courseOption</option>";
                }
                echo "</select><br>";
                echo "Year: <select name='year'>";
                $yearOptions = ['1st', '2nd', '3rd', '4th'];
                foreach ($yearOptions as $yearOption) {
                    $selected = ($yearOption == $year) ? "selected" : "";
                    echo "<option value='$yearOption' $selected>$yearOption</option>";
                }
                echo "<br>";
                echo "</select><br>";
                echo "Address: <input type='text' name='address' value='$address'><br>";
                echo "Phone: <input type='text' name='phone' value='$phone'><br>";
                echo "DOB: <input type='text' name='dob' value='$dob'><br>";
                echo "Gender: <input type='text' name='gender' value='$gender'><br>";
                echo "Nationality: <input type='text' name='nationality' value='$nationality'><br>"; 

                // Create an array of checkboxes with their respective labels
                $documentationOptions = ['CNIC', 'B-form'];
                $selectedOptions = explode(',', $documentation);
                
                echo "Documentation:<br>";
                foreach ($documentationOptions as $option) {
                    $checked = in_array($option, $selectedOptions) ? "checked" : "";
                    echo "<input type='checkbox' name='documentation[]' value='$option' $checked> $option<br>";
                }

                echo "<input type='submit' value='Update'>";
                echo "</form>";

                // Handle the form submission for updating the record
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $name = $_POST['name'];
                    $email = $_POST['email'];
                    $studentID = $_POST['studentID'];
                    $course = $_POST['course'];
                    $year = $_POST['year'];
                    $address = $_POST['address'];
                    $phone = $_POST['phone'];
                    $dob = $_POST['dob'];
                    $gender = $_POST['gender'];
                    $nationality = $_POST['nationality'];

                    // Update the selected documentation options
                    $selectedOptions = isset($_POST['documentation']) ? $_POST['documentation'] : [];
                    $documentation = implode(',', $selectedOptions);

                    // Prepare and execute the update statement
                    $stmt = $conn->prepare("UPDATE students SET name=?, email=?, studentID=?, course=?, year=?, address=?, phone=?, dob=?, gender=?, nationality=?, documentation=? WHERE studentID=?");
                    $stmt->bind_param("ssssssssssss", $name, $email, $studentID, $course, $year, $address, $phone, $dob, $gender, $nationality, $documentation, $id);

                    $stmt->execute();
                
                    // Redirect to the updated student list
                    header("Location: ".$_SERVER['PHP_SELF']);
                    exit();
                }
                
            }

            // Handle view action
                    if ($_GET['action'] === 'view') {
                    // Display the student details
                    echo "<h2 class='section-title'>Student Details</h2>";
                    echo "<p><span class='field-label'>Name:</span> $name</p>";
                    echo "<p><span class='field-label'>Email:</span> $email</p>";
                    echo "<p><span class='field-label'>Student ID:</span> $studentID</p>";
                    echo "<p><span class='field-label'>Course:</span> $course</p>";
                    echo "<p><span class='field-label'>Year:</span> $year</p>";
                    echo "<p><span class='field-label'>Address:</span> $address</p>";
                    echo "<p><span class='field-label'>Phone:</span> $phone</p>";
                    echo "<p><span class='field-label'>DOB:</span> $dob</p>";
                    echo "<p><span class='field-label'>Gender:</span> $gender</p>";
                    echo "<p><span class='field-label'>Nationality:</span> $nationality</p>";
                    echo "<p><span class='field-label'>Documentation:</span> $documentation</p>";
}
        } else {
            echo "<h2>Student not found</h2>";
        }
    }
    ?>

</body>
</html>

<?php
$conn->close();
?>