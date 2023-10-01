<?php

/**
* @author Primaningtyas Sukawati
* @version 05022018
*/

 ?>
<div id="wrapperFormAdd">

<!-- Tambah Data -->
    <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/eaudit/Korespondensi/savesurat" enctype="multipart/form-data">
    <div class="tab-content" style="padding: 5px; border:0px solid #cfcfcf;margin-top: -1px;">
            <div role="tabpanel" class="tab-pane active" id="a">
            <div class="contentTab">
              <div class="form-group">
                  <label class="col-sm-3 control-label">Tanggal Surat <span class="red-label">*</span>:</label>
                  <div class="col-sm-3">
                      <input required class="form-control date-picker" type='text'name='TGL_SURAT' id='TGL_SURAT' placeholder='DD/MM/YYYY'>
                  </div>
              </div>
              <div class="form-group">
                  <label class="col-sm-3 control-label">Referensi <span class="red-label">*</span>:</label>
                  <div class="col-sm-7">
                      <input required class="form-control" type='text' size='40' name='Referensi' id='Referensi' placeholder="Referensi">
                  </div>
              </div>
              <div class="form-group">
                  <label class="col-sm-3 control-label">Nomor <span class="red-label">*</span>:</label>
                  <div class="col-sm-7">
                      <input required class="form-control" type='text' maxlength="16" size='40' name='NomorSurat' id='NomorSurat' placeholder="Nomor Surat" onblur="cek_user(this.value)">
                      <span class="help-block"><font id='username_ada' style='display:none;' color='red'>User PN dengan NIK <span id="check_uname_add" style="font-style: italic; font-weight: bold;"></span> sudah terdaftar</font></span>
                  </div>
              </div>
              <div class="form-group">
                  <label class="col-sm-3 control-label">Hal <span class="red-label">*</span>:</label>
                  <div class="col-sm-7">
                      <input required class="form-control" type='text' size='40' name='Hal' id='Hal' placeholder="Hal">
                  </div>
              </div>
              <!-- <div class="form-group">
                  <label class="col-sm-3 control-label">Dari <span class="red-label">*</span>:</label>
                  <div class="col-sm-7">
                      <input required class="form-control" type='text' size='40' name='Dari' id='Dari' placeholder="Dari">
                  </div>
              </div> -->
              <div class="form-group">
                  <label class="col-sm-3 control-label">Dari<span class="red-label">*</span>:</label>
                  <div id="inpCariInstansiPlaceHolder" class="col-sm-7">
                      <input type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI' value='' placeholder="-- Pilih Jenis Instasi --">
                  </div>
              </div>
              <div class="form-group">
                  <label class="col-sm-3 control-label"></label>
                  <div id="inpCariInstansiTujuanPlaceHolder" class="col-sm-7">
                      <input type='text' class="input-sm form-control" name='CARI[INSTANSI_TUJUAN]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI_TUJUAN' value='' placeholder="-- Pilih Nama Instansi --">
                  </div>
              </div>
            </div>
            <br>
            <br>
            <div class="pull-right">
              <input type="hidden" name="ID_PN" id="ID_PN">
              <input type="hidden" name="act" value="doinsert">
              <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
              <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox2();">
            </div>
            <div class="clearfix"></div>
            </div>
    </div>
