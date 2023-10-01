<?php

/**
* @author Primaningtyas Sukawati
* @version 05022018
*/

 ?>
<div id="wrapperFormAdd">

<!-- Tambah Data -->

<!-- <form class="form-horizontal" method="post" id="ajaxFormAdd" action="<?php echo site_url('index.php/eaudit/Korespondensi/save_korespondensi_keluar'); ?>" enctype="multipart/form-data"> -->
<form class="form-horizontal" id="formSuratKeluar">
    <div class="tab-content" style="padding: 5px; border:0px solid #cfcfcf;margin-top: -1px;">
      <div role="tabpanel" class="tab-pane active" id="a">
        <div class="contentTab">
          <div class="box-body">
            <div class="col-md-6">
                <div class="row">
                  <div class="form-group">
                      <label class="col-sm-3 control-label">Tanggal Surat:</label>
                      <div class="col-sm-3">
                          <!-- <input required class="form-control date-picker" type='text' name='TGL_SURAT' id='TGL_SURAT' placeholder='DD/MM/YYYY'> -->
                          <input disabled class="form-control date-picker" type='text' name='TGL_SURAT' id='TGL_SURAT' placeholder='DD/MM/YYYY'>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-3 control-label">Nomor:</label>
                      <div class="col-sm-7">
                          <!-- <input required class="form-control" type='text' maxlength="16" size='40' name='NomorSurat' id='NomorSurat' placeholder="Nomor Surat" onblur="cek_user(this.value)"> -->
                          <input disabled class="form-control" type='text' maxlength="16" size='40' name='NomorSurat' id='NomorSurat' placeholder="Nomor Surat" onblur="cek_user(this.value)">
                          <span class="help-block"><font id='username_ada' style='display:none;' color='red'>User PN dengan NIK <span id="check_uname_add" style="font-style: italic; font-weight: bold;"></span> sudah terdaftar</font></span>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-3 control-label">Kontak <span class="red-label">*</span>:</label>
                      <div class="col-sm-7">
                          <input required class="form-control" type='text' size='40' name='Kontak' id='Kontak' placeholder="Kontak">
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-3 control-label">Sifat <span class="red-label">*</span>:</label>
                      <div class="col-sm-7">
                          <input required class="form-control" type='text' size='40' name='Sifat' id='Sifat' placeholder="Sifat">
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-3 control-label">Lampiran <span class="red-label">*</span>:</label>
                      <div class="col-sm-7">
                          <input required class="form-control" type='text' size='40' name='Lampiran' id='Lampiran' placeholder="Lampiran">
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-3 control-label">Hal <span class="red-label">*</span>:</label>
                      <div class="col-sm-7">
                          <input required class="form-control" type='text' size='40' name='Hal' id='Hal' placeholder="Hal">
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="col-sm-3 control-label">Penandatangan <span class="red-label">*</span>:</label>
                      <!-- <div class="col-sm-7">
                          <input required class="form-control" type='text' size='40' name='Penandatangan' id='Penandatangan' placeholder="Penandatangan">
                      </div> -->
                      <div id="inpCariPenandatanganPlaceHolder" class="col-sm-7">
                          <input type='text' class="input-sm form-control" name='CARI[PENANDATANGAN]' style="border:none;padding:6px 0px;" id='CARI_PENANDATANGAN' value='' placeholder="-- Pilih Penandatangan --">
                      </div>
                  </div>
                  <!-- <?php if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2): ?> -->
                  <div class="form-group">
                      <label class="col-sm-3 control-label">Jenis Instansi Tujuan<span class="red-label">*</span>:</label>
                      <div id="inpCariInstansiPlaceHolder" class="col-sm-7">
                          <input type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI' value='' placeholder="-- Pilih Jenis Instasi --">
                      </div>
                  </div>
                  <!-- <?php endif; ?> -->
                  <!-- <?php if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 3): ?> -->
                      <div class="form-group">
                          <label class="col-sm-3 control-label">Nama Instansi Tujuan:</label>
                          <div id="inpCariInstansiTujuanPlaceHolder" class="col-sm-7">
                              <input type='text' class="input-sm form-control" name='CARI[INSTANSI_TUJUAN]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI_TUJUAN' value='' placeholder="-- Pilih Nama Instansi --">
                          </div>
                      </div>
                  <!-- <?php endif; ?> -->
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                  <div class="form-group">
                      <!-- <label class="col-sm-2 control-label" style="padding-left: 0px; padding-right: 0px;">PN <font color='red'>*</font> :</label> -->
                      <div class="col-sm-10" style="padding-left: 0px;">
                          <!-- list pn jika belum terdaftar maka ditambahkan -->
                          <div class="col-sm-7" style="padding-right: 0px;">
                              <!-- <input type='hidden' class="form-control" name='ID_PN' id='ID_PN' value='<?php echo show_me($form_data, "ID_PN", ""); ?>' required readonly>
                              <input type='hidden' class="form-control" name='NIK' id='NIK' value='<?php echo show_me($form_data, "NIK", ""); ?>' required readonly>
                              <input type='text' class="form-control" name='NAMA' id='NAMA' value='<?php echo show_me($form_data, "NAMA", ""); ?>' required readonly> -->
                              <button type="button" class="btn btn-sm btn-primary btn-add" id="btnTambahPN"><i class="fa fa-plus"></i> Tambah Data PN</button>
                          </div>
                          <!-- <div class="col-sm-2" style="padding-left: 0px; padding-right: 0px;">
                              <button type="button" class="btn btn-sm btn-info" id="btnCariPN"><i class="fa fa-search"></i></button>
                          </div> -->
                      </div>
                  </div>
                </div>
            </div>
          </div>
          <br>
          <div class="pull-right">
            <input type="hidden" name="SURAT_ID" id="SURAT_ID">
            <input type="hidden" name="act" value="doinsert">
            <!-- <button type="submit" class="btn btn-sm btn-primary">Simpan</button> -->
            <input class="btn btn-sm btn-primary" id="btnSimpanSuratKeluar" value="Simpan"></input>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox2();">
          </div>
          <div class="clearfix"></div>
        </div>
    </div>
