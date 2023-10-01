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
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Views/ever/verification
 */
?>
<div class="box-header with-border portlet-header title-alat">
    <h5 class="">"Informasi Jabatan"</h5>
</div>

<div class="col-xs-6 col-sm-6 col-md-6 col-lg-12">
<?php if ($item->entry_via == '1'): ?>
			<br>
            <button class="btn btn-info" id="verifEditDataJabatan" href="index.php/ever/verification_edit/jabatan/<?php echo $item->ID_LHKPN;?>/new"><span class="fa fa-plus"></span> Rangkap Jabatan</button>
            <br>
            <br>
    <?php endif ?>

    <div class="panel panel-default" id="wrapperJabatan">
        <div class="panel-heading">
            <h3 class="panel-title">Jabatan yang Dilaporkan</h3>
        </div>
        <div class="table-responsive">
            <?php
            $i = 0;
            $jumJab = count($JABATANS);
            ?>
            <table class="table table-bordered table-hover table-striped" >
                <thead class="table-header">
                    <tr>
                        <!--<th>primary</th>-->
                        <th width="30">No.</th>
                        <th>Jabatan </th>
                        <th>Lembaga</th>
                        <th>Unit Kerja</th>
                        <th>Sub Unit Kerja</th>
						<th class='aksi'>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($JABATANS as $jabatan) {
                        if ($jabatan->IS_PRIMARY == '1')
                            $key = '<i class="fa fa-key"></i>';
                        else
                            $key = '';
                        ?>
                        <tr>
                            <td><?php echo ++$i; ?>.</td>
                            <td><?php echo $key . ' ' . $jabatan->NAMA_JABATAN; ?>
                                <?php /*
                                $NAMA_JABATAN = $jabatan->NAMA_JABATAN;                  
                                switch ($jabatan->ESELON) {
                                    case '1':
                                        echo 'I';
                                        break;
                                    case '2':
                                        echo 'II';
                                        break;
                                    case '3':
                                        echo 'III';
                                        break;
                                    case '4':
                                        echo 'IV';
                                        break;
                                    case '5':
                                        echo 'Non Eselon';
                                        break;
                                } 
                                */ ?>
                            </td>
                            <td><?php echo $jabatan->INST_NAMA; ?></td>
                            <td><?php echo $jabatan->UK_NAMA; ?></td>
                            <td><?php echo $jabatan->SUK_NAMA; ?></td>
                            <td align="center" class='aksi'>
                            	<?php if($jabatan->IS_PRIMARY==1){?>
                            	<button type="button" class="btn btn-primary" href="index.php/ever/verification_edit/jabatan/<?php echo $jabatan->ID;?>/edit" title="Edit Data" onclick="onButton.go(this, 'standart', true);"><i class="fa fa-pencil"></i></button>
                            	<?php }else{ ?>
                            	 <button type="button" class="btn btn-primary" href="index.php/ever/verification_edit/jabatan/<?php echo $jabatan->ID;?>/edit" title="Edit Data" onclick="onButton.go(this, 'standart', true);"><i class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-success" href="index.php/ever/verification_edit/changePrimaryKey/<?php echo $jabatan->ID; ?>/jabatan/<?php echo $jabatan->ID_LHKPN; ?>" title="Set Sebagai Jabatan Utama" onclick="onButton.changePrimaryKey(this, 'standart', '<?php echo $NAMA_JABATAN ?>');"><i class="fa fa-key"></i></button>
                                <button type="button" class="btn btn-danger" href="index.php/ever/verification_edit/delete/<?php echo $jabatan->ID; ?>/jabatan" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>
                            	<?php } ?>
                            </td>
                        </tr>
                       
                       	
                        <?php
                    }
                    ?>
                </tbody>
            </table>



            
        </div>
    </div>
</div>
<!-- //sampai edit data -->

<script type="text/javascript">
    $(document).ready(function() {

        $("#verifEditDataJabatan").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Form Data Jabatan', html, null, 'standart');
            });            
            return false;
        });

        $("#wrapperJabatan .btnCek").click(function() {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Cek Data Jabatan', html, '', 'large');
            });
            return false;
        });
        $('#wrapperJabatan .btn-edit').click(function(e) {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Edit Jabatan', html, '', 'large');
            });
            return false;
        });
        $('#wrapperJabatan .btn-delete').click(function(e) {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Delete Jabatan', html, '', 'large');
            });
            return false;
        });
        $('.ckeditor').ckeditor();
    });

    function editorialText(id) {
        var id_jab = id.replace("'", "");
        var url = 'index.php/ever/verification/editorialText/' + id_jab;
        var valID = $('.jabatan' + id_jab).val();
        var data = [
            {
                name: 'id_jabatan',
                value: valID
            }
        ];
        $('#loader_area').show();

        $.post(url, data, function(res) {
            msg = {
                success: 'Data Berhasil Disimpan!',
                error: 'Data Gagal Disimpan!'
            };
            $('#loader_area').hide();
            if (res == 0) {
                alertify.error(msg.error);
            } else {
                alertify.success(msg.success);
            }
        });
    }

    function primary() {
        var url = 'index.php/ever/verification/primary';
        var prim = $("input[name=primary]:checked").val();
        var primpn = $("input[name=primarypn]:checked").val();
        var data = [
            {
                name: "idlhkpn",
                value: prim
            },
            {
                name: "idpn",
                value: primpn
            },
            {
                name: "pn",
                value: '<?= $ID_PN; ?>'
            },
            {
                name: 'lhkpn',
                value: '<?= $ID_LHKPN; ?>'
            }
        ];
        $.post(url, data, function(res) {
            msg = {
                success: 'Data Berhasil Disimpan!',
                error: 'Data Gagal Disimpan!'
            };
            if (data == 0) {
                alertify.error(msg.error);
            } else {
                alertify.success(msg.success);
            }
        })
    }

    function f_kirim_pesan() {
        $('#loader_area').show();

        $.post('index.php/mailbox/sent/sentJabatan', {id: $('#idUser').val(), email: $('#idInst').val(), msg: $('textarea[name="MSG_JABATAN"]').text()}, function(data) {
            if (data == 0) {
                alertify.error('Gagal Mengirimkan Email');
            } else {
                alertify.success('Berhasil Mengirimkan Email');
                $('#divJ').hide();
            }

            $('#loader_area').hide();
        })
    }
</script>
