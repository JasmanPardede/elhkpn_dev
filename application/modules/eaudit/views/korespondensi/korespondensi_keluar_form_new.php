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
 </style>
<div id="wrapperFormAdd">

<!-- Tambah Data -->

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
                            <?php
                              if($surat[0]['ID_SURAT_KELUAR'] == '')
                              { ?>
                                <input disabled class="form-control date-picker" type='text' name='TGL_SURAT' id='TGL_SURAT' placeholder='DD/MM/YYYY' value="">
                            <?php
                              }
                              else {
                                ?>
                                <?php
                                    if($surat[0]['SURAT_STATUS'] != "") {
                                ?>
                                <input readonly class="form-control date-picker" type='text' name='TGL_SURAT' id='TGL_SURAT' placeholder='DD/MM/YYYY' value="<?php echo $surat[0]['SURAT_TANGGAL'] ?>">
                                <?php
                              } else {
                                ?>
                                <input class="form-control date-picker" type='text' name='TGL_SURAT' id='TGL_SURAT' placeholder='DD/MM/YYYY' value="<?php echo $surat[0]['SURAT_TANGGAL'] ?>">
                                <?php
                              }
                                 ?>
                            <?php
                              }
                             ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label labelKorespondensi">Nomor</label>
                        <div class="col-sm-7">
                            <?php
                              if($surat[0]['ID_SURAT_KELUAR'] == '')
                              { ?>
                                <input disabled class="form-control" type='text' maxlength="40" size='40' name='NomorSurat' id='NomorSurat' placeholder="Nomor Surat" onblur="cek_user(this.value)" value="">
                            <?php
                              }
                              else {
                                ?>
                                <?php
                                    if($surat[0]['SURAT_STATUS'] != "") {
                                      if($surat[0]['SURAT_NOMOR'] != null) {
                                ?>
                                        <input readonly class="form-control" type='text' maxlength="40" size='40' name='NomorSurat' id='NomorSurat' placeholder="Nomor Surat" onblur="cek_user(this.value)" value="<?php echo $surat[0]['SURAT_NOMOR'] ?>">
                                <?php
                                      } else {
                                        $formatTanggal = explode('-', (date('Y-m-d')));
                                        $tahun = $formatTanggal[0];
                                        $bulan = $formatTanggal[1];
                                        $noSurat = 'R/    /LHK.02/10-12/'.$bulan.'/'.$tahun;
                                ?>
                                        <input readonly class="form-control" type='text' maxlength="40" size='40' name='NomorSurat' id='NomorSurat' placeholder="" onblur="cek_user(this.value)" value="<?php echo $noSurat ?>">
                                        <?php
                                      }
                                    }
                                      else {
                                        if($surat[0]['SURAT_NOMOR'] != null) {
                                          ?>
                                        <input class="form-control" type='text' maxlength="40" size='40' name='NomorSurat' id='NomorSurat' placeholder="NomorSurat" onblur="cek_user(this.value)" value="<?php echo $surat[0]['SURAT_NOMOR'] ?>">
                                <?php
                                        }
                                        else {
                                          $formatTanggal = explode('-', (date('Y-m-d')));
                                          $tahun = $formatTanggal[0];
                                          $bulan = $formatTanggal[1];
                                          $noSurat = 'R/    /LHK.02/10-12/'.$bulan.'/'.$tahun;
                                          ?>
                                          <input class="form-control" type='text' maxlength="40" size='40' name='NomorSurat' id='NomorSurat' placeholder="" onblur="cek_user(this.value)" value="<?php echo $noSurat ?>">
                                          <?php
                                        }
                                      }
                                 ?>
                            <?php
                              }
                             ?>
                            <span class="help-block"><font id='username_ada' style='display:none;' color='red'>User PN dengan NIK <span id="check_uname_add" style="font-style: italic; font-weight: bold;"></span> sudah terdaftar</font></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label labelKorespondensi">Kontak #1<span class="red-label">*</span></label>
                        <div id="inpCariKontak1PlaceHolder" class="col-sm-7">
                          <?php
                              if($surat[0]['SURAT_STATUS'] != "") {
                          ?>
                          <input disabled type='text' class="input-sm form-control" name='CARI[KONTAK1]' style="border:none;padding:6px 0px;" id='CARI_KONTAK1' value="<?php echo $surat[0]['SURAT_KONTAK'] ?>" placeholder="-- Pilih Kontak #1 --">
                          <?php
                              } else {
                          ?>
                          <input required type='text' class="input-sm form-control" name='CARI[KONTAK1]' style="border:none;padding:6px 0px;" id='CARI_KONTAK1' value="<?php echo $surat[0]['SURAT_KONTAK'] ?>" placeholder="-- Pilih Kontak #1 --">
                          <?php
                              }
                           ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label labelKorespondensi">Kontak #2<span class="red-label">*</span></label>
                        <div id="inpCariKontak2PlaceHolder" class="col-sm-7">
                          <?php
                              if($surat[0]['SURAT_STATUS'] != "") {
                          ?>
                          <input readonly type='text' class="input-sm form-control" name='CARI[KONTAK2]' style="border:none;padding:6px 0px;" id='CARI_KONTAK2' value="<?php echo $surat[0]['SURAT_KONTAK_2'] ?>" placeholder="-- Pilih Kontak #2 --">
                          <?php
                              } else {
                          ?>

                          <input required type='text' class="input-sm form-control" name='CARI[KONTAK2]' style="border:none;padding:6px 0px;" id='CARI_KONTAK2' value="<?php echo $surat[0]['SURAT_KONTAK_2'] ?>" placeholder="-- Pilih Kontak #2 --">
                          <?php
                              }
                           ?>
                        </div>
                    </div>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label labelKorespondensi">Sifat <span class="red-label">*</span></label>
                        <div class="col-sm-7">
                          <?php
                              if($surat[0]['SURAT_STATUS'] != "") {
                          ?>
                          <input readonly required class="form-control" type='text' size='40' name='Sifat' id='Sifat' placeholder="Sifat" value="<?php echo $surat[0]['SURAT_SIFAT'] ?>">
                          <?php
                        } else {
                          ?>
                          <!-- <input required class="form-control" type='text' size='40' name='Sifat' id='Sifat' placeholder="Sifat" value="<?php echo $surat[0]['SURAT_SIFAT'] ?>"> -->
                          <select required class="form-control" name='Sifat' id='Sifat'>
                              <option value="" disabled selected>--- Pilih Status ---</option>
                              <option value="Biasa">Biasa</option>
                              <option value="Rahasia">Rahasia</option>
                              <option value="Sangat Rahasia">Sangat Rahasia</option>
                            </select>
                          <?php
                        }
                           ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label labelKorespondensi">Lampiran <span class="red-label">*</span></label>
                        <div class="col-sm-7">
                          <?php
                              if($surat[0]['SURAT_STATUS'] != "") {
                          ?>
                          <input readonly required class="form-control" type='text' size='40' name='Lampiran' id='Lampiran' placeholder="Lampiran" value="<?php echo $surat[0]['SURAT_LAMPIRAN'] ?>">
                          <?php
                        } else {
                          ?>
                          <input required class="form-control" type='text' size='40' name='Lampiran' id='Lampiran' placeholder="Lampiran" value="<?php echo $surat[0]['SURAT_LAMPIRAN'] ?>">
                          <?php
                        }
                           ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label labelKorespondensi">Hal <span class="red-label">*</span></label>
                        <div class="col-sm-7">
                            <?php
                              if($surat[0]['ID_SURAT_KELUAR'] == '')
                              { ?>
                                <input required class="form-control" type='text' size='40' name='Hal' id='Hal' placeholder="Hal" value="Permintaan Data">
                            <?php
                              }
                              else {
                                ?>
                                <?php
                                    if($surat[0]['SURAT_STATUS'] != "") {
                                ?>
                                <input readonly required class="form-control" type='text' size='40' name='Hal' id='Hal' placeholder="Hal" value="<?php echo $surat[0]['SURAT_HAL'] ?>">
                                <?php
                              } else {
                                ?>
                                <input required class="form-control" type='text' size='40' name='Hal' id='Hal' placeholder="Hal" value="<?php echo $surat[0]['SURAT_HAL'] ?>">
                                <?php
                              }
                                 ?>
                            <?php
                              }
                             ?>
                        </div>
                    </div>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="row">
                    <div class="form-group">
                        <label class="col-sm-4 control-label labelKorespondensi">Penandatangan <span class="red-label">*</span></label>
                        <div id="inpCariPenandatanganPlaceHolder" class="col-sm-7">
                          <?php
                              if($surat[0]['SURAT_STATUS'] != "") {
                          ?>

                            <input readonly type="text" class="form-control" name="" value="<?php echo $surat[0]['PENANDATANGAN_NAMA'];?>">


                          <?php
                        } else {
                          ?>
                          <!-- <input required type='text' class="input-sm form-control" name='CARI[PENANDATANGAN]' style="border:none;padding:6px 0px;" id='CARI_PENANDATANGAN' value="<?php echo $surat[0]['PENANDATANGAN_NAMA'] ?>" placeholder="-- Pilih Penandatangan --"> -->
                          <input required type='text' class="input-sm form-control" name='CARI[PENANDATANGAN]' style="border:none;padding:6px 0px;" id='CARI_PENANDATANGAN' value="<?php echo $surat[0]['SURAT_PENANDATANGAN_ID'] ?>" placeholder="-- Pilih Penandatangan --">
                          <?php
                        }
                           ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label labelKorespondensi">Jenis Instansi Tujuan<span class="red-label">*</span></label>
                        <div id="inpCariInstansiPlaceHolder" class="col-sm-7">
                          <?php
                              if($surat[0]['SURAT_STATUS'] != "") {
                          ?>
                          <!-- <input readonly type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI' value="<?php echo $surat[0]['ORG_KD'] ?>" placeholder="-- Pilih Jenis Instansi --"> -->
                          <input readonly type="text" class="form-control" name="" value="<?php echo $surat[0]['ORG_TIPE'];?>">
                          <?php
                              } else {
                          ?>
                          <input required type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI' value="<?php echo $surat[0]['SURAT_TEMPLATE_ID'] ?>" placeholder="-- Pilih Jenis Instansi --">
                          <?php
                              }
                           ?>
                        </div>
                    </div>
                      <div class="form-group">
                          <label class="col-sm-4 control-label labelKorespondensi">Nama Instansi Tujuan<span class="red-label">*</span></label>
                          <div id="inpCariInstansiTujuanPlaceHolder" class="col-sm-7">
                            <?php
                                if($surat[0]['SURAT_STATUS'] != "") {
                            ?>
                            <!-- <input readonly type='text' class="input-sm form-control" name='CARI[INSTANSI_TUJUAN]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI_TUJUAN' value="<?php echo $surat[0]['ORG_TUJUANKD'] ?>" tipe-inst="<?php echo $surat[0]['ORG_KD'] ?>" placeholder="-- Pilih Nama Instansi --"> -->
                            <input readonly type="text" class="form-control" name="" value="<?php echo $surat[0]['ORG_NAMA'];?>">
                            <?php
                          } else {
                            ?>
                            <input required type='text' class="input-sm form-control" name='CARI[INSTANSI_TUJUAN]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI_TUJUAN' value="<?php echo $surat[0]['SURAT_INSTANSI_ID'] ?>" tipe-inst="<?php echo $surat[0]['SURAT_TEMPLATE_ID'] ?>" placeholder="-- Pilih Nama Instansi --">
                            <?php
                          }
                             ?>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
            <div class="row" style="border-top-color: #848588;border-top-style: solid;border-top-width: 2px;border-radius: 3px;">
              <?php
                  if($surat[0]['SURAT_STATUS'] == "") {
              ?>
              <form class='form-horizontal' id="ajaxFormCari">
              <div class="col-md-4 actionPN">
                <div class="form-group">
                    <label class="col-sm-3 labelKorespondensi">Nomor Surat Tugas</label>
                    <div id="inpCariNomorSuratTugasPlaceHolder" class="col-sm-7">
                        <input type='text' class="input-sm form-control" name='CARI[NOMORSURATTUGAS]' style="border:none;padding:6px 0px;" id='CARI_NOMOR_SURAT_TUGAS' placeholder="-- Pilih Nomor Surat Tugas --">
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
                        <input type="checkbox" name="IDENTIFIER[]" value="WITH_FAM" id="checkboxKeluarga">
                    </div>
                    <div class="form-group">
                        <div class="col-col-sm-3 col-sm-offset-4-2" style="padding-top: 10px;">
                            <button type="submit" class="btn btn-sm btn-default" id="btn-cari" >Cari PN</button>
                            <button type="reset" id="btn-clear" class="btn btn-sm btn-default"> Clear</button>
                        </div>
                    </div>
                </div>
              </div>
            </form>
            <?php } ?>
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
                            <?php
                                if($surat[0]['SURAT_STATUS'] == "") {
                            ?>
                            <th>Aksi</th>
                          <?php } else {?>
                            <th></th>
                          <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
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
            <?php
                if($surat[0]['SURAT_STATUS'] == "") {
            ?>
                  <input class="btn btn-sm btn-primary" type="submit" id="btnSimpanSuratKeluar" value="Simpan"></input>
             <?php } ?>

            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox2();">
          </div>
          <div class="clearfix"></div>
        </div>
    </div>
<!-- End Tambah Data -->
  </div>
  </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {

      var idPemeriksa = "<?php echo $id_user ?>";

      <?php if ($surat != null): ?>
        var id_surat = "<?php echo $surat[0]['ID_SURAT_KELUAR'] ?>";
        var id_penandatangan = "<?php echo $surat[0]['SURAT_PENANDATANGAN_ID'] ?>";
        var jenis_instansi = "<?php echo $surat[0]['SURAT_TEMPLATE_ID'] ?>";
        var nama_instansi = "<?php echo $surat[0]['SURAT_INSTANSI_ID'] ?>";
      <?php else: ?>
        var jenis_instansi = "";
        var nama_instansi = "";
        var id_surat = "";
      <?php endif ?>
      $('#btnSimpanSuratKeluar').click(function(e) {
        e.preventDefault();
        var kontak1 = $('#CARI_KONTAK1').val();
        var kontak2 = $('#CARI_KONTAK2').val();
        var sifat = $('#Sifat').val();
        var lampiran = $('#Lampiran').val();
        var hal = $('#Hal').val();
        var penandatangan = $('#CARI_PENANDATANGAN').val();
        var jenisInstansi = $('#CARI_INSTANSI').val();
        var namaInstansi = $('#CARI_INSTANSI_TUJUAN').val();
        // var namaInstansi = $('#CARI_INSTANSI_TUJUAN').; //get text




        var valid = true;
        if(kontak1 == null)
          valid = false;
        if(kontak2 == null)
          valid = false;
        if(sifat == "")
          valid = false;
        if(lampiran == "")
          valid = false;
        if(hal == "")
          valid = false;
        if(penandatangan == null)
          valid = false;
        if(jenisInstansi == null)
          valid = false;
        if(namaInstansi == null || namaInstansi == '')
          valid = false;

        if (jenisInstansi != jenis_instansi)
        {
          if (nama_instansi == namaInstansi)
            valid = false;
        }

        if(id_surat != "")
        {
          urls = "index.php/eaudit/Korespondensi/save_korespondensi_keluar/" + id_surat + "/" + penandatangan;
        }
        else {
          urls = "index.php/eaudit/Korespondensi/save_korespondensi_keluar/";
        }
        var url = urls;

        if(valid) {
        var a = $('#formSuratKeluar').serialize();
            $.ajax({
               type: "POST",
               url: urls,
               data: a,
               success: function()
               {
                 $('#loader_area').hide();
                   CloseModalBox2();
                   alert("Data berhasil disimpan.");
                   $('#table-surat').DataTable().ajax.reload();
               }
             });
        }
        else {
          // alert("Data tidak valid.");
        }
      });

     initiateCariKontak();
     initiateCariKontak2();
     initiateCariJenisInstansi();
     // var id_instansi = "<?php echo $surat[0]['SURAT_INSTANSI_ID'] ?>";
      var id_instansi = "<?php echo $surat[0]['SURAT_TEMPLATE_ID'] ?>";
     if(id_instansi != "") {
       $("#CARI_INSTANSI").select2("data", { id: id_instansi, text: "Some Text" });
     }
     $('#CARI_INSTANSI').change(function(event) {
         initiateSelect2CariInstansiTujuan($(this).val());
     });

     var id_instansi_tujuan = "<?php echo $surat[0]['SURAT_INSTANSI_ID'] ?>";
     if(id_instansi_tujuan != ''){
       initiateSelect2CariInstansiTujuan(id_instansi_tujuan);
    }
    else {
      initiateSelect2CariInstansiTujuan();
    }
    initiateCariPenandatangan();
     var id_penandatangan = "<?php echo $surat[0]['SURAT_PENANDATANGAN_ID'] ?>";
     if(id_penandatangan != "") {
       $("#CARI_PENANDATANGAN").select2("data", { id: id_penandatangan, text: "Some Text" });
     }
     initiateCariNomorSuratTugas(idPemeriksa);
     <?php if ($surat != null): ?>
       displayLampiranPN(id_surat);
     <?php endif ?>
    });

    var sifat_surat = "<?php echo $surat[0]['SURAT_SIFAT'] ?>";
    if(sifat_surat != "") {
      $("#Sifat").val(sifat_surat);
    }

    $('#btn-cari').click(function(e) {
      $('#loader_area').show();
      e.preventDefault();

      var idPemeriksa = "<?php echo $id_user ?>";
      var existingST = $('#listST').val();
      var existingNamaPN = $('#listNamaPN').val();
      var nomorSuratTugas = $('#CARI_NOMOR_SURAT_TUGAS').val();
      var nama = $('#NamaPN').val();
      $('#listST').val($('#listST').val() + ',' + nomorSuratTugas);
      var checkboxTermasukKeluarga = document.getElementById('checkboxKeluarga').checked;
      var url = "";
      if ($('#NamaPN').val().length > 0 && nomorSuratTugas.length == 0) {
          url = "index.php/eaudit/Korespondensi/get_pn_by_name/"
      }
      else if (nomorSuratTugas.length > 0 && $('#NamaPN').val().length == 0) {
        url = "index.php/eaudit/Korespondensi/get_pn_by_st/";
      }
      else if (nomorSuratTugas.length > 0 && $('#NamaPN').val().length > 0) {
        url = "index.php/eaudit/Korespondensi/get_pn_by_st_name/"
      }



      var rowCount = $('#table_PN tr').length;

      var data = {
        idPemeriksa : idPemeriksa,
        nama : nama,
        nomorSuratTugas : nomorSuratTugas,
        checkbox : checkboxTermasukKeluarga
      };
       $.post(
         url,
         data,
         function( data ) {
           drawTable(data);
        },
        'json',);
    });

    $('#btn-clear').click(function() {
      $("#CARI_NOMOR_SURAT_TUGAS").select2("val", "");
    });

    function displayLampiranPN(idSurat)
    {
      $.ajax({
         type: "GET",
         // url: "index.php/eaudit/Korespondensi/get_lampiran_data/" + idSurat,
         url: "index.php/eaudit/Korespondensi/get_lampiran_data_cetak/" + idSurat,
         data: [],
         dataType: 'json',
         success: function(data)
         {
           drawTable(data);
        },
        error: function(j,t,e)
        {
        }
       });
    }
    function drawTable(data)
    {
        var ii = 1;
        var status = "";
        var className = "";
        var agenda = "";
        var agendaTemp = "";
        var tglKirimFinal = "";
        var tahunKirimFinal = "";
        var nik = "";
        var idlhkpn = "";
        //get all list PN
        var allPN = "";
        var hasilSearch = "";
        for (var i = 0; i < data.length; i++) {
          if(!hasilSearch.includes(data[i]["USERNAME_ENTRI"]))
          {
            hasilSearch = data[i]["USERNAME_ENTRI"] + ',' + hasilSearch;
         }
        }
        allPN = $('#listNamaPN').val();

        if(checkPNValid(hasilSearch)) {
          $('#loader_area').hide();
          for (var i = 0; i < data.length; i++) {
            // var lembaga = (data[i]["INST_NAMA"] == null) ? "  " : data[i]["INST_NAMA"] ;
            // var jabatan = (data[i]["DESKRIPSI_JABATAN"] == null) ? "  " : data[i]["DESKRIPSI_JABATAN"] ;
            var lembaga = "";
            var jabatan = "";
            // var nama =  data[i]["NAMA"] == null ? data[i]['USERNAME_ENTRI'] : data[i]["NAMA"];
            // var namaTemp = "";
            // var nama = "";
            var nama =  data[i]["HUBUNGAN"] == null ? data[i]['NAMA'] : data[i]["NAMA_LENGKAP"];
            var namaPN = data[i]['NAMA'] == null ? data[i]['NAMA_LENGKAP'] : data[i]["NAMA"];
            if(data[i]["HUBUNGAN"] == "1")
            {
              status = "Istri";
              className = "normal";
              lembaga = "";
              jabatan = "";
            }
            else if (data[i]["HUBUNGAN"] == "2") {
              status = "Suami";
              className = "normal";
              lembaga = "";
              jabatan = "";
            }
            else if(data[i]["HUBUNGAN"] == "3")
            {
              status = "Anak Tanggungan";
              className = "normal";
              lembaga = "";
              jabatan = "";
            }
            else if(data[i]["HUBUNGAN"] == "4")
            {
              status = "Anak Bukan Tanggungan";
              className = "normal";
              lembaga = "";
              jabatan = "";
            }
            else {
              status = "Penyelenggara Negara";
              className = "labelPN";
              lembaga = (data[i]["INST_NAMA"] == null) ? "  " : data[i]["INST_NAMA"] ;
              jabatan = (data[i]["DESKRIPSI_JABATAN"] == null) ? "  " : data[i]["DESKRIPSI_JABATAN"] ;
              // nama = data[i]["NAMA"];
            }
            // if(data[i]["HUBUNGAN"] == null)
            // {
            //   namaTemp =  data[i]["NAMA"];
            //   nama = namaTemp;
            // }
            // else {
            //     nama = namaTemp;
            // }
            tglKirimFinal = data[i]["tgl_kirim_final"];
            if(tglKirimFinal != null)
                tahunKirimFinal = tglKirimFinal.split('-')[0];

            if(data.length > 1)
            {
              if(className == "normal")
                data[i]["NIK"] = data[i-1]["NIK"];
            }
            agenda = tahunKirimFinal + '/' + (data[i]["JENIS_LAPORAN"] == '4' ? 'R' : 'K') + '/' + data[i]["NIK"] + '/' + data[i]["ID_LHKPN"];

             // var nama = data[i]["NAMA"];
             if(nama == null) nama = namaPN;
             if(data[i]["NAMA"] == null) data[i]["NAMA"] = namaPN;
             rowCount = ' ';
             $('#table_PN tbody').append("<tr id=\"row" + data[i]["id_lhkpn"] + "\">" +
             "<td>" + rowCount + "<input type=\"hidden\" name=\"idLHKPN\" value=\"" + data[i]["ID_LHKPN"] + "\"" + ">" +
             "<input type=\"hidden\" name=\"idKeluarga[]\" value=\"" + data[i]["ID_LHKPN"] + ',' + data[i]["ID_KELUARGA"] + ',' + nama + "\"" + ">" + "</td>" +
             // "<input type=\"hidden\" name=\"idKeluarga[]\" value=\"" + data[i]["ID_LHKPN"] + ',' + data[i]["ID_KELUARGA"] + ',' + data[i]["USERNAME_ENTRI"] + "\"" + ">" + "</td>" +
             "<td style=\"width:200px\"><label class=\"labelNamaPN\">" + nama + "</label> <br> " + agenda + "</td>" +
             "<td style=\"width:200px\"><label class=\"labelNamaPN\">" + data[i]["NAMA"] + "</label> <br> <label class=" + className + ">" + status + " </label></td>" +
             "<td style=\"width:200px\" class=\"colAlign\"><label>" + jabatan + "</label> <br>  </td>" +
             "<td style=\"width:200px\" class=\"colAlign\"><label>" + lembaga + "</label> <br>  </td>" +
             <?php
                 if($surat[0]['SURAT_STATUS'] == "") {
             ?>
             "<td class=\"colAlign\"><button type=\"submit\" class=\"btn btn-sm btn-default\" onClick=\"f_batal(this);\" data-id='" + i +"' nama-pn='" + data[i]["USERNAME_ENTRI"] + "' nama-klg='" + data[i]["NAMA"] + "'>Hapus</button></td>" +
             <?php }
             else { ?>
               "<td></td>" +
               <?php } ?>
             "</tr>"
             );

             ii++;
             rowCount++;
             $('#listNamaPN').val($('#listNamaPN').val() + ',' + data[i]["USERNAME_ENTRI"]);
         };
     }
     else {
       $('#loader_area').hide();
       var namaPN = $('#listNamaPN').val().split(',');
     }
    }

    function checkPNValid($hasilSearch)
    {
      var tempHasilSearch = $hasilSearch.replace(',', '');
      var tempPN = $('#listNamaPN').val().replace(',', '');
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
    }

    function initiateCariNomorSuratTugas(idPemeriksa)
    {
      $("#CARI_NOMOR_SURAT_TUGAS").remove();
      $("#inpCariNomorSuratTugasPlaceHolder").empty();
      $("#inpCariNomorSuratTugasPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[NOMORSURATTUGAS]' style=\"border:none;padding:6px 0px;\" id='CARI_NOMOR_SURAT_TUGAS' value='' placeholder=\"-- Pilih Nomor Surat Tugas --\">");
      var cari_nomor_surat_tugas_cfg = {
          minimumInputLength: 0,
          data: [],
          ajax: {
              url: "<?php echo base_url('index.php/eaudit/korespondensi/get_daftar_surat_tugas'); ?>/" + idPemeriksa,
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
          formatResult: function(state) {
              return state.name;
          },
          formatSelection: function(state) {
              return state.name;
          }
      };

      var iins = null;
      $.ajax({
          url: "<?php echo base_url().'index.php/eaudit/korespondensi/get_daftar_surat_tugas/' ?>" + idPemeriksa + "?q=",
          dataType: "json",
          async: false,
      }).done(function(data) {
          if (!isEmpty(data.item)) {
              cari_nomor_surat_tugas_cfg.data = [{
                      id: data.item[0].id,
                      name: data.item[0].name
                  }];

              iins = data.item[0].id;
              $('#CARI_NOMOR_SURAT_TUGAS').select2(cari_nomor_surat_tugas_cfg);
          }
      });

    };
    function initiateCariPenandatangan() {
        var cari_penandatangan_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/eaudit/korespondensi/getPenandatangan?q=') ?>",
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
                // var nama = $('#CARI_PENANDATANGAN').val();
                var id = "<?php echo $surat[0]['SURAT_PENANDATANGAN_ID'] ?>";
                if (id !== "") {
                    // $.ajax("<?php echo base_url('index.php/eaudit/korespondensi/getPenandatangan') ?>/" + id + "/" + nama, {
                      $.ajax("<?php echo base_url('index.php/eaudit/korespondensi/getPenandatangan') ?>/" + id, {
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
            url: "<?php echo base_url('index.php/eaudit/korespondensi/getPenandatangan?q=') ?>",
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
                }
            }
        });
    };
    function initiateCariJenisInstansi() {
        var cari_instansi_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/eaudit/korespondensi/getInstansiTujuan?q=') ?>",
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
                    $.ajax("<?php echo base_url('index.php/eaudit/korespondensi/getInstansiTujuan') ?>/" + id, {
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
            url: "<?php echo base_url('index.php/eaudit/korespondensi/getInstansiTujuan?q=') ?>",
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
    function initiateCariKontak()
    {
      var cari_kontak1_cfg = {
          minimumInputLength: 0,
          data: [],
          ajax: {
              url: "<?php echo base_url('index.php/eaudit/korespondensi/get_kontak?q=') ?>",
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
              var id = $('#CARI_KONTAK1').val();
              if (id !== "") {
                  $.ajax("<?php echo base_url('index.php/eaudit/korespondensi/get_selected_kontak/') ?>/" + id + "?q=", {
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
          url: "<?php echo base_url('index.php/eaudit/korespondensi/get_kontak?q=') ?>",
          dataType: "json",
          async: false,
      }).done(function(data) {
          if (!isEmpty(data.item)) {
              cari_kontak1_cfg.data = [{
                      id: data.item[0].id,
                      name: data.item[0].name
                  }];
              iins = data.item[0].id;
              $('#CARI_KONTAK1').select2(cari_kontak1_cfg);
          }
      });

      var id_kontak1 = "<?php echo $surat[0]['SURAT_KONTAK'] ?>";

      if(id_kontak1 != "") {
        $("#CARI_KONTAK1").select2("data", { id: id_kontak1, text: "Some Text" });
      }
    }

    function initiateCariKontak2()
    {
      var cari_kontak2_cfg = {
          minimumInputLength: 0,
          data: [],
          ajax: {
              url: "<?php echo base_url('index.php/eaudit/korespondensi/get_kontak?q=') ?>",
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
              var id = $('#CARI_KONTAK2').val();
              if (id !== "") {
                  $.ajax("<?php echo base_url('index.php/eaudit/korespondensi/get_selected_kontak/') ?>/" + id + "?q=", {
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
          url: "<?php echo base_url('index.php/eaudit/korespondensi/get_kontak?q=') ?>",
          dataType: "json",
          async: false,
      }).done(function(data) {
          if (!isEmpty(data.item)) {
              cari_kontak2_cfg.data = [{
                      id: data.item[0].id,
                      name: data.item[0].name
                  }];
              iins = data.item[0].id;
              $('#CARI_KONTAK2').select2(cari_kontak2_cfg);
          }
      });

      var id_kontak2 = "<?php echo $surat[0]['SURAT_KONTAK_2'] ?>";

      if(id_kontak2 != "") {
        $("#CARI_KONTAK2").select2("data", { id: id_kontak2, text: "Some Text" });
      }
    }
    function initiateSelect2CariInstansiTujuan(TIPE_INSTANSI) {
      var id_jenis_instansi = "<?php echo $surat[0]['SURAT_TEMPLATE_ID'] ?>";
      var id_nama_instansi = "<?php echo $surat[0]['SURAT_INSTANSI_ID'] ?>";
      var type = $('#CARI_INSTANSI_TUJUAN').attr('tipe-inst');
        var cari_unit_kerja_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                // url: "<?php echo base_url('index.php/eaudit/korespondensi/getNamaInstansiTujuan'); ?>/" + TIPE_INSTANSI ,
                // url: "<?php echo base_url('index.php/eaudit/korespondensi/getInstansiTujuan?q=') ?>",
                url: "<?php echo base_url('index.php/eaudit/korespondensi/getNamaInstansiTujuan') ?>/" + $('#CARI_INSTANSI').val(),
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
                var nama = $('#CARI_INSTANSI_TUJUAN').val();
                var tipe = $('#CARI_INSTANSI_TUJUAN').attr('tipe-inst');
                if (INSTANSI_TUJUAN !== "") {
                  // $.ajax("<?php echo base_url('index.php/eaudit/korespondensi/getNamaInstansiTujuan') ?>/" + tipe + "/" + nama, {
                  $.ajax("<?php echo base_url('index.php/eaudit/korespondensi/getNamaInstansiTujuan') ?>/" + $('#CARI_INSTANSI').val() + "/" + nama, {
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
            // TIPE_INSTANSI = $('#CARI_INSTANSI_TUJUAN').attr('tipe-inst');
            // $.ajax("<?php echo base_url('index.php/eaudit/korespondensi/getNamaInstansiTujuan?q=') ?>/" + TIPE_INSTANSI, {
            $.ajax("<?php echo base_url('index.php/eaudit/korespondensi/getNamaInstansiTujuan') ?>/" + TIPE_INSTANSI + "?q=", {
                dataType: "json"
            }).success(function(data) {
                if (!isEmpty(data.item)) {
                    cari_unit_kerja_cfg.data = [{
                            id: data.item[0].id,
                            name: data.item[0].name
                        }];
                    dsuk = data.item[0].id;
                    $('#CARI_INSTANSI_TUJUAN').select2(cari_unit_kerja_cfg);
                }
            });
        }
    };

    function f_batal(ele) {
        // ele.preventDefault();
        var namaKlg = $(ele).attr('nama-klg');
        var namaPN = $(ele).attr('nama-pn');
        $('#listNamaPN').val($('#listNamaPN').val().replace(namaPN, ''));
        var id = $(ele).attr('data-id');
        $(ele).closest('tr').remove();
    };


</script>
<script type="text/javascript">
	jQuery(document).ready(function() {
	    $('.date-picker').datepicker({
        autoclose: true,
	      format: 'dd/mm/yyyy'
	    })
	});
</script>
