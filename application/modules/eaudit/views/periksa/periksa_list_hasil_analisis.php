<style>
  table#table-hasil-analisis-pemeriksaan > tbody > tr,  table#table-hasil-analisis-pemeriksaan-with-underlying-transaksi > tbody > tr {
    background-color: #E7E6E6 !important;
  }

  table#table-hasil-analisis-pemeriksaan.dataTable thead > tr > th, table#table-hasil-analisis-pemeriksaan-with-underlying-transaksi.dataTable thead > tr > th {
    padding-right: 8px !important;
  }

  .modal {
    text-align: center;
    padding: 0!important;
    vertical-align: middle !important;
  }

  .modal:before {
    content: '';
    display: inline-block;
    height: 100%;
    vertical-align: middle;
    margin-right: -4px;
  }

  .modal-dialog {
    max-width: 70% !important;
    display: inline-block;
    text-align: left;
    vertical-align: middle;
  }

  #myModal .modal-body#modal-inner {
    height: 75vh;
    overflow-y: auto;
  }

  #modal-tambah-hasil-analisis > .modal-dialog {
    max-width: 65% !important;
  }

  @media only screen and (max-width : 768px) {
    .modal-dialog {
      max-width: 100% !important;
    }

    #modal-tambah-hasil-analisis > .modal-dialog {
      max-width: 100% !important;
    }
  }

  @media only screen and (max-width : 768px) {
    .modal-dialog {
      max-width: 100% !important;
    }

    #modal-tambah-hasil-analisis > .modal-dialog {
      max-width: 100% !important;
    }
  }

  .ck-editor__editable_inline {
    height: 250px;
  }

  #ajax-content {
      padding: 40px 20px 20px 20px;
  }

  table td.nilai_transaksi {
    text-align : right;
  }
</style>

<section class="content-header">
    <h1><i class="fa <?php echo $icon; ?>"></i> <?php echo $title; ?></h1>
    <?php echo $breadcrumb; ?>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box" style="padding: 20px;">
                <!-- FORM HASIL ANALISIS PEMERIKSAAN -->
                <form id="form-hasil-analisis-pemeriksaan">
                    <div class="form-group row" style="margin-bottom: 20px;">
                    <div class="col-xs-12">
                        <h4 style="margin-top: 0px"><strong><?= $nama_pn ? $nama_pn : '-'; ?></strong> (<?= $nik_pn ? $nik_pn : '-'; ?>) / <?= $deskripsi_jabatan ? $deskripsi_jabatan : '-'; ?> / <?= $inst_nama ? $inst_nama : '-'; ?></h4 style="margin-top: 0px">
                    </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12">
                            <h4><strong>Hasil Analisis</strong></h4>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12">
                            <a href="#modal-tambah-hasil-analisis" class="btn btn-primary" data-toggle="modal" data-backdrop="static" data-keyboard="false">Tambah</a>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                            <h4><strong>Terdapat Underlying Transaksi</strong></h4>
                            <table id="table-hasil-analisis-pemeriksaan-with-underlying-transaksi" class="table table-bordered table-hover table-heading no-border-bottom dataTable no-footer">
                                <thead>
                                <tr style="height: 38px;">
                                    <th width="30px">No</th>
                                    <th>Hasil Analisis</th>
                                    <th width="250">Nilai Transaksi</th>
                                    <th width="85">Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <h4><strong>Tidak/Belum Terdapat Underlying Transaksi</strong></h4>
                                <table id="table-hasil-analisis-pemeriksaan" class="table table-bordered table-hover table-heading no-border-bottom dataTable no-footer">
                                    <thead>
                                    <tr style="height: 38px;">
                                        <th width="30px">No</th>
                                        <th>Hasil Analisis</th>
                                        <th width="250">Nilai Transaksi</th>
                                        <th width="85">Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-xs-6 col-md-2" for="paparan_pimpinan">Paparan Pimpinan</label>
                        <div class="col-xs-6 col-md-10">
                            <input name="paparan_pimpinan" class="form-check-input" type="checkbox" value="1" <?= $is_paparan_pimpinan ? 'checked' : ''; ?> style="cursor: pointer;">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-xs-6 col-md-2" for="arahan_pimpinan" style="color: #333 !important;">Arahan Pimpinan</label>
                        <div class="col-xs-6 col-md-10">
                            <input name="arahan_pimpinan" class="form-check-input" type="checkbox" value="1" <?= $is_arahan_pimpinan ? 'checked' : ''; ?> style="cursor: pointer;">
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom: 15px;">
                        <div class="col-xs-12 col-md-offset-2 col-md-6">
                            <textarea name="keterangan_arahan_pimpinan" class="form-control" rows="8" style="resize: vertical;"><?= $keterangan_arahan_pimpinan ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-xs-12 col-md-2" for="keterangan">Keterangan</label>
                        <div class="col-xs-12 col-md-6">
                            <textarea name="keterangan" class="form-control" rows="8" style="resize: vertical;"><?= $keterangan_hasil_analisis ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-xs-12 col-md-2" for="keterangan">Dasar Pemeriksaan</label>
                        <div class="col-xs-12 col-md-6">
                            <textarea name="keterangan_dasar_pemeriksaan" class="form-control" rows="8" style="resize: vertical;"><?= $keterangan_dasar_pemeriksaan ?></textarea>
                        </div>  
                    </div>
                    <div class="form-group row">
                        <div class="modal-footer" id="modal-bottom"><button id="btn-simpan-hasil-analisis-pemeriksaan" class="btn btn-primary">Simpan</button><button class="btn btn-danger" id="btn-batal-hasil-analisis-pemeriksaan" data-dismiss="modal">Batal</button></div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>   
