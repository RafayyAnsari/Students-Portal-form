<?php
// Database configuration
$host = 'localhost';
$db   = 'mystudents';
$user = 'root';
$pass = '';

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Array of possible values for each column
$names = array(
    'Muhammad','Fatima','Aisha','Ali','Ahmed','Sara','Hassan','Hussein','Abdullah','Zainab','Usman','Ayesha','Hamza','Farah',
    'Amir','Bilal','Asma','Ibrahim','Khadija','Naveed','Saima','Raza','Sadia','Faisal','Sana','Imran','Nadia','Kamran', 'Samina','Zubair','Sobia');

$courses = array();
$stmt = $pdo->prepare("SELECT course_name FROM courses");
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $courses[] = $row['course_name'];
}
$years = array('1st', '2nd', '3rd', '4th');
$addresses = array(
    '100ft road sukkur',
    'universirty road karachi',
    'Gulshan-e-Iqbal, Karachi',
    'Defence Housing Authority (DHA), Karachi',
    'Clifton, Karachi',
    'North Nazimabad, Karachi',
    'Tariq Road, Karachi',
    'Bahadurabad, Karachi',
    'Gulistan-e-Jauhar, Karachi',
    'Saddar, Karachi',
    'Gulshan-e-Maymar, Karachi',
    'Malir, Karachi',
    'PECHS, Karachi',
    'Gulberg, Karachi',
    'Orangi Town, Karachi',
    'Nazimabad, Karachi'
);

$phones = array('1234567890', '9876543210', '4567891230', '7891234560', '3216549870');
$genders = array('Male', 'Female', 'Other');
$nationalities = array('USA', 'UK', 'Canada', 'Australia', 'Germany');
$documentations = array('CNIC', 'B-form');

// Generate and insert random records
for ($i = 0; $i < 1000; $i++) {
    $name = $names[array_rand($names)];
    $email = strtolower(str_replace(' ', '', $name)) . '@example.com';
    $studentID = 'STU' . rand(1000, 9999);
    $course = $courses[array_rand($courses)];
    $year = $years[array_rand($years)];
    $address = $addresses[array_rand($addresses)];
    $phone = $phones[array_rand($phones)];
    $dob = date('Y-m-d', rand(strtotime('1990-01-01'), strtotime('2005-12-31')));
    $gender = $genders[array_rand($genders)];
    $nationality = $nationalities[array_rand($nationalities)];
    $documentation = array();

    // Randomly select "CNIC", "B-form", or both
    $randIndex = rand(0, 2);
    if ($randIndex === 0) {
        $documentation[] = 'CNIC';
    } elseif ($randIndex === 1) {
        $documentation[] = 'B-form';
    } else {
        $documentation[] = 'CNIC';
        $documentation[] = 'B-form';
    }

    $stmt = $pdo->prepare("INSERT INTO students (name, email, StudentID, course, year, address, phone, dob, gender, nationality, documentation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $studentID, $course, $year, $address, $phone, $dob, $gender, $nationality, implode(', ', $documentation)]);
}

echo "Records inserted successfully.";
?>