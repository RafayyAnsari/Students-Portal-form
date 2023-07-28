<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
  .form-container {
    max-width: 400px;
    margin: 0 auto;
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
  }

  .form-group input {
    width: 100%;
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ccc;
  }

  .form-group input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    cursor: pointer;
  }

  .form-group input[type="submit"]:hover {
    background-color: #45a049;
  }
  h1 {
  text-align: center;
  text-decoration: underline;
  font-size: 30px;
  font-weight: bold;
  color: #324AB2;
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
    <h1>Student form</h1>
<div class="form-container">
    <form action="process_form.php" method="GET">

    <div class="form-group">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required>
    </div>

    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>
    </div>

    <div class="form-group">
      <label for="studentID">Student ID:</label>
      <input type="text" id="studentID" name="studentID" required>
    </div>

    <div class="form-group">
                <label for="course">Course:</label>
                <select id="course" name="course" required>
                    <option value="">Select a course</option>
                    <?php
                    // Assuming you have a MySQL database
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "mystudents";

                    // Create a connection
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $query = "SELECT course_name FROM courses where status=1";

                    $result = $conn->query($query);

                    while ($row = $result->fetch_assoc()) {
                        echo "<option value=\"" . $row['course_name'] . "\">" . $row['course_name'] . "</option>";
                    }
                  
                    $conn->close();
                    ?>
                </select>
            </div>

    <div class="form-group">
        <label for="year">Year of Course:</label>
    <select name="year" id="year">
      <option value="1">1st Year</option>
      <option value="2">2nd Year</option>
      <option value="3">3rd Year</option>
      <option value="4">4th Year</option>
    </select>
    </div>

    <div class="form-group">
      <label for="address">Address:</label>
      <input type="text" id="address" name="address" required>
    </div>

    <div class="form-group">
      <label for="phone">Phone Number:</label>
      <input type="tel" id="phone" name="phone" required>
    </div>

    <div class="form-group">
      <label for="dob">Date of Birth:</label>
      <input type="date" id="dob" name="dob" required>
    </div>

    <div class="form-group">
      <label for="gender">Gender:</label>
      <input type="text" id="gender" name="gender" required>
    </div>

    <div class="form-group">
      <label for="nationality">Nationality:</label>
      <input type="text" id="nationality" name="nationality" required>
    </div>
    <div>
      <label for="documentation">Valid Documentation:</label><br>
      <input type="checkbox" id="cnic" name="documentation[]" value="CNIC">
      <label for="cnic">CNIC</label><br>
      <input type="checkbox" id="bform" name= "documentation[]" value="B-form">
      <label for="bform">B-form</label>
    </div>

    <div class="form-group">
      <input type="submit" value="Submit">
    </div>
    </form>
    </div>

</body>
</html>