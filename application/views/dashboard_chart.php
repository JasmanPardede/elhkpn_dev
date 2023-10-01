<link class="iniicon" href="<?php echo base_url('img/favicon.ico'); ?>" rel="shortcut icon" type="image/x-icon">
<!-- Content Header (Page header) -->

   

<section class="content-header">
    <h1>
        
    </h1>
    <ol class="breadcrumb">
        <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
    </ol>
</section>
<?php
$btn = ' btn-default ';
if (@$this->session->userdata('IS_KPK') == '1' || @$this->session->userdata('IS_INSTANSI') == '1') {
    $btn = ' btn-primary ';
}
?>

<!-- Main content -->
<section class="content">
    <h1></h1>
    <div class="row">
        <div class="col-md-6">
            <h3>Statistik Laporan Yang Masuk</h3>
            <script src="<?php echo base_url('plugins/chartjs/Chart.min.js'); ?>"></script>
            <div class="box">
                <div class="box-body">
                  <div class="chart">
                  <canvas height="50px" width="100%" id="chartKomposisi"></canvas>
                  </div>
                </div><!-- /.box -->

            </div><!-- /.col (RIGHT) -->
          </div><!-- /.row -->

        <div class="col-md-6">
          <h3>Statistik User</h3>          
                    <div class="col-md-6">
                      <div class="small-box bg-green">
                        <div class="inner">
                          <p>JUMLAH USER</p>
                          <h3>53<sup style="font-size: 20px"></sup></h3>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="small-box bg-green">
                        <div class="inner">
                          <p>USER ONLINE</p>
                          <h3>5<sup style="font-size: 20px"></sup></h3>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                      </div>
                    </div>

                    <div class="col-md-12">
                        <h3>Statistik User Online / Bulan</h3>
                        <div class="box">
                        <div class="box-body">
                          <div class="chart">
                          <canvas height="160px" width="100%" id="areaChart"></canvas>
                          </div>
                        </div>
                        </div>
                    </div>
                  
          </div>

          <div class="col-md-12">
              <br/><br/><br/>
          </div>


                    
                </div>
  
    
</section><!-- /.content -->


<script>
    $(document).ready(function () {

        var areaChartData = {
            labels: ["Januari","Febuari","Maret","April","Mei","Juni"],
            datasets: [
                {
//                    label: "Digital Goods",
                    fillColor: "rgba(60,141,188,0.9)",
                    strokeColor: "rgba(60,141,188,0.8)",
                    pointColor: "#3b8bba",
                    pointStrokeColor: "rgba(60,141,188,1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: ['40','20','10']
                }
            ]
        };

        var barChartCanvas = $("#chartKomposisi").get(0).getContext("2d");
        var barChart = new Chart(barChartCanvas);
        var barChartData = areaChartData;
        var barChartOptions = {
            scaleBeginAtZero: true,
            scaleShowGridLines: true,
            scaleGridLineColor: "rgba(0,0,0,.05)",
            scaleGridLineWidth: 1,
            scaleShowHorizontalLines: true,
            scaleShowVerticalLines: true,
            barShowStroke: true,
            barStrokeWidth: 2,
            barValueSpacing: 5,
            barDatasetSpacing: 1,
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
            responsive: true,
            maintainAspectRatio: true,
            tooltipTemplate: "<%=label %> : <%= value + '' %>",
        };

        barChartOptions.datasetFill = false;
        barChart.Bar(barChartData, barChartOptions);

        var areaChartData2 = {
            labels: ["Januari","Febuari","Maret","April","Mei","Juni"],
            datasets: [
                {
//                    label: "Digital Goods",
                    fillColor: "rgba(60,141,188,0.9)",
                    strokeColor: "rgba(60,141,188,0.8)",
                    pointColor: "#3b8bba",
                    pointStrokeColor: "rgba(60,141,188,1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: ['40','35','45']
                }
            ]
        };
       
        var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var areaChart = new Chart(areaChartCanvas);
        var areaChartOptions = {
          //Boolean - If we should show the scale at all
          showScale: true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines: false,
          //String - Colour of the grid lines
          scaleGridLineColor: "rgba(0,0,0,.05)",
          //Number - Width of the grid lines
          scaleGridLineWidth: 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines: true,
          //Boolean - Whether the line is curved between points
          bezierCurve: true,
          //Number - Tension of the bezier curve between points
          bezierCurveTension: 0.3,
          //Boolean - Whether to show a dot for each point
          pointDot: false,
          //Number - Radius of each point dot in pixels
          pointDotRadius: 4,
          //Number - Pixel width of point dot stroke
          pointDotStrokeWidth: 1,
          //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
          pointHitDetectionRadius: 20,
          //Boolean - Whether to show a stroke for datasets
          datasetStroke: true,
          //Number - Pixel width of dataset stroke
          datasetStrokeWidth: 2,
          //Boolean - Whether to fill the dataset with a color
          datasetFill: true,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio: false,
          //Boolean - whether to make the chart responsive to window resizing
          responsive: true
        };
        areaChart.Line(areaChartData2, areaChartOptions);

    })


</script>