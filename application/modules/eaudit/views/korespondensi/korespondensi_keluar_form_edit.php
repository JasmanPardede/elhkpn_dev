<?php

/**
* @author Primaningtyas Sukawati
* @version 05022018
*/

 ?>
 <style>
 .labelKorespondensi
 {
   padding-top: 0px;
   padding-right: 0px;
   padding-left: 10px;
 }
 .labelNamaPN
 {
   font-weight: bold;
 }
 .labelPN
 {
   color: red;
   font-weight:0;
 }
 .colAlign
 {
   text-align: center;
   vertical-align: middle;
 }
 .actionPN
 {
    padding-top: 15px;
 }
 /* #divDataPN
 {
   border-top-color: #3c8dbc;
 } */
 </style>
<div id="wrapperFormAdd">

<!-- Tambah Data -->

<!-- <form class="form-horizontal" method="post" id="ajaxFormAdd" action="<?php echo site_url('index.php/eaudit/Korespondensi/save_korespondensi_keluar'); ?>" enctype="multipart/form-data"> -->
<form class="form-horizontal" id="formSuratKeluar">
    <div class="tab-content" style="padding: 5px; border:0px solid #cfcfcf;margin-top: -1px;">
      <div role="tabpanel" class="tab-pane active" id="a">
        <div class="contentTab">
          <div class="box-body">
            <div class="row">
              <div class="col-md-4">
                  <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label labelKorespondensi">Tanggal Surat</label>
                        <div class="col-sm-4">
                            <!-- <input required class="form-control date-picker" type='text' name='TGL_SURAT' id='TGL_SURAT' placeholder='DD/MM/YYYY'> -->
                            <input class="form-control date-picker" type='text' name='TGL_SURAT' id='TGL_SURAT' placeholder='DD/MM/YYYY' value="<?php echo $surat[0]['SURAT_TANGGAL'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label labelKorespondensi">Nomor</label>
                        <div class="col-sm-7">
                            <!-- <input required class="form-control" type='text' maxlength="16" size='40' name='NomorSurat' id='NomorSurat' placeholder="Nomor Surat" onblur="cek_user(this.value)"> -->
                            <input class="form-control" type='text' maxlength="16" size='40' name='NomorSurat' id='NomorSurat' placeholder="Nomor Surat" onblur="cek_user(this.value)" value="<?php echo $surat[0]['SURAT_NOMOR'] ?>">
                            <span class="help-block"><font id='username_ada' style='display:none;' color='red'>User PN dengan NIK <span id="check_uname_add" style="font-style: italic; font-weight: bold;"></span> sudah terdaftar</font></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label labelKorespondensi">Kontak <span class="red-label">*</span></label>
                        <div class="col-sm-7">
                            <input required class="form-control" type='text' size='40' name='Kontak' id='Kontak' placeholder="Kontak" value="<?php echo $surat[0]['SURAT_KONTAK'] ?>">
                        </div>
                    </div>

                  </div>
              </div>
              <div class="col-md-4">
                  <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label labelKorespondensi">Sifat <span class="red-label">*</span></label>
                        <div class="col-sm-7">
                            <input required class="form-control" type='text' size='40' name='Sifat' id='Sifat' placeholder="Sifat" value="<?php echo $surat[0]['SURAT_SIFAT'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label labelKorespondensi">Lampiran <span class="red-label">*</span></label>
                        <div class="col-sm-7">
                            <input required class="form-control" type='text' size='40' name='Lampiran' id='Lampiran' placeholder="Lampiran" value="<?php echo $surat[0]['SURAT_LAMPIRAN'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label labelKorespondensi">Hal <span class="red-label">*</span></label>
                        <div class="col-sm-7">
                            <input required class="form-control" type='text' size='40' name='Hal' id='Hal' placeholder="Hal" value="<?php echo $surat[0]['SURAT_HAL'] ?>">
                        </div>
                    </div>

                  </div>
              </div>
              <div class="col-md-4">
                  <div class="row">
                    <div class="form-group">
                        <label class="col-sm-4 control-label labelKorespondensi">Penandatangan <span class="red-label">*</span></label>
                        <div id="inpCariPenandatanganPlaceHolder" class="col-sm-7">
                            <input type='text' class="input-sm form-control" name='CARI[PENANDATANGAN]' style="border:none;padding:6px 0px;" id='CARI_PENANDATANGAN' value="<?php echo $surat[0]['PENANDATANGAN_NAMA'] ?>" placeholder="-- Pilih Penandatangan --">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label labelKorespondensi">Jenis Instansi Tujuan<span class="red-label">*</span></label>
                        <div id="inpCariInstansiPlaceHolder" class="col-sm-7">
                             <input type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI' value="<?php echo $surat[0]['ORG_KD'] ?>" placeholder="-- Pilih Jenis Instasi --">
                        </div>
                    </div>
                      <div class="form-group">
                          <label class="col-sm-4 control-label labelKorespondensi">Nama Instansi Tujuan<span class="red-label">*</span></label>
                          <div id="inpCariInstansiTujuanPlaceHolder" class="col-sm-7">
                              <input type='text' class="input-sm form-control" name='CARI[INSTANSI_TUJUAN]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI_TUJUAN' value="<?php echo $surat[0]['ORG_NAMA'] ?>" placeholder="-- Pilih Nama Instansi --">
                          </div>
                      </div>
                      <!-- <div class="form-group">
                          <label class="col-sm-3 control-label labelKorespondensi">Nomor Surat Tugas</label>
                          <div class="col-sm-7">
                              <input class="form-control" type='text' size='40' name='NomorSuratTugas' id='NomorSuratTugas' placeholder="Nomor Surat Tugas">
                          </div>
                      </div> -->
                      <!-- <div class="form-group">
                          <label class="col-sm-3 control-label labelKorespondensi">Nomor Surat Tugas <span class="red-label">*</span></label>
                          <div id="inpCariNomorSuratTugasPlaceHolder" class="col-sm-7">
                              <input type='text' class="input-sm form-control" name='CARI[NOMORSURATTUGAS]' style="border:none;padding:6px 0px;" id='CARI_NOMOR_SURAT_TUGAS' value='' placeholder="-- Pilih Nomor Surat Tugas --">
                          </div>
                      </div> -->

                  </div>
              </div>
            </div>
            <div class="row" style="border-top-color: #848588;border-top-style: solid;border-top-width: 2px;border-radius: 3px;">
              <div class="col-md-4 actionPN">
                <div class="form-group">
                    <label class="col-sm-3 labelKorespondensi">Nomor Surat Tugas</label>
                    <div class="col-sm-7">
                        <input class="form-control" type='text' size='40' name='NomorSuratTugasInput' id='NomorSuratTugasInput' placeholder="Nomor Surat Tugas" value="001">
                    </div>
                </div>
              </div>
              <div class="col-md-4 actionPN">
                <div class="form-group">
                    <label class="col-sm-3 labelKorespondensi">Nama PN</label>
                    <div class="col-sm-7">
                        <input class="form-control" type='text' size='40' name='NamaPN' id='NamaPN' placeholder="Nama PN">
                    </div>
                </div>
              </div>
              <div class="col-md-4 actionPN">
                <div class="form-group">
                    <label class="col-sm-5 labelKorespondensi">Termasuk Anggota Keluarga</label>
                    <div class="col-sm-3" style="padding-top: 10px;">
                        <!-- <input type="checkbox" name="IDENTIFIER[]" value="WITH_FAM" id="checkboxKeluarga"> -->
                        <input type="checkbox" name="IDENTIFIER[]" value="WITH_FAM" id="checkboxKeluarga">
                    </div>
                    <div class="form-group">
                        <div class="col-col-sm-3 col-sm-offset-4-2" style="padding-top: 10px;">
                            <button type="submit" class="btn btn-sm btn-default" id="btn-cari" >Cari PN</button>
                            <!-- <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#CARI_TEXT_PN').val(''); $('#CARI_TEXT_PN').focus(); $('#ajaxFormCariPN').trigger('submit');">Clear</button> -->
                        </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="divDataPN">
                <div class="box-body">
                  <input type="hidden" nama="listST" id="listST">
                  <input type="hidden" nama="listNamaPN" id="listNamaPN">
                  <input type="hidden" nama="listNamaAllPN" id="listNamaAllPN">
                  <table class="table table-striped table-hover table-heading no-border-bottom dataTable" id="table_PN">
                    <thead>
                        <tr>
                            <th></th>
                            <th style="width:250px">Nama PN / No. Agenda</th>
                            <th style="width:250px">Nama Anggota Keluarga</th>
                            <th style="width:300px">Jabatan</th>
                            <th>Lembaga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php $lampiran = $this->Korespondensi_model->get_lampiran_by_suratID($surat[0]['SURAT_ID']);
                      // print_r($lampiran);
                      // print_r(sizeof($lampiran));
                      // print_r( $lampiran[0]);
                      $status = "";
                      $tglKirimFinal = "";
                      $tahunKirimFinal = "";
                      $agenda = "";
                      $agendaTemp = "";
                      $className = "";



                      if(sizeof($lampiran) > 0) {
                        $x = 1;
                        // print_r($lampiran[$ii]);
                        for ($ii=0; $ii < sizeof($lampiran); $ii++) {
                          if($lampiran[$ii]['HUBUNGAN'] == "1")
                          {
                            $status = ($lampiran[$ii]['HUBUNGAN'] == "1") ? "Istri" : "Suami" ;
                            $className = "normal";
                          }
                          else if($lampiran[$ii]['HUBUNGAN'] == "3")
                          {
                            $status = "Anak Tanggungan";
                            $className = "normal";
                          }
                          else if($lampiran[$ii]['HUBUNGAN'] == "4")
                          {
                            $status = "Anak Bukan Tanggungan";
                            $className = "normal";
                          }
                          else {
                            $status = "Penyelenggara Negara";
                            $className = "labelPN";
                          }
                          $tglKirimFinal = $lampiran[$ii]['tgl_kirim_final'];
                          $tahunKirimFinal = $tglKirimFinal.split('-')[0];
                          $agenda = $tahunKirimFinal + '/' + ($lampiran[$ii]['JENIS_LAPORAN'] == '4' ? 'R' : 'K') + '/' + $lampiran[$ii]['NIK'] + '/' + $lampiran[$ii]['ID_LHKPN'];
                          if($lampiran[$ii]['NIK'] == null) {
                            $lampiran[$ii]['NIK'] = '  ';
                            $agenda = $agendaTemp;
                          }
                          else {
                            $agendaTemp = $tahunKirimFinal + '/' + ($lampiran[$ii]['JENIS_LAPORAN'] == '4' ? 'R' : 'K') + '/' + $lampiran[$ii]['NIK'] + '/' + $lampiran[$ii]['ID_LHKPN'];
                          }
                          $lembaga = ($lampiran[$ii]['INST_NAMA'] == null) ? "  " : $lampiran[$ii]['INST_NAMA'] ;
                          $jabatan = ($lampiran[$ii]['DESKRIPSI_JABATAN'] == null) ? "  " : $lampiran[$ii]['DESKRIPSI_JABATAN'] ;
                          ?>
                          <tr>
                            <td class="colAlign"></td>
                            <td style="width:200px"><?php echo $lampiran[$ii]['USERNAME_ENTRI']; ?><br> <?php echo $agenda ?></td>
                            <td style="width:200px"><?php echo $lampiran[$ii]['NAMA'] == null ? $lampiran[$ii]['USERNAME_ENTRI'] : $lampiran[$ii]['NAMA'] ; ?> <br><label class='" + <?php echo $className?> + "'><?php echo $status ?></label> </td>
                            <td style="width:200px"><?php echo $jabatan ?></td>
                            <td style="width:200px"><?php echo $lembaga ?></td>
                            <td class="colAlign"><button type="submit" class="btn btn-sm btn-default" onClick="f_batal(this);" data-id='" + i +"' nama-pn='" + <?php $lampiran[$ii]['USERNAME_ENTRI']; ?> + "' nama-klg='" + <?php echo $lampiran[$ii]['NAMA']; ?> + "'>Hapus</button></td>
                          </tr>
                              <?php
                              $x++;
                            }
                          }

                           ?>

                    </tbody>
                    </table>
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

       initiateCariJenisInstansi();
       $("#CARI_INSTANSI").select2("data", { id: 2, text: "Some Text" });
       $('#CARI_INSTANSI').change(function(event) {
         console.log("param " + $(this).val());
           initiateSelect2CariInstansiTujuanNew($(this).val());
       });
       // initiateSelect2CariInstansiTujuan("<?php echo $surat[0]['ORG_TUJUANKD'] ?>");
       // initiateSelect2CariInstansiTujuan("<?php echo $surat[0]['ORG_KD'] ?>");
       initiateSelect2CariInstansiTujuanNew("<?php echo $surat[0]['ORG_KD'] ?>");
        $("#CARI_INSTANSI_TUJUAN").select2("data", { id: "<?php echo $surat[0]['ORG_TUJUANKD'] ?>", text: "<?php echo $surat[0]['ORG_NAMA'] ?>" });
        console.log($("#CARI_INSTANSI_TUJUAN").select2("val") + "test YAAA");
       initiateCariPenandatangan();
       var idx = $('#CARI_PENANDATANGAN').val();
       var nama = "<?php echo $surat[0]['PENANDATANGAN_NAMA'] ?>";
       $("#CARI_PENANDATANGAN").select2("data", { id: 2, text: "Some Text" });
       console.log($("#CARI_PENANDATANGAN").select2("val") + "test YO");

    });

    function initiateCariPenandatangan() {
      console.log("mulai lohh " + $('#CARI_PENANDATANGAN').val());
        // $("#CARI_PENANDATANGAN").remove();
        // $("#inpCariPenandatanganPlaceHolder").empty();
        // $("#inpCariPenandatanganPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[PENANDATANGAN]' style=\"border:none;padding:6px 0px;\" id='CARI_PENANDATANGAN' placeholder=\"-- Pilih Instansi --\">");
console.log("testttt " + $('#CARI_PENANDATANGAN').val());
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
                var id = "<?php echo $surat[0]['PENANDATANGAN_ID'] ?>";
                var nama = $('#CARI_PENANDATANGAN').val(); //get Penandatangan Nama
                // var id = '2';
                // var nama = "<?php echo $surat[0]['PENANDATANGAN_NAMA'] ?>";
                console.log("idnyaa lohh " + id + "äll around - " + nama);
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getPenandatangan') ?>/" + id + "/" + nama, {
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
        var id = $('#CARI_PENANDATANGAN').val();
var nama = "<?php echo $surat[0]['PENANDATANGAN_NAMA'] ?>";



        console.log("idnyaa lohh2 " + id + " end" + nama + " nameee");
        $.ajax({
            url: "<?php echo base_url('index.php/share/reff/getPenandatangan') ?>",
            dataType: "json",
            async: false

        }).done(function(data) {
            if (!isEmpty(data.item)) {
                cari_penandatangan_cfg.data = [{
                        id: data.item[0].id,
                        name: data.item[0].name
                    }];

                iins = data.item[0].id;
                console.log("WOI " + cari_penandatangan_cfg['id']);
                $('#CARI_PENANDATANGAN').select2(cari_penandatangan_cfg);

                if (iins != null) {
                    $("#CARI_PENANDATANGAN").val(iins).trigger("change");
                    // initiateSelect2CariInstansiTujuan(iins);
                }
            }
        });
        // $('#CARI_PENANDATANGAN').select2('data', )
    };
    function initiateCariJenisInstansi() {

        // $("#CARI_INSTANSI").remove();
        // $("#inpCariInstansiPlaceHolder").empty();
        // $("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;margin-top:7px;\" id='CARI_INSTANSI' value='' placeholder=\"-- Pilih Instansi --\">");

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
              var id = $('#CARI_INSTANSI').val(); //get INSTANSI Nama
              var nama = "<?php echo $surat[0]['ORG_KD'] ?>";
              console.log("TEST id zz " + id + ", Nama: " + nama);
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
                var nama = $('#CARI_INSTANSI').val(); //get INSTANSI Nama
                var id = "<?php echo $surat[0]['ORG_KD'] ?>";
                console.log("TEST id " + id + ", Nama: " + nama);
                $('#CARI_INSTANSI').select2(cari_instansi_cfg);

                // if (iins != null) {
                //     $("#CARI_INSTANSI").val(iins).trigger("change");
                //     console.log("masuk trigger cari instansi = " + iins);
                //     initiateSelect2CariInstansiTujuanNew(iins);
                // }

            }
        });

    };
    function initiateSelect2CariInstansiTujuan(TIPE_INSTANSI) {
// console.log("tipe instansi = " + TIPE_INSTANSI);
//         $("#CARI_INSTANSI_TUJUAN").remove();
//         $("#inpCariInstansiTujuanPlaceHolder").empty();

        var set_default_null = "PENCEGAHAN";
// $("#inpCariInstansiTujuanPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI_TUJUAN]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI_TUJUAN' value='' placeholder=\"-- Pilih Nama Instansi --\">");

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
                console.log("initSelection - instansi tujuan: " + INSTANSI_TUJUAN);
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
            console.log("next instansi tujuan: " + $('#CARI_INSTANSI_TUJUAN').val() + ", tipe instansi = " + TIPE_INSTANSI);
            $.ajax("<?php echo base_url('index.php/share/reff/getNamaInstansiTujuan') ?>/" + TIPE_INSTANSI + "/" + __INSTANSI_TUJUAN, {
                dataType: "json"
            }).success(function(data) {
  //                    console.log(data, data.item, !isEmpty(data.item));
                if (!isEmpty(data.item)) {
                    cari_unit_kerja_cfg.data = [{
                            id: data.item[0].id,
                            name: data.item[0].name
                        }];

                    dsuk = data.item[0].id;

  //                   $('#CARI_INSTANSI_TUJUAN').select2(cari_unit_kerja_cfg).on("change", function(e) {
  // gtblDaftarIndividual.fnClearTable(0);
  //       gtblDaftarIndividual.fnDraw();
  //       reloadTableDoubleTime(gtblDaftarIndividual);
  //                   });

                    if (dsuk != null) {
                        $("#CARI_INSTANSI_TUJUAN").val(dsuk).trigger("change");
                    }
                }

            });
        }
    };

    function initiateSelect2CariInstansiTujuanNew(TIPE_INSTANSI) {
        var cari_instansi_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {

                url: "<?php echo base_url('index.php/share/reff/getNamaInstansiTujuan'); ?>/" + TIPE_INSTANSI ,
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
              var id = $('#CARI_INSTANSI_TUJUAN').val(); //get INSTANSI Nama
              var nama = "<?php echo $surat[0]['ORG_TUJUANKD'] ?>";
              console.log("TEST id zz " + id + ", Nama: " + nama);
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getNamaInstansiTujuan') ?>/" + id, {
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
            url: "<?php echo base_url('index.php/share/reff/getNamaInstansiTujuan') ?>/" + TIPE_INSTANSI ,
            dataType: "json",
            async: false,
        }).done(function(data) {
            if (!isEmpty(data.item)) {
                cari_instansi_cfg.data = [{
                        id: data.item[0].id,
                        name: data.item[0].name
                    }];

                iins = data.item[0].id;
                var nama = $('#CARI_INSTANSI_TUJUAN').val(); //get INSTANSI Nama
                var id = "<?php echo $surat[0]['ORG_TUJUANKD'] ?>";
                console.log("TEST id " + id + ", Nama: " + nama);
                $('#CARI_INSTANSI_TUJUAN').select2(cari_instansi_cfg);

                // if (iins != null) {
                //     $("#CARI_INSTANSI").val(iins).trigger("change");
                //     console.log("masuk trigger cari instansi = " + iins);
                //     initiateSelect2CariInstansiTujuan(iins);
                // }
            }
        });

    };

    function f_batal(ele) {
        var id = $(ele).attr('data-id');
        $(ele).closest('tr').remove();
        count();
    };
    $('#btn-cari').click(function(e) { //wrapperFormAdd
      $('#loader_area').show();
      // $('#wrapperFormAdd').hide();
      // $('#table_surat').show();
      e.preventDefault();
      var idPemeriksa = '898125';
      // var url = "index.php/eaudit/Korespondensi/get_daftar_data_pn/484771";
      var existingST = $('#listST').val();
      var existingNamaPN = $('#listNamaPN').val();
      var nomorSuratTugas = $('#NomorSuratTugasInput').val();
      $('#listST').val($('#listST').val() + ',' + nomorSuratTugas);

      console.log("existingST" + existingST + "; existingNamaPN :" + existingNamaPN);
      console.log("nomor surat tugas" + nomorSuratTugas + "length: " + nomorSuratTugas.length);
      console.log("index " + existingST.includes(nomorSuratTugas));
      // if(!existingST.includes(nomorSuratTugas))
      // {
        // alert("udah ada woy!" + existingST.indexOf(nomorSuratTugas));

      var checkboxTermasukKeluarga = document.getElementById('checkboxKeluarga').checked;
      console.log("checkbox keluarga : " + checkboxTermasukKeluarga);
      console.log("value nama PN " + $('#NamaPN').val());
      var url = "";
      if ($('#NamaPN').val().length > 0 && $('#NomorSuratTugasInput').val().length == 0) {
        console.log("masuk by nama PN doang");
          url = "index.php/eaudit/Korespondensi/get_pn_by_name/" + idPemeriksa + '/' + $('#NamaPN').val() + '/' + checkboxTermasukKeluarga;
      }
      else if ($('#NomorSuratTugasInput').val().length > 0 && $('#NamaPN').val().length == 0) {
        console.log("masuk by ST doang");
        url = "index.php/eaudit/Korespondensi/get_pn_by_st/" + idPemeriksa + '/' + nomorSuratTugas + '/' + checkboxTermasukKeluarga;
      }
      else if ($('#NomorSuratTugasInput').val().length > 0 && $('#NamaPN').val().length > 0) {
        console.log("masuk by nama PN dan ST");
        url = "index.php/eaudit/Korespondensi/get_pn_by_st_name/" + idPemeriksa + '/' + $('#NamaPN').val() + '/' + nomorSuratTugas + '/' +checkboxTermasukKeluarga;
      }
      else {
        console.log("masuk search by else");
      }

      // var url = "index.php/eaudit/Korespondensi/get_pn_by_st/" + nomorSuratTugas + '/' + checkboxTermasukKeluarga;
      var rowCount = $('#table_PN tr').length;
      console.log(rowCount + " jumlah row");

      $.ajax({
         type: "GET",
         url: url,
         data: [],
         dataType: 'json',
         success: function(data)
         {
           var ii = 1;
           var status = "";
           var className = "";
           var agenda = "";
           var agendaTemp = "";
           var tglKirimFinal = "";
           var tahunKirimFinal = "";
           // $('#test123').append("<table class=\"table table-striped table-hover table-heading no-border-bottom\"><thead><tr><th>No</th><th style='width:250px'>Nama PN / No. Agenda</th><th style='width:250px'>Nama Anggota Keluarga</th><th style='width:300px'>Jabatan</th><th>Lembaga</th><th>Aksi</th></tr></thead>");

           //get all list PN
           var allPN = "";
           var hasilSearch = "";
           for (var i = 0; i < data.length; i++) {
             if(!hasilSearch.includes(data[i]["USERNAME_ENTRI"]))
             {
               console.log("masuk sini");
               hasilSearch = data[i]["USERNAME_ENTRI"] + ',' + hasilSearch;
            }
           }
            allPN = $('#listNamaPN').val();
           console.log("hasilSearch : " + hasilSearch);
           console.log("allPN : " + allPN);
           // console.log(checkPNValid(hasilSearch));
           if(checkPNValid(hasilSearch)) {
             $('#loader_area').hide();
             for (var i = 0; i < data.length; i++) {
               // console.log("test dta" + data[i]["NAMA"]);
               // console.log(data[i]["HUBUNGAN"]);

               if(data[i]["HUBUNGAN"] == "1")
               {
                 status = (data[i]["HUBUNGAN"] == "1") ? "Istri" : "Suami" ;
                 className = "normal";
               }
               else if(data[i]["HUBUNGAN"] == "3")
               {
                 status = "Anak Tanggungan";
                 className = "normal";
               }
               else if(data[i]["HUBUNGAN"] == "4")
               {
                 status = "Anak Bukan Tanggungan";
                 className = "normal";
               }
               else {
                 status = "Penyelenggara Negara";
                 className = "labelPN";
               }
               tglKirimFinal = data[i]["tgl_kirim_final"];
               tahunKirimFinal = tglKirimFinal.split('-')[0];
               // console.log("tahun lapor " + tglKirimFinal + " " + tahunKirimFinal);
               agenda = tahunKirimFinal + '/' + (data[i]["JENIS_LAPORAN"] == '4' ? 'R' : 'K') + '/' + data[i]["NIK"] + '/' + data[i]["ID_LHKPN"];
               if(data[i]["NIK"] == null) {
                 data[i]["NIK"] = '  ';
                 agenda = agendaTemp;
               }
               else {
                 agendaTemp = tahunKirimFinal + '/' + (data[i]["JENIS_LAPORAN"] == '4' ? 'R' : 'K') + '/' + data[i]["NIK"] + '/' + data[i]["ID_LHKPN"];
               }



              var jabatan = (data[i]["INST_NAMA"] == null) ? "  " : data[i]["INST_NAMA"] ;
              var lembaga = (data[i]["DESKRIPSI_JABATAN"] == null) ? "  " : data[i]["DESKRIPSI_JABATAN"] ;
              rowCount = ' ';
              $('#table_PN tbody').append("<tr id=\"row" + data[i]["id_lhkpn"] + "\">" +
              "<td class=\"colAlign\">" + rowCount + "<input type=\"hidden\" name=\"idLHKPN\" value=\"" + data[i]["id_lhkpn"] + "\"" + ">" +
              "<input type=\"hidden\" name=\"idKeluarga[]\" value=\"" + data[i]["id_lhkpn"] + ',' + data[i]["ID_KELUARGA"] + ',' + data[i]["USERNAME_ENTRI"] + "\"" + ">" + "</td>" +
              "<td style=\"width:200px\"><label class=\"labelNamaPN\">" + data[i]["USERNAME_ENTRI"] + "</label> <br> " + agenda + "</td>" +
              "<td style=\"width:200px\"><label class=\"labelNamaPN\">" + data[i]["NAMA"] + "</label> <br> <label class=" + className + ">" + status + " </label></td>" +
              "<td style=\"width:200px\" class=\"colAlign\"><label>" + jabatan + "</label> <br>  </td>" +
              "<td style=\"width:200px\" class=\"colAlign\"><label>" + lembaga + "</label> <br>  </td>" +
              // "<td class=\"colAlign\"><button type=\"submit\" class=\"btn btn-sm btn-default\" onClick=\"f_batal(this);\" data-id=\"+ i +\">Hapus</button></td>" +
              "<td class=\"colAlign\"><button type=\"submit\" class=\"btn btn-sm btn-default\" onClick=\"f_batal(this);\" data-id='" + i +"' nama-pn='" + data[i]["USERNAME_ENTRI"] + "' nama-klg='" + data[i]["NAMA"] + "'>Hapus</button></td>" +
              "</tr>"
              );

              ii++;
              rowCount++;
              $('#listNamaPN').val($('#listNamaPN').val() + ',' + data[i]["USERNAME_ENTRI"]);
            };
          }
          else {
            $('#loader_area').hide();
            // alert("Data PN telah dimasukkan");
            console.log("PN is existed");
            var namaPN = $('#listNamaPN').val().split(',');
            console.log(namaPN);
            // $('#listNamaPN').val($('#listNamaPN').val().replace(namaPN, ''));
          }
         },
         error: function(j,t,e)
         {
           console.log(j);
           console.log(t);
           console.log(e);
         }
       });
     // }
     // else{
     //   console.log("ST sudah ada");
     // }
    });


function checkPNValid($hasilSearch)
{
  var tempHasilSearch = $hasilSearch.replace(',', '');
  var tempPN = $('#listNamaPN').val().replace(',', '');
  console.log("listNamaPN: "+ $('#listNamaPN').val());
  console.log("hasilSearch: " + $hasilSearch);
  console.log("tempHasilSearch: " + tempHasilSearch);
  console.log("tempPN: " + tempPN);
  if($('#table_PN tr').length > 1){
    if(tempPN.includes(tempHasilSearch)) {
      return false;
    }
    else {
      return true;
    }
  }
  else {
    return true;
  }
  // if($('#listNamaPN').val().includes($hasilSearch)) {
  //   return false;
  // }
  // else {
  //   return true;
  // }

}
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
