<? function dC($st,$u){ ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
   google.charts.load('current', {'packages':['line', 'corechart']});
   google.charts.setOnLoadCallback(drawChart);

   function drawChart() {

   var button = document.getElementById('change-chart');
   var chartDiv = document.getElementById('chart_div');

   var data = new google.visualization.DataTable();
   data.addColumn('string', 'Month');
   data.addColumn('number', "Cost");
   data.addColumn('number', "Consumption");

   data.addRows(<? echo "$st";?>);

   var materialOptions = {
     chart: {
       title: 'Consumption/Cost Graph'
     },
     curveType: 'function',
     width: 850,
     height: 500,
     series: {
       // Gives each series an axis name that matches the Y-axis below.
       0: {axis: 'Cost'},
       1: {axis: 'Consumption'}
     },
     axes: {
       // Adds labels to each axis; they don't have to match the axis names.
       y: {
         Consumption: {label: 'Consumption(<? echo $u; ?>)'},
         Cost: {label: 'Cost(INR)'}
       }
     }
   };


     var materialChart = new google.charts.Line(chartDiv);
     materialChart.draw(data, materialOptions);

   }
</script>
<div id="chart_div"></div>
<? }
   ?>
