<!-- BAR CHART Komposisi User - PN/WL -->
<div class="box box-danger" id="box-komposisi">
    <div class="box-header with-border">
        <h3 class="box-title">Komposisi USER - PN/WL</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="<?php echo base_url('index.php/ereport/exum/chart/1') ?>">
            <div class="form-group">
                <label class="col-md-offset-2 col-md-6 control-label"><strong>Data Ke : </strong></label>
                <div class="col-md-4">
                    <select class="form-control input-sm" name="limit">
                        <?php for($i = 10; $i < 100; $i+=10){ ?>
                            <option <?php echo $i == $limit ? 'selected="selected"' : '' ?> value="<?php echo ($i-10)?>"><?php echo $i?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-offset-2 col-md-6 control-label"><strong>Urut Rasio User - PN/WL : </strong></label>
                <div class="col-md-4">
                    <select class="form-control input-sm" name="rasio">
                        <option <?php echo $rasio == '1' ? 'selected="selected"' : '' ?> value="1">Besar - Kecil</option>
                        <option <?php echo $rasio == '2' ? 'selected="selected"' : '' ?> value="2">Kecil - Besar</option>
                    </select>
                </div>
            </div>
<!--            <div class="form-group">-->
<!--                <label class="col-md-offset-2 col-md-6 control-label"><strong>Tahun : </strong></label>-->
<!--                <div class="col-md-4">-->
<!--                    <select class="form-control input-sm" name="tahun">-->
<!--                        --><?php //for($i = 2015; $i >= 2000; $i--){ ?>
<!--                            <option value="--><?php //echo $i?><!--">--><?php //echo $i?><!--</option>-->
<!--                        --><?php //} ?>
<!--                    </select>-->
<!--                </div>-->
<!--            </div>-->
            <div class="row">
                <label class="col-md-offset-8 col-md-4 text-right"><button type="button" class="btn-submit btn btn-primary btn-sm">Tampilkan</button> </label>
            </div>
        </form>
        <div class="chart">
            <canvas height="430" width="475" id="chartKomposisi"></canvas>
        </div>
    </div><!-- /.box-body -->
</div><!-- /.box -->

<script>
    $(document).ready(function () {
        $('#box-komposisi .btn-submit').click(function () {
            var act = $(this).closest('form').attr('action');
            var data = $(this).closest('form').serializeArray();
            var con = $(this).closest('div.con');
            $.post(act, data, function(html){
                con.html(html);
            });
        });

        var areaChartData = {
            labels: ["<?php echo implode('","', $instansi) ?>"],
            datasets: [
                {
//                    label: "Digital Goods",
                    fillColor: "rgba(60,141,188,0.9)",
                    strokeColor: "rgba(60,141,188,0.8)",
                    pointColor: "#3b8bba",
                    pointStrokeColor: "rgba(60,141,188,1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: [<?php echo implode(',', $jml); ?>]
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
            tooltipTemplate: "<%=label %> : <%= value + ' %' %>",
        };

        barChartOptions.datasetFill = false;
        barChart.Bar(barChartData, barChartOptions);
    })
</script>