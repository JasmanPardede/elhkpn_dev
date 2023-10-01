<script type="text/javascript">
    var GET_VALUE_CHART1 = function (ID) {
        var result = new Array();
        $.ajax({
            url: '<?php echo base_url(); ?>portal/review_harta/jumlah/' + ID,
            async: false,
            dataType: 'JSON',
            success: function (data) {
                if (data) {
                    for (i = 0; i < data.length; i++) {
                        result[i] = data[i];
                    }
                }
            }
        });
        return result;
    }, GET_VALUE_CHART2 = function (ID) {
        var result = new Array();
        $.ajax({
            url: '<?php echo base_url(); ?>portal/data_penerimaan/load_data/' + ID,
            async: false,
            dataType: 'JSON',
            success: function (data) {
                if (data) {
                    var list = eval(data.list);
                    var total = eval(data.sum);
                    result[0] = total.SUM_A || 0;
                    result[1] = total.SUM_B || 0;
                    result[2] = total.SUM_C || 0;
                }
            }
        });
        return result;
    }, GET_VALUE_CHART3 = function (ID) {
        var result = new Array();
        $.ajax({
            url: '<?php echo base_url(); ?>portal/data_pengeluaran/load_data/' + ID,
            async: false,
            dataType: 'JSON',
            success: function (data) {
                if (data) {
                    var list = eval(data.list);
                    var total = eval(data.sum);
                    result[0] = total.SUM_A || 0;
                    result[1] = total.SUM_B || 0;
                    result[2] = total.SUM_C || 0;
                }
            }
        });
        return result;
    };
</script>