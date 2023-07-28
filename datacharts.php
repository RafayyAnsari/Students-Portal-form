<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mystudents";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data for pie chart
$query_pie = "SELECT course, SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) AS maleCount, SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) AS femaleCount FROM students GROUP BY course";
$result_pie = $conn->query($query_pie);

$data_pie = array();
while ($row_pie = $result_pie->fetch_assoc()) {
    $course_pie = $row_pie['course'];
    $maleCount_pie = (int)$row_pie['maleCount'];
    $femaleCount_pie = (int)$row_pie['femaleCount'];

    $data_pie[] = array($course_pie, $maleCount_pie, $femaleCount_pie);
}

// Fetch data for bar chart
$query_bar = "SELECT course, SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) AS maleCount, SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) AS femaleCount FROM students GROUP BY course";
$result_bar = $conn->query($query_bar);

$data_bar = array();
while ($row_bar = $result_bar->fetch_assoc()) {
    $course_bar = $row_bar['course'];
    $maleCount_bar = (int)$row_bar['maleCount'];
    $femaleCount_bar = (int)$row_bar['femaleCount'];

    $data_bar[] = array($course_bar, $maleCount_bar, $femaleCount_bar);
}

// Convert data to JSON format
$json_data_pie = json_encode($data_pie);
$json_data_bar = json_encode($data_bar);
?>

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawPieChart);
      google.charts.setOnLoadCallback(drawBarChart);

      function drawPieChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Course');
        data.addColumn('number', 'Male Count');
        data.addColumn('number', 'Female Count');
        data.addRows(<?php echo $json_data_pie; ?>);

        var options = {
          title: 'Student Enrollment Ratio in Each Course',
          pieHole: 0.4,
          isStacked: true
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }

      function drawBarChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Course');
        data.addColumn('number', 'Male Count');
        data.addColumn('number', 'Female Count');
        data.addRows(<?php echo $json_data_bar; ?>);

        var options = {
          title: 'Male/Female Enrollment in Each Course',
          vAxis: {title: 'Enrollment'},
          hAxis: {title: 'Course'},
          bars: 'vertical',
          colors: ['#3366cc', '#ff9900']
        };

        var chart = new google.visualization.BarChart(document.getElementById('barchart'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
    <div id="barchart" style="width: 900px; height: 500px;"></div>
  </body>
</html>

<?php
// Close the database connection
$conn->close();
?>