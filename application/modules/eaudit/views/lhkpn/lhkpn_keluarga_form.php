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
 * @package Views/keluarga
 */
?>
<?php
if ($form == 'add') {
    ?>
    <div id="wrapperFormAdd">
        <form method="post" class="form-horizontal addForm" id="ajaxFormAdd" action="index.php/efill/lhkpn/savekeluarga" enctype="multipart/form-data">
            <div class="box-body">
                <div class="col-md-6">

                    <div class="form-group">
                        <label> Nama <span class="red-label">*</span></label>

                        <input required class="form-control input_capital" name='NAMA' id='NAMA'  type="text">

                    </div>
                    <div class="form-group">
                        <label>Hubungan<span class="red-label">*</span>  </label>
                    </div> 
                    <div class="form-group"> 
                        <label>
                            <input required name="HUBUNGAN" value="1" type="radio" onchange="viewHubungan()" class="HUBUNGAN"> Istri
                        </label>

                        <label>
                            <input required name="HUBUNGAN" value="2" type="radio" onchange="viewHubungan()" class="HUBUNGAN"> Suami
                        </label>
                        <label>
                            <input required name="HUBUNGAN" value="3" type="radio" onchange="viewHubungan()" class="HUBUNGAN"> Anak Tanggungan
                        </label>
                        <label>
                            <input required name="HUBUNGAN" value="4" type="radio" onchange="viewHubungan()" class="HUBUNGAN"> Anak Bukan Tanggungan
                        </label>

                    </div>

                    <!--<div class="form-group">
                            <div class="col-sm-8"></div>
                            <div class="col-sm-2">
                                    <label>
                                            <input name="STATUS_HUBUNGAN" value="1" type="radio" onchange="viewStatus()" class="STATUS_HUBUNGAN" checked> Nikah
                                    </label>
                            </div>
                            <div class="col-sm-2">
                                    <label>
            <input name="STATUS_HUBUNGAN" value="-1" type="radio" onchange="viewStatus()" class="STATUS_HUBUNGAN"> Cerai
                                    </label>
                            </div>
                    </div>-->

                    <!--<div id="nikah">
                            <div class="form-group">
                                    <div class="col-sm-6"></div>
                                    <label class="col-sm-2 control-label">Tempat :</label>
                                    <div class="col-sm-4">
                                            <input class="form-control" name='TEMPAT_NIKAH' id='TEMPAT_NIKAH' placeholder="Tempat Nikah" type="text">
                                    </div>
                            </div>
                            <div class="form-group">
                                    <div class="col-sm-6"></div>
                                    <label class="col-sm-2 control-label">Tanggal :</label>
                                    <div class="col-sm-4">
                                            <input class="form-control datepicker"  name='TANGGAL_NIKAH' id='TANGGAL_NIKAH' placeholder="DD/MM/YYYY" type="text">
                                    </div>
                            </div>-->

                    <!-- <div id="cerai" style="display: none">
                            <div class="form-group">
                                    <div class="col-sm-6"></div>
                                    <label class="col-sm-2 control-label">Tempat :</label>
                                    <div class="col-sm-4">
                                            <input class="form-control" name='TEMPAT_CERAI' id='TEMPAT_CERAI' placeholder="Tempat Cerai" type="text">
                                    </div>
                            </div>
                            <div class="form-group">
                                    <div class="col-sm-6"></div>
                                    <label class="col-sm-2 control-label">Tanggal :</label>
                                    <div class="col-sm-4">
                                            <input class="form-control datepicker"  name='TANGGAL_CERAI' id='TANGGAL_CERAI' placeholder="DD/MM/YYYY" type="text">
                                    </div>
                            </div>
                    </div> -->

                    <!--   <div class="form-group">
                          <label class="col-sm-4 control-label"></label>
                          <div class="col-sm-4">
                              <label>
                                  <input required name="HUBUNGAN" value="3" type="radio" onchange="viewHubungan()" class="HUBUNGAN"> Anak Tanggungan
                              </label>
                          </div>
                          <div class="col-sm-4">
                              <label>
                                  <input required name="HUBUNGAN" value="4" type="radio" onchange="viewHubungan()" class="HUBUNGAN"> Anak Bukan Tanggungan
                              </label>
                          </div>
                      </div> -->
                    <div class="form-group">
                        <label>Tempat Lahir <span class="red-label">*</span> </label>

                        <input required class="form-control input_capital" name='TEMPAT_LAHIR' id='TEMPAT_LAHIR'  type="text">

                    </div>
                    <div class="form-group">
                        <label>Tanggal Lahir <span class="red-label">*</span> </label>

                        <input required class="form-control datepicker"  name='TANGGAL_LAHIR' id='TANGGAL_LAHIR'  type="text">

                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Jenis Kelamin <span class="red-label">*</span> </label>
                    </div>
                    <div class="form-group">
                        <label>
                            <input required name="JENIS_KELAMIN" value="LAKI-LAKI" id="pria" type="radio"> Laki-laki
                        </label>


                        <label>
                            <input required name="JENIS_KELAMIN" value="PEREMPUAN" id="wanita" type="radio"> Perempuan
                        </label>
                    </div>
                    <div class="form-group">
                        <label> Pekerjaan  <span class="red-label"></label>

                        <input class="form-control input_capital" name='PEKERJAAN' id='PEKERJAAN'  type="text">

                    </div>
                    <div class="form-group">
                        <label> Alamat Rumah <span class="red-label">*</span></label>

                        <textarea name="ALAMAT_RUMAH" id="ALAMAT_RUMAH" cols="30" rows="2" class="form-control ALAMAT_RUMAH"  required></textarea>
                        <!-- <input required class="form-control ALAMAT_RUMAH" name='ALAMAT_RUMAH' id='ALAMAT_RUMAH' placeholder="Alamat Rumah" type="text"> -->
                        <a href="javascript:;" onclick="samaPN();" class="btn btn-sm btn-primary">Sama dengan PN</a>

                    </div>
                    <div class="form-group">
                        <label> Nomor Telepon/Handphone</label>

                        <input class="form-control numbersOnly" onkeypress="validate(event)" name='NOMOR_TELPON' id='NOMOR_TELPON'  type="text">

                    </div>

                </div>
            </div>
            <div class="pull-right">
                <input type='hidden' readonly name='ID_LHKPN' id="id_lhkpn" value="<?= @$id_lhkpn; ?>">
                <input class="form-control" nname='ID_KELUARGA' id='ID_KELUARGA' placeholder="Id Keluarga" type="hidden">
                <input class="form-control" name='ID_KELUARGA_LAMA' id='ID_KELUARGA_LAMA' placeholder="Id Keluarga Lama" type="hidden">
                <input type="hidden" name="act" value="doinsert">
                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                <input type="reset" class="btn btn-sm btn-danger" value="Batal" onclick="CloseModalBox();">
            </div>

        </form>
    </div>
    <script type="text/javascript">
        $('.input_capital').keyup(function () {
            $(this).val($(this).val().toUpperCase());
        });
        $(document).ready(function () {
            // ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);
            ID = $('#id_lhkpn').val();
            ng.formProcess($("#ajaxFormAdd"), 'insert', '', ng.LoadAjaxTabContent, {url: 'index.php/efill/lhkpn/showTable/3/' + ID + '/edit', block: '#block', container: $('#keluarga').find('.contentTab')});
        });
        function viewStatus(STATUS_HUBUNGAN)
        {
            if (document.forms["ajaxFormAdd"]["STATUS_HUBUNGAN"][0].checked || STATUS_HUBUNGAN == '1')
            {
                //commitment selected
                // document.getElementById("cerai").style.display = 'none';
                document.getElementById("nikah").style.display = '';
            } else if (document.forms["ajaxFormAdd"]["STATUS_HUBUNGAN"][1].checked || STATUS_HUBUNGAN == '-1') {
                //disciplinary selected
                // document.getElementById("cerai").style.display = '';
                document.getElementById("nikah").style.display = 'none';
            }
        }
        function viewHubungan(HUBUNGAN)
        {

            if (document.forms["ajaxFormAdd"]["HUBUNGAN"][0].checked || HUBUNGAN == '1')
            {
                $('#pria').prop('disabled', true);
                $('#wanita').prop('disabled', false);
                document.getElementById("wanita").checked = true;
                document.getElementById("status").style.display = '';
            } else if (document.forms["ajaxFormAdd"]["HUBUNGAN"][1].checked || HUBUNGAN == '2')
            {
                $('#pria').prop('disabled', false);
                $('#wanita').prop('disabled', true);
                document.getElementById("pria").checked = true;
                document.getElementById("status").style.display = '';
            } else if (document.forms["ajaxFormAdd"]["HUBUNGAN"][2].checked || HUBUNGAN == '3')
            {
                document.getElementById("status").style.display = 'none';
                $('#pria').prop('disabled', false);
                $('#wanita').prop('disabled', false);
            } else if (document.forms["ajaxFormAdd"]["HUBUNGAN"][3].checked || HUBUNGAN == '4')
            {
                document.getElementById("status").style.display = 'none';
                $('#pria').prop('disabled', false);
                $('#wanita').prop('disabled', false);
            }
        }
    </script>
    <?php
}
?>
<?php
if ($form == 'edit') {
    ?>
    <div id="wrapperFormEdit">
        <form method="post" class="form-horizontal addForm" id="ajaxFormEdit" action="index.php/efill/lhkpn/savekeluarga">
            <div class="box-body">

                <div class="col-md-6">


                    <div class="form-group">
                        <label> Nama<span class="red-label">*</span> </label>

                        <input required class="form-control input_capital" name='NAMA' id='NAMA' value='<?php echo $item->NAMA; ?>' placeholder="Nama" type="text">

                    </div>
                    <div class="form-group">
                        <label> Hubungan <span class="red-label">*</span></label>
                    </div>
                    <div class="form-group">  
                        <label>
                            <input <?php echo $item->HUBUNGAN == '1' ? 'checked' : ''; ?> required name="HUBUNGAN" value="1" type="radio" onchange="viewHubungan()" class="HUBUNGAN"> Istri
                        </label>

                        <label>
                            <input <?php echo $item->HUBUNGAN == '2' ? 'checked' : ''; ?> required name="HUBUNGAN" value="2" type="radio" onchange="viewHubungan()" class="HUBUNGAN"> Suami
                        </label>


                        <label>
                            <input <?php echo $item->HUBUNGAN == '3' ? 'checked' : ''; ?> name="HUBUNGAN" value="3" type="radio"  onchange="viewHubungan()" class="HUBUNGAN">Anak Tanggungan
                        </label>

                        <label>
                            <input <?php echo $item->HUBUNGAN == '4' ? 'checked' : ''; ?> name="HUBUNGAN" value="4" type="radio" onchange="viewHubungan()" class="HUBUNGAN"> Anak Bukan Tanggungan`
                        </label>

                    </div>


                    <div class="form-group">
                        <label> Tempat Lahir <span class="red-label">*</span></label>

                        <input required class="form-control input_capital" name='TEMPAT_LAHIR' id='TEMPAT_LAHIR' value='<?php echo $item->TEMPAT_LAHIR; ?>' placeholder="Tempat Lahir" type="text">

                    </div>
                    <div class="form-group">
                        <label> Tanggal Lahir <span class="red-label">*</span></label>

                        <input required class="form-control datepicker"  name='TANGGAL_LAHIR' id='TANGGAL_LAHIR' value='<?php echo $item->TANGGAL_LAHIR; ?>' placeholder="DD/MM/YYYY" type="text">

                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label> Jenis Kelamin <span class="red-label">*</span></label>
                    </div> 
                    <div class="form-group">
                        <label>
                            <input id="pria" required name="JENIS_KELAMIN" value="LAKI-LAKI" type="radio" <?php echo $item->JENIS_KELAMIN == 'LAKI-LAKI' ? 'checked' : ''; ?>> Laki-laki
                        </label>

                        <label>
                            <input id="wanita" required name="JENIS_KELAMIN" value="PEREMPUAN" type="radio" <?php echo $item->JENIS_KELAMIN == 'PEREMPUAN' ? 'checked' : ''; ?>> Perempuan
                        </label>

                    </div>
                    <div class="form-group">
                        <label > Pekerjaan </label>

                        <input class="form-control input_capital" name='PEKERJAAN' id='PEKERJAAN' value='<?php echo $item->PEKERJAAN; ?>' placeholder="Pekerjaan" type="text">

                    </div>
                    <div class="form-group">
                        <label> Alamat Rumah <span class="red-label">*</span></label>

                        <textarea name="ALAMAT_RUMAH" id="ALAMAT_RUMAH" cols="30" rows="2" class="form-control ALAMAT_RUMAH" placeholder="Alamat Rumah" required><?php echo $item->ALAMAT_RUMAH; ?></textarea>
                        <!-- <input required class="ALAMAT_RUMAH form-control" name='ALAMAT_RUMAH' id='ALAMAT_RUMAH' value='<?php echo $item->ALAMAT_RUMAH; ?>' placeholder="Alamat Rumah" type="text"> -->
                        <a href="javascript:;" onclick="samaPN();" class="btn btn-sm btn-primary">Sama dengan PN</a>

                    </div>
                    <div class="form-group">
                        <label> Nomor Telepon/Handphone</label>

                        <input class="form-control numbersOnly" onkeypress="validate(event)" name='NOMOR_TELPON' id='NOMOR_TELPON' value='<?php echo $item->NOMOR_TELPON; ?>' placeholder="Nomor Telpon" type="text">

                    </div>

                </div>
            </div>
            <div class="pull-right">
                <input class="form-control" name="ID_LHKPN" id="id_lhkpn" value='<?php echo $id_lhkpn = $item->ID_LHKPN; ?>' placeholder="Id Lhkpn" type="hidden">
                <input class="form-control" name='ID_KELUARGA' id='ID_KELUARGA' value='<?php echo $item->ID_KELUARGA; ?>' placeholder="Id Keluarga" type="hidden">
                <input class="form-control" name='ID_KELUARGA_LAMA' id='ID_KELUARGA_LAMA' value='<?php echo $item->ID_KELUARGA_LAMA; ?>' placeholder="Id Keluarga Lama" type="hidden">
                <input type="hidden" name="act" value="doupdate">
                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                <input type="reset" class="btn btn-sm btn-danger" value="Batal" onclick="CloseModalBox();">
            </div>
        </form>
    </div>
    <script type="text/javascript">
        $('.input_capital').keyup(function () {
            $(this).val($(this).val().toUpperCase());
        });
        $(document).ready(function () {
            // ng.formProcess($("#ajaxFormEdit"), 'update', location.href.split('#')[1]);   
            ID = $('#id_lhkpn').val();
            ng.formProcess($("#ajaxFormEdit"), 'update', '', ng.LoadAjaxTabContent, {url: 'index.php/efill/lhkpn/showTable/3/' + ID + '/edit', block: '#block', container: $('#keluarga').find('.contentTab')});
            var chek = $('#pria').is(':checked');
            if (chek != false) {
    //            alert('wanita');
                $('#pria').prop('disabled', false);
                $('#wanita').prop('disabled', true);
            } else {
                // alert('pria');
                $('#pria').prop('disabled', true);
                $('#wanita').prop('disabled', false);
            }
        });
        function viewStatus(STATUS_HUBUNGAN)
        {
            if (document.forms["ajaxFormEdit"]["STATUS_HUBUNGAN"][0].checked || STATUS_HUBUNGAN == '1')
            {
                //commitment selected
                document.getElementById("cerai").style.display = 'none';
                document.getElementById("nikah").style.display = '';
            } else if (document.forms["ajaxFormEdit"]["STATUS_HUBUNGAN"][1].checked || STATUS_HUBUNGAN == '-1') {
                //disciplinary selected
                document.getElementById("cerai").style.display = '';
                document.getElementById("nikah").style.display = 'none';
            }
        }

        function viewHubungan(HUBUNGAN)
        {
            if (document.forms["ajaxFormEdit"]["HUBUNGAN"][0].checked || HUBUNGAN == '1')
            {
                $('#pria').prop('disabled', true);
                $('#wanita').prop('disabled', false);
                document.getElementById("wanita").checked = true;
                document.getElementById("status").style.display = '';
            } else if (document.forms["ajaxFormEdit"]["HUBUNGAN"][1].checked || HUBUNGAN == '2')
            {
                $('#pria').prop('disabled', false);
                $('#wanita').prop('disabled', true);
                document.getElementById("pria").checked = true;
                document.getElementById("status").style.display = '';
            } else if (document.forms["ajaxFormEdit"]["HUBUNGAN"][2].checked || HUBUNGAN == '3')
            {
                $('#pria').prop('disabled', false);
                $('#wanita').prop('disabled', false);
                document.getElementById("status").style.display = 'none';
            } else if (document.forms["ajaxFormEdit"]["HUBUNGAN"][3].checked || HUBUNGAN == '4')
            {
                $('#pria').prop('disabled', false);
                $('#wanita').prop('disabled', false);
                document.getElementById("status").style.display = 'none';
            }
        }
    </script>
    <?php
}
?>
<?php
if ($form == 'delete') {
    ?>
    <div id="wrapperFormDelete">
        Benarkah Akan Menghapus Keluarga dibawah ini ?
        <form method="post" class="form-horizontal" id="ajaxFormDelete" action="index.php/efill/lhkpn/savekeluarga">
            <div class="box-body">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Nama :</label>
                        <div class="col-sm-8">
                            <label class="control-label"><?php echo $item->NAMA; ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Hubungan :</label>
                        <div class="col-sm-8">
                            <label class="control-label"><?php if ($item->HUBUNGAN == '1') {
        echo 'Istri';
    } elseif ($item->HUBUNGAN == '2') {
        echo 'Suami';
    } elseif ($item->HUBUNGAN == '3') {
        echo 'Anak Tanggungan';
    } else {
        echo 'Anak Bukan Tanggungan';
    } ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Tempat Lahir :</label>
                        <div class="col-sm-8">
                            <label class="control-label"><?php echo $item->TEMPAT_LAHIR; ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Tanggal Lahir :</label>
                        <div class="col-sm-8">
                            <label class="control-label"><?php echo $item->TANGGAL_LAHIR; ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Jenis Kelamin :</label>
                        <div class="col-sm-8">
                            <label class="control-label"><?php if ($item->JENIS_KELAMIN == 'p') {
        echo 'Laki-laki';
    } else {
        echo 'Perempuan';
    } ?></label>
                        </div>
                    </div>
    <?php if ($item->STATUS_HUBUNGAN != '') { ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Status Hubungan :</label>
                            <div class="col-sm-8">
                                <label class="control-label"><?php if ($item->STATUS_HUBUNGAN == '1') {
            echo 'Nikah';
        } else {
            echo 'Cerai';
        } ?></label>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-md-6">
    <?php if ($item->STATUS_HUBUNGAN == '1') { ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tempat Nikah :</label>
                            <div class="col-sm-8">
                                <label class="control-label"><?php echo $item->TEMPAT_NIKAH; ?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tempat Nikah :</label>
                            <div class="col-sm-8">
                                <label class="control-label"><?php echo $item->TEMPAT_NIKAH; ?></label>
                            </div>
                        </div>
    <?php } elseif ($item->STATUS_HUBUNGAN == '-1') { ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tempat Nikah :</label>
                            <div class="col-sm-8">
                                <label class="control-label"><?php echo $item->TEMPAT_NIKAH; ?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tempat Nikah :</label>
                            <div class="col-sm-8">
                                <label class="control-label"><?php echo $item->TEMPAT_NIKAH; ?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tempat Cerai :</label>
                            <div class="col-sm-8">
                                <label class="control-label"><?php echo $item->TEMPAT_CERAI; ?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tanggal Cerai :</label>
                            <div class="col-sm-8">
                                <label class="control-label"><?php echo $item->TANGGAL_CERAI; ?></label>
                            </div>
                        </div>
    <?php } ?>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Pekerjaan :</label>
                        <div class="col-sm-8">
                            <label class="control-label"><?php echo $item->PEKERJAAN; ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Alamat Rumah :</label>
                        <div class="col-sm-8">
                            <label class="control-label"><?php echo $item->ALAMAT_RUMAH; ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Nomor Telpon :</label>
                        <div class="col-sm-8">
                            <label class="control-label"><?php echo $item->NOMOR_TELPON; ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pull-right">
                <input type="hidden" name="ID_KELUARGA" value="<?php echo $item->ID_KELUARGA; ?>">
                <input class="form-control" name="ID_LHKPN" id="id_lhkpn" value='<?php echo $item->ID_LHKPN; ?>' placeholder="Id Lhkpn" type="hidden">
                <input type="hidden" name="act" value="dodelete">
                <button type="submit" class="btn btn-sm btn-primary">Hapus</button>
                <input type="reset" class="btn btn-sm btn-danger" value="Batal" onclick="CloseModalBox();">
            </div>
        </form>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            // ng.formProcess($("#ajaxFormDelete"), 'delete', location.href.split('#')[1]);
            ID = $('#id_lhkpn').val();
            ng.formProcess($("#ajaxFormDelete"), 'delete', '', ng.LoadAjaxTabContent, {url: 'index.php/efill/lhkpn/showTable/3/' + ID + '/edit', block: '#block', container: $('#keluarga').find('.contentTab')});
        });
    </script>
    <?php
}
?>

<?php
if ($form == 'detail') {
    ?>
    <div id="wrapperFormDetail" class="form-horizontal">
        <div class="box-body">
            <div class="col-md-6">

                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama :</label>
                    <div class="col-sm-8">
                        <label><?php echo $item->NAMA; ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Hubungan :</label>
                    <div class="col-sm-8">
                        <label><?php if ($item->HUBUNGAN == '1') {
        echo 'Istri';
    } elseif ($item->HUBUNGAN == '2') {
        echo 'Suami';
    } elseif ($item->HUBUNGAN == '3') {
        echo 'Anak Tanggungan';
    } else {
        echo 'Anak Bukan Tanggungan';
    } ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Tempat Lahir :</label>
                    <div class="col-sm-8">
                        <label><?php echo $item->TEMPAT_LAHIR; ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Tanggal Lahir :</label>
                    <div class="col-sm-8">
                        <label><?php echo $item->TANGGAL_LAHIR; ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Jenis Kelamin :</label>
                    <div class="col-sm-8">
                        <label><?php if ($item->JENIS_KELAMIN == 'p') {
        echo 'Pria';
    } else {
        echo 'Wanita';
    } ?></label>
                    </div>
                </div>
    <?php if ($item->STATUS_HUBUNGAN != '') { ?>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Status Hubungan :</label>
                        <div class="col-sm-8">
                            <label><?php if ($item->STATUS_HUBUNGAN == '1') {
            echo 'Nikah';
        } else {
            echo 'Cerai';
        } ?></label>
                        </div>
                    </div>
    <?php } ?>
            </div>
            <div class="col-md-6">
    <?php if ($item->STATUS_HUBUNGAN == '1') { ?>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Tempat Nikah :</label>
                        <div class="col-sm-8">
                            <label><?php echo $item->TEMPAT_NIKAH; ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Tempat Nikah :</label>
                        <div class="col-sm-8">
                            <label><?php echo $item->TEMPAT_NIKAH; ?></label>
                        </div>
                    </div>
    <?php } elseif ($item->STATUS_HUBUNGAN == '-1') { ?>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Tempat Nikah :</label>
                        <div class="col-sm-8">
                            <label><?php echo $item->TEMPAT_NIKAH; ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Tempat Nikah :</label>
                        <div class="col-sm-8">
                            <label><?php echo $item->TEMPAT_NIKAH; ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Tempat Cerai :</label>
                        <div class="col-sm-8">
                            <label><?php echo $item->TEMPAT_CERAI; ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Tanggal Cerai :</label>
                        <div class="col-sm-8">
                            <label><?php echo $item->TANGGAL_CERAI; ?></label>
                        </div>
                    </div>
    <?php } ?>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Pekerjaan :</label>
                    <div class="col-sm-8">
                        <label><?php echo $item->PEKERJAAN; ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Alamat Rumah :</label>
                    <div class="col-sm-8">
                        <label><?php echo $item->ALAMAT_RUMAH; ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nomor Telpon :</label>
                    <div class="col-sm-8">
                        <label><?php echo $item->NOMOR_TELPON; ?></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </div>
    <?php
}
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });
    });
    function validate(evt) {
        var theEvent = evt || window.event;
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode(key);
        var regex = /[0-9\b]|\./;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault)
                theEvent.preventDefault();
        }
    }
    function samaPN() {
        var dta = "<?= substr(md5(@$id_lhkpn), 5, 8); ?>";
        var url = "index.php/efill/lhkpn/samaPN/" + dta;
        $.post(url, function (html) {
            if (html == '') {
                alertify.error('Data pribadi PN masih kosong!');
            } else {
                $('.addForm .ALAMAT_RUMAH').val(html);
            }
        });
    }
</script>