<!-- End Tambah Data -->
        <div class="pull-right">

        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        // ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);
        $('.test').click(function(e) {
          alert('cuks');
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Tambah PN/WL Individual', html, '', 'large');
            });
            return false;
        });
        initiateCariJenisInstansi();
        $('#CARI_INSTANSI').change(function(event) {
            initiateSelect2CariInstansiTujuan($(this).val());
        });
        <?php if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2"): ?>
            initiateSelect2CariInstansiTujuan(3122);
        <?php else: ?>
            initiateSelect2CariInstansiTujuan(<?php echo $this->session->userdata('INST_SATKERKD'); ?>);
        <?php endif; ?>
    });
    function initiateCariJenisInstansi() {

        $("#CARI_INSTANSI").remove();
        $("#inpCariInstansiPlaceHolder").empty();
    <?php if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2"): ?>
            $("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='3122' placeholder=\"--\">");
            initiateSelect2CariInstansiTujuan(3122);
    <?php else: ?>
            $("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='' placeholder=\"-- Pilih Instansi --\">");
    <?php endif; ?>
        var cari_instansi_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getInstansiTujuan') ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $('#CARI_INSTANSI').val();
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getInstansiTujuan') ?>/" + id, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        };

        var iins = null;
        $.ajax({
            url: "<?php echo base_url('index.php/share/reff/getInstansiTujuan') ?>",
            dataType: "json",
            async: false,
        }).done(function(data) {
            if (!isEmpty(data.item)) {
                cari_instansi_cfg.data = [{
                        id: data.item[0].id,
                        name: data.item[0].name
                    }];

                iins = data.item[0].id;

                $('#CARI_INSTANSI').select2(cari_instansi_cfg);

                if (iins != null) {
                    $("#CARI_INSTANSI").val(iins).trigger("change");
                    initiateSelect2CariInstansiTujuan(iins);
                }
            }
        });

    };
    function initiateSelect2CariInstansiTujuan(TIPE_INSTANSI) {

        $("#CARI_INSTANSI_TUJUAN").remove();
        $("#inpCariInstansiTujuanPlaceHolder").empty();

        var set_default_null = "PENCEGAHAN";

        if (TIPE_INSTANSI !== 3122) {
            $("#inpCariInstansiTujuanPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI_TUJUAN]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI_TUJUAN' value='' placeholder=\"-- Pilih Nama Instansi --\">");
        }
        else {
            $("#inpCariInstansiTujuanPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI_TUJUAN]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI_TUJUAN' value='' placeholder=\"--\">");
            set_default_null = "PENCEGAHAN";
        }
        var cari_unit_kerja_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getNamaInstansiTujuan'); ?>/" + TIPE_INSTANSI + "?setdefault_to_null="+set_default_null,
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var INSTANSI_TUJUAN = $('#CARI_INSTANSI_TUJUAN').val();
                if (INSTANSI_TUJUAN !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getNamaInstansiTujuan') ?>/" + TIPE_INSTANSI + "/" + INSTANSI_TUJUAN + "?setdefault_to_null="+set_default_null, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        };

        var dsuk = null;
        if (isDefined(TIPE_INSTANSI)) {
            var __INSTANSI_TUJUAN = $('#CARI_INSTANSI_TUJUAN').val();

            $.ajax("<?php echo base_url('index.php/share/reff/getNamaInstansiTujuan') ?>/" + TIPE_INSTANSI + "/" + __INSTANSI_TUJUAN + "?setdefault_to_null="+set_default_null, {
                dataType: "json"
            }).success(function(data) {
  //                    console.log(data, data.item, !isEmpty(data.item));
                if (!isEmpty(data.item)) {
                    cari_unit_kerja_cfg.data = [{
                            id: data.item[0].id,
                            name: data.item[0].name
                        }];

                    dsuk = data.item[0].id;

                    $('#CARI_INSTANSI_TUJUAN').select2(cari_unit_kerja_cfg).on("change", function(e) {
  gtblDaftarIndividual.fnClearTable(0);
        gtblDaftarIndividual.fnDraw();
        reloadTableDoubleTime(gtblDaftarIndividual);
                    });

                    if (dsuk != null) {
                        $("#CARI_INSTANSI_TUJUAN").val(dsuk).trigger("change");
                    }
                }

            });
        }
    };
</script>
<script type="text/javascript">
	jQuery(document).ready(function() {
	    $('.date-picker').datepicker({
	    format: 'dd/mm/yyyy'
	    })
	});
</script>
<?php
//}
?>
