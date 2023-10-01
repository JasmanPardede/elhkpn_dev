<script type="text/javascript">
    var BAR_CHART2 = function () {


        get_nilai_awal(INDEX_CHART_NOW);

        function get_nilai_awal(ID){
            var result = new Array();
            $.ajax({
                url: '<?php echo base_url(); ?>portal/data_penerimaan/load_data/' + ID,
                async: true,
                dataType: 'JSON',
                success: function (data) {
                    if (data) {
                        var list = eval(data.list);
                        var total = eval(data.sum);
                        result[0] = total.SUM_A || 0;
                        result[1] = total.SUM_B || 0;
                        result[2] = total.SUM_C || 0;
                    }
                    val_1 = result;
                    if(INDEX_CHART_LAST == null){
                        showGraphic(val_1, null);
                    }
                    get_nilai_akhir(INDEX_CHART_LAST,val_1);

                }
            });
        }

        function get_nilai_akhir(ID,val_1){
            var result = new Array();
            $.ajax({
                url: '<?php echo base_url(); ?>portal/data_penerimaan/load_data/' + ID,
                async: true,
                dataType: 'JSON',
                success: function (data) {
                    if (data) {
                        var list = eval(data.list);
                        var total = eval(data.sum);
                        result[0] = total.SUM_A || 0;
                        result[1] = total.SUM_B || 0;
                        result[2] = total.SUM_C || 0;
                    }
                    val_2 = result;
                    showGraphic(val_1,val_2);
                }
            });
        }

        function showGraphic(INDEX_CHART_NOW,INDEX_CHART_LAST){
                var val_1 = INDEX_CHART_NOW !=null ? INDEX_CHART_NOW : [];
                var val_2 = INDEX_CHART_LAST != null ?  INDEX_CHART_LAST : [];
                var areaChartData = {
                    labels: ["PP", "PUK", "PNL"],
                    datasets: [
                        {
                            label: TAHUN_1,
                            fillColor: "rgba(210, 214, 222, 1)",
                            strokeColor: "rgba(210, 214, 222, 1)",
                            pointColor: "rgba(210, 214, 222, 1)",
                            pointStrokeColor: "#c1c7d1",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(220,220,220,1)",
                            data: val_1
                        }
                    ]
                };

                var barChartCanvas = $("#barChart2").get(0).getContext("2d");
                var barChart = new Chart(barChartCanvas);
                var barChartData = areaChartData;
        <?php if ($LAST): ?>
                    areaChartData.datasets.push({
                        label: TAHUN_2,
                        fillColor: "rgba(60,141,188,0.9)",
                        strokeColor: "rgba(60,141,188,0.8)",
                        pointColor: "#3b8bba",
                        pointStrokeColor: "rgba(60,141,188,1)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data: val_2
                    });

                    barChartData = areaChartData;
                    barChartData.datasets[1].fillColor = "#00a65a";
                    barChartData.datasets[1].strokeColor = "#00a65a";
                    barChartData.datasets[1].pointColor = "#00a65a";
        <?php endif; ?>
                //-------------
                //- BAR CHART -
                //-------------
                var legendTemplate = "";
                var barChartOptions = {
                    //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
                    scaleBeginAtZero: true,
                    //Boolean - Whether grid lines are shown across the chart
                    scaleShowGridLines: true,
                    //String - Colour of the grid lines
                    scaleGridLineColor: "rgba(0,0,0,.05)",
                    //Number - Width of the grid lines
                    scaleGridLineWidth: 1,
                    //Boolean - Whether to show horizontal lines (except X axis)
                    scaleShowHorizontalLines: true,
                    //Boolean - Whether to show vertical lines (except Y axis)
                    scaleShowVerticalLines: true,
                    //Boolean - If there is a stroke on each bar
                    barShowStroke: true,
                    //Number - Pixel width of the bar stroke
                    barStrokeWidth: 2,
                    //Number - Spacing between each of the X value sets
                    barValueSpacing: 5,
                    //Number - Spacing between data sets within X values
                    barDatasetSpacing: 1,
                    //String - A legend template
                    legendTemplate: legendTemplate,
                    //Boolean - whether to make the chart responsive
                    responsive: true,
                    maintainAspectRatio: true,
                    scaleLabel: function (label) {
                        return  '' + label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    },
                    tooltipTemplate: function (label) {
                        var text = label.label;
                        var name;
                        if (text == 'PP') {
                            name = "Penerimaan Dari Pekerjaan";
                        } else if (text == 'PUK') {
                            name = "Penerima Dari Usaha dan Kekayaan";
                        } else {
                            name = "Penerimaan Lainnya";
                        }
                        var value = name + ' Rp.' + label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return value;
                    },
                    multiTooltipTemplate: function (label) {
                        return label.datasetLabel + " Rp. " + label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                };
                barChartOptions.datasetFill = false;
                barChart.Bar(barChartData, barChartOptions);
        }
    }

</script>