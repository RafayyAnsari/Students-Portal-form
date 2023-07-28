<html>
<head>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
      // Function to fetch data and draw charts
      function fetchDataAndDrawCharts() {
        $.ajax({
          url: "fetch_data.php",
          method: "GET",
          dataType: "json",
          success: function(data) {
            drawPieChart(data.pieData);
            drawBarChart(data.barData);
          }
        });
      }

      // Function to draw the pie chart
      function drawPieChart(data) {
        var chartData = new google.visualization.DataTable();
        chartData.addColumn('string', 'Course');
        chartData.addColumn('number', 'Male Count');
        chartData.addColumn('number', 'Female Count');
        chartData.addRows(data);

        var options = {
          title: 'Student Enrollment Ratio in Each Course',
          pieHole: 0.4,
          isStacked: true
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(chartData, options);
      }

      // Function to draw the bar chart
      function drawBarChart(data) {
        var chartData = new google.visualization.DataTable();
        chartData.addColumn('string', 'Course');
        chartData.addColumn('number', 'Male Count');
        chartData.addColumn('number', 'Female Count');
        chartData.addRows(data);

        var options = {
          title: 'Male/Female Enrollment in Each Course',
          vAxis: {title: 'Enrollment'},
          hAxis: {title: 'Course'},
          bars: 'vertical',
          colors: ['#3366cc', '#ff9900']
        };

        var chart = new google.visualization.BarChart(document.getElementById('barchart'));
        chart.draw(chartData, options);
      }

      // Initial data fetch and chart drawing
      fetchDataAndDrawCharts();

      // Fetch data and update charts every 5 seconds
      setInterval(fetchDataAndDrawCharts, 5000);
    }
  </script>
</head>
<body>
  <div id="piechart" style="width: 900px; height: 500px;"></div>
  <div id="barchart" style="width: 900px; height: 500px;"></div>
</body>
</html>