<!-- End Tambah Data -->
    <div class="pull-right">

    </div>
  </div>
  </form>
</div>
<div id="wrapperCariPN" style="display: none;">
    <ol class="breadcrumb">
        <li>Tambah Surat Keluar</li>
        <li>Cari PN</li>
    </ol>
    <!-- <button type="button" class="btn btn-sm btn-default" id="btnTambahPN"><i class="fa fa-plus"></i> Tambah Data PN</button> -->
    <!-- <div class="pull-left">
        <button class="btn btn-sm btn-primary" id="btn-add-pn-individual" href="index.php/ereg/all_pn/daftar_pn_individu/0/daftarindividu" >Tambah PN</button>
    </div> -->
    <!--<div class="pull-right">-->
    <div class="col-md-12">
        <form method="post" class='form-horizontal' id="ajaxFormCariPN" action="index.php/eaudit/korespondensi/hasilcaripn/">
        <!-- <form method="post" class='form-horizontal' id="ajaxFormCariPN" action="index.php/efill/lhkpnoffline/hasilcaripn/"> -->
            <div class="box-body">
                <div class="col-md-6">
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Nama/NIK <font color='red'>*</font> :</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control input-sm pull-right" placeholder="Search PN / WL NIK" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="CARI_TEXT_PN"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tanggal Lahir :</label>
                            <div class="col-sm-3">
                                <input class="form-control input-sm pull-right date-picker field-required" type='text' name='CARI[TGL_LAHIR]' id='TGL_LAHIR' placeholder='DD/MM/YYYY'>
                            </div>
                            <div class="form-group">
                                <div class="col-col-sm-3 col-sm-offset-4-2">
                                    <button type="submit" class="btn btn-sm btn-default" id="btn-cari">Cari</button>
                                    <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#CARI_TEXT_PN').val(''); $('#CARI_TEXT_PN').focus(); $('#ajaxFormCariPN').trigger('submit');">Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <br/>
    <div class="clearfix" style="margin-bottom: 10px;"></div>
    <div id="wrapperHasilCariPN">
        <!-- draw here -->
    </div>
    <div class="pull-right">
        <button type="button" class="btn btn-sm btn-default" id="btnKembaliKePenerimaan">Kembali Ke form</button>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function() {
      $('#btnSimpanSuratKeluar').click(function() {
        var url = "index.php/eaudit/Korespondensi/save_korespondensi_keluar";
        var TglSurat = $('#TGL_SURAT').val();
        var NomorSurat = $('#NomorSurat').val();
        var Kontak = $('#Kontak').val();
        var Sifat = $('#Sifat').val();
        var Lampiran = $('#Lampiran').val();
        var Hal = $('#Hal').val();
        var Penandatangan = $('#CARI_PENANDATANGAN').val();
        var Instansi = $('#CARI_INSTANSI').val();
        var InstansiTujuan = $('#CARI_INSTANSI_TUJUAN').val();

        var a = $('#formSuratKeluar').serialize();
            $.ajax({
               type: "POST",
               url: url,
               data: a,
               success: function(data)
               {
                 $('#loader_area').hide();
                   CloseModalBox2();
                   alert("Data berhasil disimpan.");
               }
             });
      });

        // $('.test').click(function(e) {
        //
        //     url = $(this).attr('href');
        //     $('#loader_area').show();
        //     $.post(url, function(html) {
        //         OpenModalBox('Tambah PN/WL Individual', html, '', 'large');
        //     });
        //     return false;
        // });
       initiateCariJenisInstansi();
       $('#CARI_INSTANSI').change(function(event) {
           initiateSelect2CariInstansiTujuan($(this).val());
       });
       <?php if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2"): ?>
           initiateSelect2CariInstansiTujuan(3122);
       <?php else: ?>
           initiateSelect2CariInstansiTujuan(<?php echo $this->session->userdata('INST_SATKERKD'); ?>);
       <?php endif; ?>
       initiateCariPenandatangan();
    });
    // $('#btnCariPN').click(function() { //wrapperFormAdd
    //   $('#wrapperFormAdd').hide();
    //   $('#wrapperCariPN').show();
    // });
    $('#btnTambahPN').click(function() { //wrapperFormAdd
      $('#wrapperFormAdd').hide();
      $('#wrapperCariPN').show();
    });
    $('#btnKembaliKePenerimaan').click(function() { //wrapperFormAdd
      $('#wrapperFormAdd').show();
      $('#wrapperCariPN').hide();
    });
    // function showCariPN() {
    //     $('#wrapperCariPN').slideDown('fast', function() {
    //         $('#wrapperCariPN').find('#CARI_TEXT_PN').focus();
    //     });
    //     $("#ajaxFormCariPN").submit(function(e) {
    //         e.preventDefault();
    //         var url = $(this).attr('action');
    //         ng.LoadAjaxContentPost(url, $(this), '#wrapperHasilCariPN', _this.eventShowHasilCariPN);
    //         return false;
    //     });
    // };

    function initiateCariPenandatangan() {
        $("#CARI_PENANDATANGAN").remove();
        $("#inpCariPenandatanganPlaceHolder").empty();
        // $("#inpCariPenandatanganPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[PENANDATANGAN]' style=\"border:none;padding:6px 0px;\" id='CARI_PENANDATANGAN' value='' placeholder=\"-- Pilih Instansi --\">");
        <?php if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2"): ?>
                $("#inpCariPenandatanganPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[PENANDATANGAN]' style=\"border:none;padding:6px 0px;\" id='CARI_PENANDATANGAN' value='3122' placeholder=\"--\">");
                initiateSelect2CariInstansiTujuan(3122);
        <?php else: ?>
                $("#inpCariPenandatanganPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[PENANDATANGAN]' style=\"border:none;padding:6px 0px;\" id='CARI_PENANDATANGAN' value='' placeholder=\"-- Pilih Instansi --\">");
        <?php endif; ?>
        var cari_penandatangan_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getPenandatangan') ?>",
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
                var id = $('#CARI_PENANDATANGAN').val();
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getPenandatangan') ?>/" + id, {
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
            url: "<?php echo base_url('index.php/share/reff/getPenandatangan') ?>",
            dataType: "json",
            async: false,
        }).done(function(data) {
            if (!isEmpty(data.item)) {
                cari_penandatangan_cfg.data = [{
                        id: data.item[0].id,
                        name: data.item[0].name
                    }];

                iins = data.item[0].id;

                $('#CARI_PENANDATANGAN').select2(cari_penandatangan_cfg);

                if (iins != null) {
                    $("#CARI_PENANDATANGAN").val(iins).trigger("change");
                    initiateSelect2CariInstansiTujuan(iins);
                }
            }
        });
    };
    function initiateCariJenisInstansi() {

        $("#CARI_INSTANSI").remove();
        $("#inpCariInstansiPlaceHolder").empty();
    <?php if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2"): ?>
            $("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;margin-top:7px;\" id='CARI_INSTANSI' value='3122' placeholder=\"--\">");
            initiateSelect2CariInstansiTujuan(3122);
    <?php else: ?>
            $("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;margin-top:7px;\" id='CARI_INSTANSI' value='' placeholder=\"-- Pilih Instansi --\">");
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
    // create = {
    //   showCariPN: function() {
    //       $('#wrapperCariPN').slideDown('fast', function() {
    //           $('#wrapperCariPN').find('#CARI_TEXT_PN').focus();
    //       });
    //       $("#ajaxFormCariPN").submit(function(e) {
    //           e.preventDefault();
    //           var url = $(this).attr('action');
    //           ng.LoadAjaxContentPost(url, $(this), '#wrapperHasilCariPN', _this.eventShowHasilCariPN);
    //           return false;
    //       });
    //   },
    //   eventShowHasilCariPN: function() {
    //       $(".paginationPN").find("a").click(function() {
    //           var url = $(this).attr('href');
    //           // window.location.hash = url;
    //           ng.LoadAjaxContentPost(url, $('#ajaxFormCariPN'), '#wrapperHasilCariPN', _this.eventShowHasilCariPN);
    //           return false;
    //       });
    //       $('.btnSelectPN').click(function() {
    //           DATAPN = $(this).attr('data-pn');
    //           PN = DATAPN.split('::');
    //           $('#wrapperFormCreate').find('#ID_PN').val(PN[0]);
    //           $('#wrapperFormCreate').find('#NIK').val(PN[1]);
    //           $('#wrapperFormCreate').find('#NAMA').val(PN[2]);
    //           _this.showFormCreate();
    //           _this.hideCariPN();
    //       });
    //   },
    //   hideCariPN: function() {
    //       $('#wrapperCariPN').slideUp('fast');
    //   },
    //   init:function(){
    //       _this = create;
    //
    //   $('#btnCariPN').click(function() {
    //       _this.showCariPN();
    //       _this.hideFormCreate();
    //   });
    //   $('#btnKembaliKePenerimaan').click(function() {
    //       _this.showFormCreate();
    //       _this.hideCariPN();
    //   });
    //
    //   }
    // }
</script>
<script type="text/javascript">
	jQuery(document).ready(function() {
	    $('.date-picker').datepicker({
        autoclose: true,
	      format: 'dd/mm/yyyy'
	    })
	});
</script>
<?php
//}
?>
