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
 * @package Views/user
 */

$role_yang_diijinkan = !in_array($this->session->userdata('ID_ROLE'), $this->config->item('AKSES_ROLE_PAGE_KELOLA_ADMIN_INSTANSI')['filter_instansi_except']);

?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="panel panel-default">
    <div class="panel-heading"><strong>DAFTAR ADMIN INSTANSI</strong></div>
    </div>
    <?php echo $breadcrumb; ?>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
		<div class="col-md-12">
      <div class="panel panel-default">
          <div class="panel-body" >
            <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl; ?>">
                <div class="box-body">
                    <div class="col-md-5">
                        <div class="row">
							<?php // if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2): ?>
                            <?php //if ($this->session->userdata('ID_ROLE') !== 3 || $this->session->userdata('ID_ROLE') !== 4):
                                if ($role_yang_diijinkan) : ?>
                            <div class="form-group" style="margin-bottom:10px">
                                <label class="col-sm-4 control-label">Instansi:</label>
                                <div id="inpCariInstansiPlaceHolder" class="col-sm-6">
                                    <input type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI' value='' placeholder="-- Pilih Instasi --">
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="form-group" style="margin-bottom:10px">
                                <label class="col-sm-4 control-label">Status:</label>
                                <div id="inpCariStatusPlaceHolder" class="col-sm-6">
                                    <input type='text' class="input-sm form-control" name='CARI[STATUS]' style="border:none;padding:6px 0px;" id='CARI_STATUS' value='' placeholder="-- Pilih Status --">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Cari :</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control input-sm" placeholder="Username/Nama/Email" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="inp_dt_completeNEW_cari"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-4">
                                    <button type="button" id="btnCari" class="btn btn-sm btn-default" onclick="submitCari();"><i class="fa fa-search"></i></button>
                                    <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="clearPencarian();">Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="btn-cetak btn btn-default btn-sm btn-same" style="background-color: #34ac75;">
                        <span class="logo-mini">
                            <img style="width:20px;" src="<?php echo base_url(); ?>img/icon/excel.png" >
                        </span> Print to Excel 
                    </a>
               </div>
            </form>

        <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header with-border">
              <div class="col-md-2">
    <?php
              echo '<button class="btn btn-sm btn-primary" id="btn-add" href="index.php/ereg/admin_instansi/adduser"><i class="fa fa-plus"></i> Tambah Data</button>';
              ?>
              </div>
              </div>
				<div class="box-body table-responsive">
                <!-- <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->
                    <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                        <thead>
                            <tr>
                                <th align="center" width="2%">No</th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Last Login</th>
                                <th>Email</th>
                                <th>Instansi</th>
                                <th width="120px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.col -->
</div>
</div><!-- /.row -->
</section><!-- /.content -->