</section>

<!-- FORM DAN MODAL TAMBAH HASIL ANALISIS -->
<div class="modal fade" id="modal-tambah-hasil-analisis" tabindex="-1" role="dialog" aria-labelledby="modal-title-tambah-hasil-analisis">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modal-title-tambah-hasil-analisis">Tambah Hasil Analisis</h4>
      </div>
      <div class="modal-body">
        <form id="form-tambah-hasil-analisis" class="form-horizontal">
          <input type="hidden" name="id_hasil_analisis" value="">
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="row">
              <div class="col-xs-12">
                <label class="control-label col-xs-12 col-md-2" for="hasil_analisis">Hasil Analisis</label>
                <div class="col-xs-12 col-md-10">
                  <textarea id="hasil_analisis" name="hasil_analisis" class="form-control" rows="8" style="resize: vertical;"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-xs-12">
                <label class="col-xs-12 col-md-2 control-label">Nilai Transaksi</label>
                <div class="col-xs-12 col-md-4">
                  <input name="mata_uang" type="text" class="form-control"/>
                </div>
                <div class="col-xs-12 col-md-6">
                  <div class="input-group" style="display: table !important;">
                    <span id="mata_uang_simbol" class="input-group-addon">-</span>
                    <input type="text" class="form-control" name="nominal" placeholder="Nominal" autocomplete="off" style="text-align: right;"/>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-xs-12">
                <label class="col-xs-12 col-md-2 control-label">Terdapat Underlying Transaksi</label>
                <br>
                <div class="col-xs-12 col-md-4">
                  <input type="checkbox" name="underlying_transaksi" value="1" style="cursor: pointer;"/>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button id="btn-simpan-hasil-analisis" type="button" class="btn btn-primary" data-index="null">Simpan</button>
        <button id="btn-batal-hasil-analisis" type="button" class="btn btn-danger">Batal</button>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url(); ?>plugins/ckeditor/ckeditor.js?v=<?=$this->config->item('cke_version');?>"></script>
<script src="<?php echo base_url(); ?>plugins/ckeditor/adapters/jquery.js?v=<?=$this->config->item('cke_version');?>"></script>
<script src="<?php echo base_url(); ?>plugins/ckeditor/additional-setting.js?v=<?=$this->config->item('cke_version');?>"></script>

