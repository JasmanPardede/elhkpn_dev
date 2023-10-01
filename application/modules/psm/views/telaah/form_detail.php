<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/**
 * View
 *
 * @author Rizky Awlia Fajrin (Evan Sumangkut) - PT.Waditra Reka Cipta
 * @package Views/user
*/
?>

<div id="wrapperFormAdd" class='form-horizontal'>
    <form method="post" id="ajaxFormAdd" action="index.php/psm/telaah/save_telaah" enctype="multipart/form-data">
        <div class="box-body">
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                   <div class="form-group">
                                    <label>
                                        <u>IDENTITAS PENYELENGGARA NEGARA</u>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <label>Nama</label>
                                        <input type="text" readonly  value="<?php echo $get_lhkpn->NAMA ?>" class="form-control"/>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <label>Instansi</label> 
                                        <input type="text"  readonly  value="<?php echo $get_lhkpn->NAMA_JABATAN ?>" class="form-control"/>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <label>Jabatan</label>
                                        <input type="text"  readonly  value="<?php echo $get_lhkpn->INST_NAMA ?>" class="form-control"/>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <u>IDENTITAS ANDA</u>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <label>Nama</label>
                                        <input type="text" readonly  value="<?php echo $get_pelaporan->NAMA_PELAPOR ?>" class="form-control"/>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <label>Nomor HP/Telp</label> 
                                        <input type="text" readonly  value="<?php echo $get_pelaporan->NOMOR_PELAPOR ?>" class="form-control"/>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <label>Email</label> 
                                        <input type="text" readonly  value="<?php echo $get_pelaporan->EMAIL_PELAPOR ?>" class="form-control"/>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label>
                                        <u>ISI INFORMASI</u>
                                    </label>
                                </div>
                                 <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10"><br>
                                        <?php echo $get_pelaporan->ISI_PENGADUAN ?>

                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                 <div class="form-group">
                                    <label>
                                        <u>LAMPIRAN</u>
                                    </label>
                                </div>
                                 <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                    <label>Lampiran </label>
                                    <br>
                                        <table id="tableListFileUpload" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="60%">File Uploads</th>
                                                    <th width="40%">Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php  
                                                    function fsize($file){
                                                        $a = array("B", "KB", "MB", "GB", "TB", "PB");
                                                        $pos = 0;
                                                        $size = filesize($file);
                                                        while ($size >= 1024)
                                                        {
                                                        $size /= 1024;
                                                        $pos++;
                                                        }
                                                        return round ($size,2)." ".$a[$pos];
                                                    }
                                                     
                                                    $keterangan = $get_dokumen[0]->KETERANGAN;

                                                    echo '<td>';
                                                        foreach($get_link as $link){
                                                            echo $link;
                                                        }
                                                    echo '</td>';
                                                    echo '<td>'.$keterangan.'</td>';
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                 <div class="form-group">
                                    <label>
                                        <u>TIM PEMERIKSA</u>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <label>Status</label> <span class="red-label">*</span></label> <?= FormHelpPopOver('backend_status_psm'); ?>
                                        <br>
                                        <input required type="radio" name="STATUS_PEMERIKSA" <?php if($get_pelaporan->IS_VERIFICATION==1) echo 'checked'; ?> value="1"/> Diteruskan ke Penelaahan <input type="radio"<?php if($get_pelaporan->IS_VERIFICATION==2) echo 'checked'; ?>  name="STATUS_PEMERIKSA" value="2"/> Tidak ditindaklanjuti 
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                 <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <label>Keterangan</label> <span class="red-label">*</span></label> <?= FormHelpPopOver('backend_keterangan_psm'); ?>
                                        <br>
                                        <textarea required name="KETERANGAN_PEMERIKSA" id="KETERANGAN_PEMERIKSA" placeholder="Catatan" rows="10" class="form-control"><?php echo $get_pelaporan->KETERANGAN_PEMERIKSA ?></textarea>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                </div>
                <div class="col-sm-1">
                </div>
            </div>
        </div>
        <br>
        <div class="pull-right">
            <input type="hidden" name="token_pelaporan" value="<?php echo encrypt_username($get_pelaporan->ID_PELAPORAN) ?>">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Simpan</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i> Tutup</button>
        </div>
        
    </form>
</div>
<script type="text/javascript">    
    $(function() {
        $('.over').popover();
           $('.over')
           .mouseenter(function(e){
              $(this).popover('show'); 
           })
           .mouseleave(function(e){
              $(this).popover('hide'); 
           });  
    })
    

    $(document).ready(function() {
        var dtTable = $('#dt_completeNEW').DataTable();
        ng.formProcess($("#ajaxFormAdd"), 'insert','',null,null,null,'Anda tidak memiliki akses pada data ini !!!');
        dtTable.ajax.reload( null, false );
        $('#ajaxFormAdd').submit(function (e) {
            dtTable.ajax.reload( null, false );
        });

   
    });
   
</script>