<script language="javascript">

	var gtblDaftarIndividual;
        var data_is_active;

	function cek_user(username) {
        var div = $('#div-username');
        var loading = $('#loading', div);
        $('img', div).hide();
        username = username.replace(/^\s+|\s+$/g, '');
        if (username == '') {
            $('#fail', div).show();
            return;
        }
        loading.show();
        var url = "index.php/ereg/admin_instansi/cek_user/" + username;
        $.post(url, function (data) {
            loading.hide();
            if (data == 'ada')
            {
                $('#fail', div).show();
                $("#username_ada").show();
                document.getElementById('USERNAMEadd').value = "";
            } else
            {
                $('#success', div).show();
                $("#username_ada").hide();
            }
            ;
        });
    }

	var initiateCariInstansi = function () {

	$("#CARI_INSTANSI").remove();
	$("#inpCariInstansiPlaceHolder").empty();
	<?php // if ($this->session->userdata('ID_ROLE')=="1" || $this->session->userdata('ID_ROLE')=="2"): ?>
        <?php //if ($this->session->userdata('ID_ROLE') !== "3" || $this->session->userdata('ID_ROLE') !== "4"): 
                if ($role_yang_diijinkan) : ?>
		$("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='3122' placeholder=\"KOMISI PEMBERANTASAN KORUPSI (KPK)\">");
	<?php else: ?>

		$("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='' placeholder=\"-- Pilih Instansi --\">");

	<?php endif; ?>

	var cari_instansi_cfg = {
		minimumInputLength: 0,
		data: [],
		ajax: {
			url: "<?php echo base_url('index.php/share/reff/getLembaga'); ?>",
			dataType: 'json',
			quietMillis: 250,
			data: function (term, page) {
				return {
					q: term
				};
			},
			results: function (data, page) {
				return {results: data.item};
			},
			cache: true
		},
		initSelection: function (element, callback) {
			var id = $('#CARI_INSTANSI').val();
			if (id !== "") {
				$.ajax("<?php echo base_url('index.php/share/reff/getLembaga'); ?>/" + id, {
					dataType: "json"
				}).done(function (data) {
					callback(data[0]);
				});
			}
		},
		formatResult: function (state) {
			return state.name;
		},
		formatSelection: function (state) {
			return state.name;
		}
	};

	var iins = null;
	$.ajax({
		url: "<?php echo base_url('index.php/share/reff/getLembaga'); ?>",
		dataType: "json",
		async: false,
	}).done(function (data) {
		if (!isEmpty(data.item)) {
			cari_instansi_cfg.data = [{
					id: data.item[0].id,
					name: data.item[0].name
				}];

			iins = data.item[0].id;

			$('#CARI_INSTANSI').select2(cari_instansi_cfg);

			if (iins != null) {
				$("#CARI_INSTANSI").val(iins).trigger("change");

			}
		}
	});

	};

    function cek_email(email, id) {
        var div = $('#div-email');
        var loading = $('#loading', div);
        $('img', div).hide();
        loading.show();
        var url = "index.php/ereg/all_user_instansi/cek_email/" + encodeURIComponent(email) + '/' + id;
        $.post(url, function (data) {
            loading.hide();
            if (data == '0') {
                $('#fail', div).show();
                $('#email_ada').hide();
                $('#email_salah').show();
            } else if (data == '1') {
                $('#fail', div).show();
                $('#email_ada').show();
                $('#email_salah').hide();
            } else {
                $('#success', div).show();
                $('#email_ada').hide();
                $('#email_salah').hide();
            }
        });
    }

    var btnEditOnClick = function (self) {
        url = $(self).attr('href');
        console.log(url);
        $('#loader_area').show();
        $.post(url, function (html) {
            $('#loader_area').hide();
            OpenModalBox('Edit Admin Instansi', html, '', 'standart');
        });
        return false;

    };

    var btnDeleteOnClick = function (self) {
        url = $(self).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Hapus Admin Instansi', html, '');
        });
        return false;
    };

    var btnActiveOnClick = function (self) {
        url = $(self).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Aktifkan Admin Instansi', html, 'standart');
        });
        return false;
    };

    var btnResetOnClick = function (self) {
        url = $(self).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Reset Password Admin Instansi', html, '');
        });
        return false;
    };

    var btnAktivasiOnClick = function (self) {
        url = $(self).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Kirim Ulang Aktivasi', html, '');
        });
        return false;
    };

    var tblDaftarInstansi = {
        tableId: 'dt_completeNEW',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarInstansi'},
        conf: {
			"cShowSearch": false,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "responsive": true,
            "bAutoWidth": false,
            "sAjaxSource": "<?php echo base_url('ereg/admin_instansi/load_data_index'); ?>",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {

                var passData = getRecordDtTbl(sSource, aoData, oSettings);
				passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI").val()});
				passData.push({"name": "CARI[TEXT]", "value": $("#inp_dt_completeNEW_cari").val()});
				passData.push({"name": "CARI[STATUS]", "value": $("#CARI_STATUS").val()});
                $.getJSON(sSource, passData, function (json) {
                    fnCallback(json);
                });
            },
            "aoColumns": [
                {"mDataProp": "NO_URUT", bSearchable: true},
                {"mDataProp": "USERNAME", bSearchable: true},
                {"mDataProp": "NAMA", bSearchable: true},
                {"mDataProp": function (source, type, val) {
                        var columnData = "";
                        if (source.LAST_LOGIN) {
                            var unixTime = source.LAST_LOGIN;
                            var date = new Date(unixTime * 1000);
                            columnData = moment(date).format('DD-MM-YYYY h:mm:ss');
                        } else {
                            columnData = "";
                        }
                        return  columnData;
                    },
                    bSearchable: true
                },
                {"mDataProp": "EMAIL", bSearchable: true},
                {"mDataProp": function (source, type, val) {
                        var columnData = "";

                        if (source.UK_ID) {
                            columnData = " <b>" + source.UK_NAMA + "</b>)";
                        }
                        if (source.INST_SATKERKD) {
                            columnData = "" + source.INST_NAMA + "";
                        }
                        return  columnData;
                    },
                    bSearchable: true
                },
                {"mDataProp": function (source, type, val) {
                        var is_active = $('#CARI_STATUS').val();
                        var btnEdit = "";

                        var btnEdit = '<button type="button" class="btn btn-success btn-sm btn-edit" href="index.php/ereg/admin_instansi/edituser/' + source.ID_USER + '" title="Edit" onclick="btnEditOnClick(this);"><i class="fa fa-pencil"></i></button>';

                        var btnDelete = '<button type="button" class="btn btn-danger btn-sm btn-delete" href="index.php/ereg/admin_instansi/deleteuser/' + source.ID_USER + '" title="Delete" onclick="btnDeleteOnClick(this);"><i class="fa fa-trash" style="color:white;"></i></button>';

                        var btnReset = '<button type="button" class="btn btn-sm btn-success btn-reset" style="background-color: gray; color: white;" href="index.php/ereg/admin_instansi/resetpassword/' + source.ID_USER + '" title="reset password" onclick="btnResetOnClick(this);"><i class="fa fa-reddit-square"style="color:white;"></i></button>';

                        var btnAktivasi = '<button type="button" class="btn btn-sm btn-warning btn-aktivasi" href="index.php/ereg/admin_instansi/kirimaktivasi/' + source.ID_USER + '" title="kirim ulang aktivasi" onclick="btnAktivasiOnClick(this);"><i class="fa fa-send"style="color:white;"></i></button>';

                        var btnActive = '<button type="button" class="btn btn-sm btn-dark btn-active" style="background-color: #ff6600;" href="index.php/ereg/admin_instansi/activeuser/' + source.ID_USER + '" title="aktifkan user" onclick="btnActiveOnClick(this);"><i class="fa fa-cloud-upload" aria-hidden="true" style="color:white;"></i></button>';

                        if (is_active == -1){
                            return (btnActive).toString();
                        }else{
                            return (btnEdit + " " + btnDelete + " " + btnReset + " "+ btnAktivasi).toString();
                        }
                    },
                    bSortable: false,
                    bSearchable: false
                }
            ],
            "fnRowCallback": function (nRow, aData) {
                var stl = false;
                if (aData.STS_J == 10 || aData.STS_J == 11 || aData.STS_J == 15) {
                    stl = true;
                }
                return nRow;
            }
        }
    };

	var submitCari = function() {
        reloadTableDoubleTime(gtblDaftarIndividual);
    };
	var clearPencarian = function() {
        $('#inp_dt_completeNEW_cari').val('');
        reloadTableDoubleTime(gtblDaftarIndividual);
    };

    $(document).ready(function () {
        
        data_is_active = [{id: (1+','+0), text: 'Active'}, {id: -1, text: 'Inactive'}];
        $('#CARI_STATUS').select2({data: data_is_active});

		initiateCariInstansi();
        $(".pagination").find("a").click(function () {
            var url = $(this).attr('href');
            url = url.replace('<?= base_url(); ?>', '');
            window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#ajaxFormCari'));
            return false;
        });

        $('input[name="INST"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/ereg/admin_instansi/getInst') ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function (element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?= base_url('index.php/ereg/admin_instansi/getInst') ?>/" + id, {
                        dataType: "json"
                    }).done(function (data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection: function (state) {
                return state.name;
            }
        });

        $("#ajaxFormCari").find("#INST").change(function () {
            $("#ajaxFormCari").submit();
        });

        $('#btn-clear').click(function (event) {
            $('#ajaxFormCari').find('input:text').val('');
            $('#ajaxFormCari').trigger('submit');
        });

        $(".btn-detail").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Detail Admin Instansi', html, '');
            });
            return false;
        })

        $("#btn-add").click(function () {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                $('#loader_area').hide();
                OpenModalBox('Tambah Admin Instansi', html, '');
            });
            return false;
        });

        $('.btn-edit').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                $('#loader_area').hide();
                OpenModalBox('Edit Admin Instansi', html, '', 'standart');
            });
            return false;
        });
        $('#CARI_INSTANSI').change(function(event) {
            reloadTableDoubleTime(gtblDaftarIndividual);
        });
        $('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Hapus Admin Instansi', html, '');
            });
            return false;
        });

		$('.btn-reset').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Reset Password Admin Instansi', html, '');
            });
            return false;
        });

		gtblDaftarIndividual = initDtTbl(tblDaftarInstansi);

        $('.btn-cetak').click(function(e) {
            e.preventDefault();
            var instansi = '';
            var status = '';
            var text = '';

            instansi = ($('#CARI_INSTANSI').val() == '') ? 'ALL' : $('#CARI_INSTANSI').val();
            status = $('#CARI_STATUS').val();
            if($('#CARI_STATUS').val() == ''){
                status = 'ALL';
            }else if($('#CARI_STATUS').val() == '1,0'){
                status = '1';
            }
            text = ($('#inp_dt_completeNEW_cari').val() == '') ? 'ALL' : $('#inp_dt_completeNEW_cari').val();

            var url = '<?php echo site_url("/index.php/ereg/Cetak/export_ai"); ?>/' + instansi +'/' + status +'/' + text;
            window.location.href = url;
            return;
        });
	});


</script>