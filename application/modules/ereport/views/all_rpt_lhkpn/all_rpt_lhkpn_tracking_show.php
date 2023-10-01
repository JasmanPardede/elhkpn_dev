<link href="<?php echo base_url();?>css/custom.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url();?>plugins/barcode/jquery-barcode.js"></script>
<div class="">
    <div class="bkd-cntn">
        <div id="barcodeTarget" class="barcodeTarget"></div>
        <canvas id="canvasTarget" width="150" height="150"></canvas>
    </div>
    <table width="85%" class="tbl-bd" align="center" style="margin-top: 0; margin-left: 0px;">
        <tr>
            <td width="5%"><strong>Bidang</strong></td>
            <td width="1%" align="center">:</td>
            <td width="30%"><?= (!empty($items[0]->BDG_NAMA)) ? @$items[0]->BDG_NAMA : '-'; ?></td>
        </tr>
        <tr>
            <td><strong>Nama</strong></td>
            <td align="center">:</td>
            <td><?= strtoupper(@$items[0]->NAMA); ?></td>
        </tr><tr>
            <td><strong>Tempat, Tanggal Lahir</strong></td>
            <td align="center">:</td>
            <td><?= strtoupper(@$items[0]->TEMPAT_LAHIR).', '.strtoupper(tgl_format(@$items[0]->TGL_LAHIR));?></td>
        </tr><tr>
            <td><strong>Jabatan</strong></td>
            <td align="center">:</td>
            <td><?= (!empty($items[0]->NAMA_JABATAN)) ? @$items[0]->NAMA_JABATAN : '-';?></td>
        </tr><tr>
            <td><strong>Lembaga</strong></td>
            <td align="center">:</td>
            <td><?= (!empty($items[0]->INST_NAMA)) ? @$items[0]->INST_NAMA : '-';?></td>
        </tr><tr>
            <td><strong>Unit Kerja</strong></td>
            <td align="center">:</td>
            <td><?= (!empty($items[0]->UK_NAMA)) ? @$items[0]->UK_NAMA : '-';?></td>
        </tr><tr>
            <td><strong>Tanggal Lapor</strong></td>
            <td align="center">:</td>
            <td><?= (!empty($items[0]->TGL_LAPOR)) ? strtoupper(tgl_format(@$items[0]->TGL_LAPOR)) : strtoupper(tgl_format($items[0]->TANGGAL_PELAPORAN));?></td>
        </tr><tr>
            <td><strong>Tanggal Penyampaian</strong></td>
            <td align="center">:</td>
            <td><?= (!empty($items[0]->TANGGAL_PENERIMAAN)) ? strtoupper(tgl_format(@$items[0]->TANGGAL_PENERIMAAN)) : '-';?></td>
        </tr><tr>
            <td><strong>No. Agenda</strong></td>
            <td align="center">:</td>
            <td><?php echo @$id; ?></td>
        </tr>
    </table>
    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom" style="margin-bottom: 40px;">
        <thead>
        <tr>
            <th width="5%">No.</th>
            <th width="18%">Pengirim</th>
            <th width="18%">Penerima</th>
            <th width="18%">Date Insert</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $arr = array();
        if($getHistory == $arr){
            ?>
            <tr>
                <td colspan="5"><center>Data Not Found...</center></td>
            </tr>
            <?php
        }else{
            $i = 1;
            foreach ($getHistory as $hist) {
                ?>
                <tr>
                    <td><?= @$i++; ?></td>
                    <td><?= @$hist->PENGIRIM; ?></td>
                    <td><?= @$hist->PENERIMA; ?></td>
                    <td><?= @date('d/m/Y h:m:s',strtotime(@$hist->DATE_INSERT)); ?></td>
                    <td><?= @$hist->STATUS; ?></td>
                </tr>
            <?php }} ?>
        </tbody>
    </table>
</div>


<script language="javascript">
    $(document).ready(function() {
        $("#ajaxFormCari").submit(function(e) {
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this));
            return false;
        });

        $('.btn-print').click(function(e) {
            url = $(this).attr('href');
            html = '<iframe src="'+url+'" width="100%" height="500px"></iframe>';
            OpenModalBox('Print Tracking LHKPN', html, '', 'large');
            return false;
        });
    });

    function generateBarcode(){
        var value    = '<?php echo @$id; ?>';
        var btype    = 'code93';
        var renderer = $("input[name=renderer]:checked").val();

        var quietZone = false;
        if ($("#quietzone").is(':checked') || $("#quietzone").attr('checked')){
            quietZone = true;
        }

        var settings = {
            output:renderer,
            bgColor: $("#bgColor").val(),
            color: $("#color").val(),
            barWidth: $("#barWidth").val(),
            barHeight: $("#barHeight").val(),
            moduleSize: $("#moduleSize").val(),
            posX: $("#posX").val(),
            posY: $("#posY").val(),
            addQuietZone: $("#quietZoneSize").val()
        };

        if ($("#rectangular").is(':checked') || $("#rectangular").attr('checked')){
            value = {code:value, rect: true};
        }

        if (renderer == 'canvas'){
            clearCanvas();
            $("#barcodeTarget").hide();
            $("#canvasTarget").show().barcode(value, btype, settings);
        } else {
            $("#canvasTarget").hide();
            $("#barcodeTarget").html("").show().barcode(value, btype, settings);
        }
    }

    $(function(){
        generateBarcode();
    });
</script>

<style>
    td .btn {
        margin: 0px;
    }
</style>