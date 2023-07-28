<?php
// Establish a connection to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mystudents";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate the filter query based on the selected criteria
function generateFilterQuery($filters) {
    global $conn;

    $query = "SELECT * FROM students WHERE 1=1";

    if (!empty($filters['course'])) {
        $course = $conn->real_escape_string($filters['course']);
        $query .= " AND course = '$course'";
    }

    if (!empty($filters['nationality'])) {
        $nationality = $conn->real_escape_string($filters['nationality']);
        $query .= " AND nationality = '$nationality'";
    }
    if (!empty($filters['year'])) {
        $year = $conn->real_escape_string($filters['year']);
        $query .= " AND year = '$year'";
    }
    if (!empty($filters['gender'])) {
        $gender = $conn->real_escape_string($filters['gender']);
        $query .= " AND gender = '$gender'";
    }
    // Add your own creative filter conditions here

    return $query;
}

// Get the list of courses from the database
$coursesQuery = "SELECT * FROM courses";
$coursesResult = $conn->query($coursesQuery);

$courses = array();
if ($coursesResult->num_rows > 0) {
    while ($row = $coursesResult->fetch_assoc()) {
        $courses[] = $row['course_name'];
    }
}

// Process the filter form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $filters = array(
        'course' => isset($_POST['course']) ? $_POST['course'] : '',
        'nationality' => isset($_POST['nationality']) ? $_POST['nationality'] : '',
        'year' => isset($_POST['year']) ? $_POST['year'] : '',
        'gender' => isset($_POST['gender']) ? $_POST['gender'] : ''
        
    );
    $filterQuery = generateFilterQuery($filters);

    $result = $conn->query($filterQuery);

    if ($result->num_rows > 0 || !empty($filters['course']) || !empty($filters['nationality']) || !empty($filters['year']) || !empty($filters['gender'])) {
        if (!empty($filters['course']) || !empty($filters['nationality']) || !empty($filters['year']) || !empty($filters['gender'])) {
            // Calculate the total number of filtered students
            $totalFilteredStudentsQuery = "SELECT COUNT(*) as total FROM ($filterQuery) as filtered_students";
            $totalFilteredStudentsResult = $conn->query($totalFilteredStudentsQuery);
            $totalFilteredStudents = $totalFilteredStudentsResult->fetch_assoc()['total'];
    
            echo "<div style='text-align: center; margin-bottom: 20px;'>";
            echo "No. of Student Enrollments: " . $totalFilteredStudents;
            echo "</div>";

}
        // Display the filtered results in a styled table
        echo "<div class='container'>";
        echo "<table class='students-table'>";
        echo "<tr><th>Name</th><th>Email</th><th>Course</th><th>Year</th><th>Address</th><th>Phone</th><th>DOB</th><th>Gender</th><th>Nationality</th><th>Documentation</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["course"] . "</td>";
            echo "<td>" . $row["year"] . "</td>";
            echo "<td>" . $row["address"] . "</td>";
            echo "<td>" . $row["phone"] . "</td>";
            echo "<td>" . $row["dob"] . "</td>";
            echo "<td>" . $row["gender"] . "</td>";
            echo "<td>" . $row["nationality"] . "</td>";
            echo "<td>" . $row["documentation"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
        echo "</div>";
    } else {
        echo "No results found.";
    }
}
?>
<!-- HTML form for filtering -->
<form method="POST" action="">
    <label for="course">Course:</label>
    <select name="course" id="course">
        <option value="">-- Select the course --</option>
        <?php
        foreach ($courses as $course) {
            echo "<option value='$course'>$course</option>";
        }
        ?>
    </select>
    <br>

    <label for="nationality">Nationality:</label>
    <select name="nationality" id="nationality">
        <option value="">-- Select Nationality --</option>
        <option value="UK">UK</option>
        <option value="USA">USA</option>
        <option value="Australia">Australia</option>
        <option value="Germany">Germany</option>
        <option value="Canada">Canada</option>
    </select>
    <br>

    <label for="year">Year:</label>
    <select name="year" id="year">
        <option value="">-- Select Year --</option>
        <option value="1st">1st</option>
        <option value="2nd">2nd</option>
        <option value="3rd">3rd</option>
        <option value="4th">4th</option>
    </select>
    <br>

    <label for="gender">Gender:</label>
    <select name="gender" id="gender">
        <option value="">-- Select Gender --</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select>
    <br>

    <!-- Add more filter fields as needed -->

    <input type="submit" value="Filter">
</form>
<!-- General Dashboard -->
<div class="dashboard">
    <?php
    
    // Total number of students registered
    $totalStudentsQuery = "SELECT COUNT(*) as total FROM students";
    $totalStudentsResult = $conn->query($totalStudentsQuery);
    $totalStudents = $totalStudentsResult->fetch_assoc()['total'];

    echo "<div>Total Students Registered: " . $totalStudents . "</div>";

    // Count of male and female students
    $maleCountQuery = "SELECT COUNT(*) as maleCount FROM students WHERE gender = 'Male'";
    $maleCountResult = $conn->query($maleCountQuery);
    $maleCount = $maleCountResult->fetch_assoc()['maleCount'];

    $femaleCountQuery = "SELECT COUNT(*) as femaleCount FROM students WHERE gender = 'Female'";
    $femaleCountResult = $conn->query($femaleCountQuery);
    $femaleCount = $femaleCountResult->fetch_assoc()['femaleCount'];

    echo "<div>Male Students: " . $maleCount . "</div>";
    echo "<div>Female Students: " . $femaleCount . "</div>";
    
    $courseCountQuery = "SELECT course, COUNT(*) as courseCount FROM students GROUP BY course";
    $courseCountResult = $conn->query($courseCountQuery);
        echo "<br>";
    echo "<div>Course Enrollment:</div>";
    echo "<ul>";
    while ($row = $courseCountResult->fetch_assoc()) {
        $course = $row['course'];
        $count = $row['courseCount'];
        echo "<li>$course: $count students</li>";
    }
echo "</ul>";
    ?>
</div>


<!-- CSS styles -->
<style>
.container {
    max-width: 800px;
    margin: 0 auto;
}

form {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 20px;
    margin-bottom: 20px;
}

form label {
    display: block;
    font-weight: bold;
    margin-bottom: 10px;
}

form select,
form input[type="submit"] {
    padding: 8px;
    font-size: 14px;
    border: 1px solid #ddd;
    border-radius: 4px;
    width: 100%;
    margin-bottom: 10px;
}

.students-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.students-table th,
.students-table td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}

.students-table th {
    background-color: #f2f2f2;
    font-weight: bold;
}

.students-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.students-table tr:hover {
    background-color: #f5f5f5;
}

.students-table td:first-child {
    font-weight: bold;
}

</style>