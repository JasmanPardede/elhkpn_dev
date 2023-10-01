<?php if($mode == 'viewBap'){ ?>
    <div id="wrapperBAP">
        <!--<button onclick="showForm('1','<?= substr(md5($ID_BAP),5,8); ?>')" type="button" class="btn btn-sm btn-addJab btn-primary"><i class="fa fa-plus"></i> Tambah</button><br><br>-->
        <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th>No. Agenda</th>
                    <th>PN</th>
                    <th>Tgl Lapor</th>
                    <th>Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if(empty($item)){
                ?>
                    <tr>
                        <td colspan="6" align="center"><i> Data tidak ditemukan </i></td>
                    </tr>
                <?php
                    }else{
                        $no = 1;
                        foreach ($item as $items): 
                ?>
                            <tr>
                                <td><?= @$no++; ?>. </td>
                                <td class="text-center"><?= date('Y', strtotime($items->tgl_lapor)).'/'.($items->JENIS_LAPORAN == '4' ? 'R' : ($items->JENIS_LAPORAN == '5' ? 'P' : 'K')).'/'.$items->NIK.'/'.$items->ID_LHKPN;; ?></td>                            
                                <td class="text-center"><?= @$items->NAMA_LENGKAP; ?></td>
                                <td class="text-center"><?= @$items->tgl_kirim_final; ?></td>
                                <td class="text-center"><?= '<ul><li>'.@$items->NAMA_JABATAN.' - '.@$items->UK_NAMA.' - '.@$items->INST_NAMA.'</li></ul>'; ?></td>
<!--                                <td class="text-center">
                                    <?php
                                        if ($items->NAMA_JABATAN) {
                                            $j = explode(',', $items->NAMA_JABATAN);
                                            $c_j = count($j );
                                            echo '<ul>';
                                            foreach ($j as $ja) {
                                                $jb = explode(':58:', $ja);
                                                $idjb = $jb[0];
                                                $statakhirjb = @$jb[1];
                                                $statakhirjbtext = @$jb[2];
                                                $statmutasijb = @$jb[3];
                                                if (@$jb[4] != '') {
                                                    if($c_j > 1){
                                                        if(@$jb[5] == 1 )
                                                    echo '<li>' . @$jb[4] . '</li>';     
                                        }else{
echo '<li>' . @$jb[4] . '</li>';
                                        }
                                                   

                                                }
                                            }
                                            echo '</ul>';
                                        }
                                    ?>
                                </td>-->
                                <td width="120" class="text-center">
                                    <?php if(@$items->STATUS_CETAK_PENGUMUMAN_PDF != '1') { ?>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete" onclick="showForm('2','<?= substr(md5($items->ID),5,8); ?>')" title="Delete"><i class="fa fa-trash" ></i></button>
                                    <?php } ?>
                                </td>
                            </tr>
                <?php 
                            endforeach;
                    }
                ?>
            </tbody>
        </table>
    </div>

    <div class="BAP" data-id="10" style="display: none;">
        <div id="ctnDltBap"></div>
    </div>
<?php }else if($mode == 'addBap'){ ?>
    <form class="form-horizontal" method="post" id="formAddBap" action="javascript:;" enctype="multipart/form-data">
        <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th><input type="checkbox" onClick="chk_all(this);" title="Check ALL"></th>
                    <th>No. Agenda</th>
                    <th>Tgl Lapor</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if(empty($LHKPN)){
                ?>
                    <tr>
                        <td colspan="6" align="center"><i> Data tidak ditemukan !!! </i></td>
                    </tr>
                <?php
                    }else{
                        $no = 1;
                        foreach ($LHKPN as $items): 
                ?>
                            <tr>
                                <td><?= @$no++; ?>. </td>
                                <td class="text-center"><input name="cekIDLhkpn[]" class="chk" type="checkbox" value="<?= @$items->IDLHKPN; ?>" onclick="chk(this);" /></td>
                                <td class="text-center"><?= date('Y', strtotime($items->TGL_LAPOR)).'/'.($items->JENIS_LAPORAN == '4' ? 'R' : ($items->JENIS_LAPORAN == '5' ? 'P' : 'K')).'/'.$items->NIK.'/'.$items->IDLHKPN; ?></td>                            
                                <td class="text-center"><?= @$items->TGL_LAPOR; ?></td>
                            </tr>
                <?php 
                            endforeach;
                    }
                ?>
            </tbody>
        </table>
        <div class="pull-right">
            <input type="hidden" class="" name="ID_BAP" id="" style="" value="<?= @$ID_BAP; ?>" placeholder="">
            <?= (empty($LHKPN) ? '' : '<input type="submit" class="btn btn-sm btn-success" value="Simpan">' ); ?>
            <input type="button" class="btn btn-sm btn-danger aa" value="Kembali" onclick="f_close(this);">
            <input type="hidden" class="" name="act" id="" style="" value="doinsert">
        </div>
    </form>

    <script type="text/javascript">
        $(document).ready(function(){
            $("form#formAddBap").submit(function(event) {
                var urll     = 'index.php/eano/announ/saveBap';
                var formData = new FormData($(this)[0]);
                var tex      = '<?php echo $IDBAP ?>';

                $.ajax({
                    url: urll,
                    type: 'POST',
                    data: formData,
                    async: false,
                    success: function (html) {
                        msg = {
                           success : 'Data Berhasil Disimpan!',
                           error : 'Data Gagal Disimpan!'
                        };
                        if (html == 0) {
                           alertify.error(msg.error);
                        } else {
                           alertify.success(msg.success);
                        }
                        if(html == 1){
                            f_close();
                                
                            var uuu = "index.php/eano/announ/bap_detail/"+tex;
                            $.post(uuu,function(data){
                                $('#wrapperBAP').html(data);
                            });

                        }else{
                            console.log('error');
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });

                return false;
            });
        });

        var idChk = [];

        function chk_all(ele){
            if($(ele).is(':checked')){
                $('.chk').prop('checked', true);
            }else{
                $('.chk').prop('checked', false);
            }

            $('.chk:visible').each(function(){
                chk(this);
            });
        }

        function chk(ele){
            var val = $(ele).val();
            idChk.push(val);
        }
    </script>
<?php }else{ ?>
    <form class="form-horizontal" method="post" id="formdltBap" action="javascript:;" enctype="multipart/form-data">
        <div class="box-body form-horizontal">
            <div class="col-sm-12">
                <input type="hidden" class="" name="ID" style="" value="<?= @$item->ID; ?>" placeholder="">

                <div class="form-group">
                    <label class="col-sm-4 control-label">No Agenda :</label>
                    <div class="col-sm-5">
                        <?= @$agenda; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">No BAP :</label>
                    <div class="col-sm-5">
                        <?= @$item->NOMOR_BAP; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Tanggal Lapor :</label>
                    <div class="col-sm-5">
                        <?= @$item->tgl_kirim_final; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Tanggal Bast :</label>
                    <div class="col-sm-5">
                        <?= @$item->TGL_BA_PENGUMUMAN; ?>
                    </div>
                </div>

            </div>
        </div><!-- /.box-body -->
        <div class="pull-right">
            <input type="hidden" name="ID" id="ID" style="" value="<?php echo substr(md5($item->ID),5,8);?>" placeholder="">
            <input type="hidden" name="ID_BAP" id="ID_BAP" style="" value="<?php echo substr(md5($item->ID_BAP),5,8);?>" placeholder="">
            <button type="submit" class="btn btn-sm btn-primary">Hapus</button>
            <input type="button" class="btn btn-sm btn-danger aa" value="Kembali" onclick="f_close(this);">
            <input type="hidden" class="" name="act" id="" style="" value="dodelete">
        </div>
    </form>
    <script type="text/javascript">
        $(document).ready(function(){
            $("form#formdltBap").submit(function(event) {
                var urll     = 'index.php/eano/announ/saveBap';
                var formData = new FormData($(this)[0]);
                var tex      = $('#ID_BAP').val();

                $.ajax({
                    url: urll,
                    type: 'POST',
                    data: formData,
                    async: false,
                    success: function (html) {
                        msg = {
                           success : 'Data Berhasil Dihapus!',
                           error : 'Data Gagal Dihapus!'
                        };
                        if (html == 0) {
                           alertify.error(msg.error);
                        } else {
                           alertify.success(msg.success);
                        }
                        if(html == 1){
                            f_close();
                                
                            var uuu = "index.php/eano/announ/bap_detail/"+tex;
                            $.post(uuu,function(data){
                                $('#wrapperBAP').html(data);
                            });

                        }else{
                            console.log('error');
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });

                return false;
            });
        })
    </script>
<?php } ?>

<script type="text/javascript">
    function showForm(tipe, param){
        $('#wrapperBAP').slideUp('slow', function(){
            $('div[data-id="10"]').slideDown('slow');
            var target = "index.php/eano/announ/showFormBap/"+tipe+"/"+param;
            $('div[data-id="10"]').load(target);
        });
    }

    function f_close()
    {
        $('.BAP').slideUp('slow', function () {
            $('#wrapperBAP').slideDown('slow');
        })
    }
</script>