<script type="text/javascript">

  $(document).ready(function() {

    $('#table-hasil-analisis-pemeriksaan-with-underlying-transaksi').dataTable({
        "oLanguage": ecDtLang,
        'sPaginationType': 'full_numbers',
        'responsive': true,
        'bServerSide': true,
        'bProcessing': true,
        "bLengthChange": false,
        "bFilter": true,
        "bSort": false,
        "searching": false,
        "bAutoWidth": false,
        "aoColumns": [
            null,
            null,
            { "sClass":"nilai_transaksi" },
            null
            ],
        "columnDefs": [
        {
            "targets": 0, // your case first column
            "className": "text-center",
        },
        {
            "targets": 3,
            "className": "text-center",
        }],
        'fnServerData': function(sSource, aoData, fnCallback) {
            $.ajax({
                'dataType': 'json',
                'type': 'POST',
                'url': '<?php echo base_url(); ?>eaudit/Periksa/load_hasil_analisis/<?php echo $id_audit ?>/<?php echo true ?>',
                'data': aoData,
                'success': fnCallback
            });
        }

    });

    $('#table-hasil-analisis-pemeriksaan').dataTable({
        "oLanguage": ecDtLang,
        'sPaginationType': 'full_numbers',
        'responsive': true,
        'bServerSide': true,
        'bProcessing': true,
        "bLengthChange": false,
        "bFilter": true,
        "bSort": false,
        "searching": false,
        "bAutoWidth": false,
        "aoColumns": [
            null,
            null,
            { "sClass":"nilai_transaksi" },
            null
            ],
        "columnDefs": [
        {
            "targets": 0, // your case first column
            "className": "text-center",
        },
        {
            "targets": 3,
            "className": "text-center",
        }],
        'fnServerData': function(sSource, aoData, fnCallback) {
            $.ajax({
                'dataType': 'json',
                'type': 'POST',
                'url': '<?php echo base_url(); ?>eaudit/Periksa/load_hasil_analisis/<?php echo $id_audit ?>',
                'data': aoData,
                'success': fnCallback
            });
        }

    });

    var ckeditor_hasil_analisis = CKEDITOR.replace('hasil_analisis', { removePlugins: 'sourcearea' });

    ckeditor_hasil_analisis.on('change', function() {
      $('#form-tambah-hasil-analisis').bootstrapValidator('revalidateField', 'hasil_analisis');
    });
    
    $('#form-hasil-analisis-pemeriksaan').bootstrapValidator({
      fields: {
        arahan_pimpinan: {
          validators: {
            callback: {
              callback: function (value, validator, $field) {
                if ($field.is(":checked")) {
                  validator.updateStatus('keterangan_arahan_pimpinan', 'VALID');
                } else {
                  validator.updateStatus('keterangan_arahan_pimpinan', 'NOT_VALIDATED');
                }

                return true;
              }
            }
          }
        },
        keterangan_arahan_pimpinan: {
          validators: {
            notEmpty: {
              message: 'Keterangan Arahan Pimpinan wajib diisi jika Arahan Pimpinan di-tick.'
            }
          }
        }
      }
    })
    .on('error.form.bv', function(e) {
      alertify.error("Data Hasil Analisis Pemeriksaan tidak valid, mohon dicek kembali.");
    })
    .on('success.form.bv', function(e) {
      e.preventDefault();

      let $form = $(e.target);

      let data = {
        id_audit: <?= $id_audit ?>,
        id_lhkpn: <?= $id_lhkpn ?>,
        paparan_pimpinan: $form.find('[name="paparan_pimpinan"]').is(":checked") ? 1 : 0,
        arahan_pimpinan: $form.find('[name="arahan_pimpinan"]').is(":checked") ? 1 : 0,
        keterangan_arahan_pimpinan: $form.find('[name="keterangan_arahan_pimpinan"]').val(),
        keterangan: $form.find('[name="keterangan"]').val(),
        keterangan_dasar_pemeriksaan: $form.find('[name="keterangan_dasar_pemeriksaan"]').val(),
      }

      $.ajax({
        url: "<?= base_url("eaudit/periksa/update_hasil_analisis_pemeriksaan") ?>",
        method: 'POST',
        dataType: 'json',
        data: data,
        cache: false,
        beforeSend: function() {
          $('#loader_area').show();
        },
        success: function (response) {
          if (response.success) {
            url = 'index.php/eaudit/periksa/index/';
            $('#loader_area').show();
            ng.LoadAjaxContent(url);

            alertify.success(response.message);
          } else {
            alertify.error(response.message);
          }
        },
        complete: function(jqXHR, textStatus) {
          $('#loader_area').hide();
        },
        error: function(xhr, status, error) {
          alertify.error(xhr.status + ' : ' + xhr.statusText);
        }
      });
    });
    
    $('#form-hasil-analisis-pemeriksaan').find('[name="arahan_pimpinan"]').on('change', function(e) {
      let $form = $('#form-hasil-analisis-pemeriksaan');
      let $keterangan = $form.find('[name="keterangan_arahan_pimpinan"]');

      if (this.checked) {
        $keterangan.prop("disabled", false).focus();
      } else {
        $keterangan.prop("disabled", true).val("");
      }

      $form.bootstrapValidator('revalidateField', 'keterangan_arahan_pimpinan');
    }).trigger('change');

    $('#form-tambah-hasil-analisis').bootstrapValidator({
      excluded: [':disabled'],
      fields: {
        hasil_analisis: {
          validators: {
            callback: {
              callback: function(value, validator, $field) {
                let hasil_analisis = ckeditor_hasil_analisis.getData();
                let text = hasil_analisis.replace(/<[^>]*>/gi, '');
                
                return !(text.length === 0 || text === null || text.match(/^ *$/) !== null);
              },
              message: 'Keterangan wajib diisi.',
            }
          }
        },
        mata_uang: {
          validators: {
            notEmpty: {
              message: 'Mata Uang wajib diisi.'
            }
          }
        },
        nominal: {
          validators: {
            notEmpty: {
              message: 'Nominal wajib diisi.'
            },
            stringLength: {
              max: 22,
              message: 'Nominal harus kurang dari 17 digit.'
            },
            callback: {
              callback: function (value, validator, $field) {
                let first_character = value.substring(1, 0);
                let nominal = $field.maskMoney('unmasked')[0];

                if (value.search(/[0-9,.]/) < 0 || first_character.match(/[a-zA-Z,.]/)) {
                  return {
                    valid: false,
                    message: 'Nominal wajib diisi.'
                  }
                }

                if (!(nominal > 0.00)) {
                  return {
                    valid: false,
                    message: 'Nominal harus lebih besar dari 0.00'
                  }
                }

                return true;
              }
            }
          }
        }
      }
    })
    .on('error.form.bv', function(e) {
      alertify.error("Data Hasil Analisis tidak valid, mohon dicek kembali.", e);
    })
    .on('success.form.bv', function(e) {
      e.preventDefault();

      let $form = $(e.target);
      let hasil_analisis = ckeditor_hasil_analisis.getData();
      let mata_uang = $form.find('[name="mata_uang"]').select2('data');
      let nominal = $form.find('[name="nominal"]').maskMoney('unmasked')[0];
      let nominal_string = $form.find('[name="nominal"]').val();
      let is_checked_underlying_transksi = $("input:checkbox[name=underlying_transaksi]:checked").val();
      let underlying_transaksi = is_checked_underlying_transksi ? is_checked_underlying_transksi : '0';
      let id_hasil_analisis = $form.find('[name="id_hasil_analisis"]').val();

      let data_analisis = {
        id_audit: <?= $id_audit ?>,
        id_hasil_analisis: id_hasil_analisis,
        hasil_analisis: hasil_analisis,
        mata_uang: mata_uang,
        nominal: nominal,
        nominal_string: nominal_string,
        underlying_transaksi: underlying_transaksi
      }

      $.ajax({
        url: "<?= base_url("eaudit/periksa/save_hasil_analisis_pemeriksaan") ?>",
        method: 'POST',
        dataType: 'json',
        data: data_analisis,
        cache: false,
        beforeSend: function() {
          $('#loader_area').show();
        },
        success: function (response) {
          if (response.success) {
            CloseModalBox2();
            $('#table-hasil-analisis-pemeriksaan-with-underlying-transaksi').DataTable().ajax.reload();
            $('#table-hasil-analisis-pemeriksaan').DataTable().ajax.reload();
            alertify.success(response.message);
          } else {
            alertify.error(response.message);
          }
        },
        complete: function(jqXHR, textStatus) {
          $('#loader_area').hide();
        },
        error: function(xhr, status, error) {
          alertify.error(xhr.status + ' : ' + xhr.statusText);
        }
      });
      $("#modal-tambah-hasil-analisis").modal('hide');
    })
    .on('error.validator.bv', function(e, data) {
      data.element.data('bv.messages').find('.help-block[data-bv-for="' + data.field + '"]').hide().filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    
    $('#form-tambah-hasil-analisis').find('[name="nominal"]').maskMoney({
      thousands: '.',
      decimal: ',',
      allowZero: false
    })
    .on('change', function(e) {
      $('#form-tambah-hasil-analisis').bootstrapValidator('revalidateField', 'nominal');
    });

    $('#form-tambah-hasil-analisis').find('[name="mata_uang"]').select2({
      placeholder: "Mata Uang",
      minimumInputLength: 0,
      ajax: {
        url: "<?= base_url("eaudit/periksa/ajax_get_mata_uang") ?>",
        dataType: 'json',
        quietMillis: 250,
        data: function (term, page) {
          return { q: term };
        },
        results: function (data, page) {
          return { results: data };
        },
        cache: false
      },
      formatResult: function (state) {
        return state.singkatan;
      },
      formatSelection: function (state) {
        return state.singkatan;
      }
    })
    .on('change', function (e) {
      let data = $(e.target).select2('data');

      $('#mata_uang_simbol').text(data.simbol);
      $('#form-tambah-hasil-analisis').bootstrapValidator('revalidateField', 'mata_uang');
    }).end();
    
    $('#modal-tambah-hasil-analisis').on('shown.bs.modal', function (e) {
      let id_hasil_analisis = $(e.relatedTarget).data("id");
      let $form = $('#form-tambah-hasil-analisis');
      let $btn_simpan = $("#btn-simpan-hasil-analisis");
      let $modal_title = $("#modal-title-tambah-hasil-analisis");

      $form.trigger("reset");
      $form.data('bootstrapValidator').resetForm();
      ckeditor_hasil_analisis.focus();
      
      if (typeof id_hasil_analisis === 'undefined') {
        $modal_title.html("Tambah Hasil Analisis");
        $form.find('[name="id_hasil_analisis"]').val('');
        $btn_simpan.removeAttr("data-index").html('Simpan');
      } else {

        $.ajax({
          url: "<?= base_url("eaudit/periksa/get_hasil_analisis_by_id") ?>",
          method: 'POST',
          dataType: 'json',
          data: {id_hasil_analisis},
          cache: false,
          success: function (response) {
            if (response.success) {
                $.each(response.data, function( key, value ) {
                    ckeditor_hasil_analisis.setData(value.hasil_analisis);
                    $form.find('[name="mata_uang"]').select2("data", value.mata_uang).trigger("change");
                    $form.find('[name="nominal"]').val(value.nominal_string);
                    $form.find('[name="id_hasil_analisis"]').val(value.id);
                    
                    if(value.underlying_transaksi == '1'){
                        $('input:checkbox[name=underlying_transaksi]').prop('checked', true);
                    }

                    $form.bootstrapValidator('revalidateField', 'mata_uang');
                    $form.bootstrapValidator('revalidateField', 'nominal');     
                });
            } else {
              console.log(response.data);
            }
          },
        });

        $modal_title.html("Edit Hasil Analisis");
        $btn_simpan.html('Update');
      }
    })
    .on('hidden.bs.modal', function () {
      let $form = $('#form-tambah-hasil-analisis');
      let $btn_simpan = $("#btn-simpan-hasil-analisis");

      $btn_simpan.removeAttr("data-index");
      
      ckeditor_hasil_analisis.setData('');
      $form.find('[name="mata_uang"]').select2('val', '');
      $form.find('#mata_uang_simbol').text('-');
      $form.trigger("reset");
      $form.data('bootstrapValidator').resetForm();
    });

    $("#btn-batal-hasil-analisis").on('click', function () {
      $("#modal-tambah-hasil-analisis").modal('hide');
    });

    $("#btn-simpan-hasil-analisis").on('click', function () {
      $('#form-tambah-hasil-analisis').submit();
    });

    $("#btn-batal-hasil-analisis-pemeriksaan").on('click', function() {
        url = 'index.php/eaudit/periksa/index/';
        $('#loader_area').show();
        ng.LoadAjaxContent(url);

        return false;
    });

    $(document).off('click', ".btn-hapus-hasil-analisis");

    $(document).on('click', ".btn-hapus-hasil-analisis", function(e) {
      e.preventDefault();
      let id = $(this).data("id");

      confirm(`Apakah Anda yakin ingin menghapus Data Analisis ini ?`, function() {
        $.ajax({
          url: "<?= base_url("eaudit/periksa/hapus_data_hasil_analisis") ?>",
          method: 'POST',
          dataType: 'json',
          data: {id},
          cache: false,
          beforeSend: function() {
            $('#loader_area').show();
          },
          success: function (response) {
            if (response.success) {
                $('#table-hasil-analisis-pemeriksaan-with-underlying-transaksi').DataTable().ajax.reload();
                $('#table-hasil-analisis-pemeriksaan').DataTable().ajax.reload();
                alertify.success(response.message);
            } else {
              alertify.error(response.message);
            }
          },
          complete: function(jqXHR, textStatus) {
            $('#loader_area').hide();
          },
          error: function(xhr, status, error) {
            alertify.error(xhr.status + ' : ' + xhr.statusText);
          }
        });

      });
    });
  });
</script>
