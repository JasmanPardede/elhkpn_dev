<!-- BAR CHART Komposisi User - PN/WL -->
<div class="box box-danger" id="box-kepatuhan">
    <div class="box-header with-border">
        <h3 class="box-title">Kepatuhan LHKPN</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="<?php echo base_url('index.php/ereport/exum/chart/2') ?>">
            <div class="form-group">
                <label class="col-md-offset-2 col-md-3 control-label"><strong>Tahun Ke : </strong></label>
                <div class="col-md-3">
                    <select class="form-control input-sm" name="tahun_start">
                        <?php for($i = date('Y'); $i >= 2000; $i--){ ?>
                            <option <?php echo $i == $tahunstart ? 'selected="selected"' : '' ?> value="<?php echo $i?>"><?php echo $i?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-1">
                    s/d
                </div>
                <div class="col-md-3">
                    <select class="form-control input-sm" name="tahun_end">
                        <?php for($i = date('Y'); $i >= 2000; $i--){ ?>
                            <option <?php echo $i == $tahunend ? 'selected="selected"' : '' ?> value="<?php echo $i?>"><?php echo $i?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-offset-2 col-md-3 control-label"><strong>Instansi : </strong></label>
                <div class="col-md-7">
                    <select class="form-control input-sm" name="instansi">
                        <option value="">All</option>
                        <?php foreach($inst as $row): ?>
                            <option value="<?php echo $row->id;?>"><?php echo $row->name;?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <label class="col-md-offset-8 col-md-4 text-right"><button type="button" class="btn-submit btn btn-primary btn-sm">Tampilkan</button> </label>
            </div>
        </form>
        <div class="chart">
            <canvas height="300" width="475" id="chartKepatuhan"></canvas>
        </div>
        <div class="row">
            <div class="col-md-offset-4 col-md-6">
                <div style="margin-right; 10px; float: left; width: 20px; height: 20px;background-color: rgba(220,220,220,0.9);"></div>
                <div style="float: left;"><strong>Jumlah PN/WL</strong></div>
            </div>
        </div>
        <div class="row" style="margin-top: 3px;">
            <div class="col-md-offset-4 col-md-6">
                <div style="margin-right; 10px; float: left; width: 20px; height: 20px;background-color: rgba(60,141,188,0.9);"></div>
                <div style="float: left;"><strong>Jumlah LHKPN</strong></div>
            </div>
        </div>
    </div><!-- /.box-body -->
</div><!-- /.box -->

<script>
    $(document).ready(function () {
        $('#box-kepatuhan .btn-submit').click(function () {
            var act = $(this).closest('form').attr('action');
            var data = $(this).closest('form').serializeArray();
            var con = $(this).closest('div.con');
            $.post(act, data, function(html){
                con.html(html);
            });
        });

        var areaChartData = {
            labels: ["<?php echo implode('","', $tahun) ?>"],
            datasets: [
                {
                    label: "Jumlah PN/WL",
                    fillColor: "rgba(220,220,220,0.9)",
                    strokeColor: "rgba(220,220,220,0.8)",
                    pointColor: "#3b8bba",
                    pointStrokeColor: "rgba(60,141,188,1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: [<?php echo implode(',', $pn); ?>]
                },
                {
                    label: "Jumlah LHKPN",
                    fillColor: "rgba(60,141,188,0.9)",
                    strokeColor: "rgba(60,141,188,0.8)",
                    pointColor: "#3b8bba",
                    pointStrokeColor: "rgba(60,141,188,1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: [<?php echo implode(',', $lhkpn); ?>]
                }
            ]
        };

        var barChartCanvas = $("#chartKepatuhan").get(0).getContext("2d");
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
        };

        barChartOptions.datasetFill = false;
        barChart.Bar(barChartData, barChartOptions);
    })
</script>