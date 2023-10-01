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
 * @package Views/pn
 */
$min_tahun = isset($min_tahun) && $min_tahun ? $min_tahun : date('Y');
$default_cari_tahun = isset($default_cari_tahun) && $default_cari_tahun ? $default_cari_tahun : date('Y');

function aktifkan($idjb, $status) {
    $out = '
        <div class="dropdown pull-right" style="margin-top: 10px;">
        <a class="btn btn-sm btn-success btnAktifkan" href="index.php/ereg/all_pn/aktifkan/' . $idjb . '/' . $status . '">
            Aktifkan
        </a>
    ';

    $out .= '</div>';
    return $out;
}
?>

<section class="content-header">
  <div class="panel panel-default">
    <div class="panel-heading"><strong>TRACKING PN/WL</strong></div>
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

                    <div class="col-md-6">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">NIK :</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control input-sm" placeholder="NIK" name="CARI[NIK]" value="<?php echo htmlspecialchars(stripcslashes(@$CARI['NIK']), ENT_QUOTES); ?>" id="CARI_NIK"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Nama :</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control input-sm" placeholder="Nama" name="CARI[NAMA]" value="<?php echo htmlspecialchars(stripcslashes(@$CARI['NAMA']), ENT_QUOTES); ?>" id="CARI_NAMA"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Email :</label>
                                <div class="col-sm-6">
                                    <input type="email" class="form-control input-sm" placeholder="Email" name="CARI[EMAIL]" value="<?php echo htmlspecialchars(stripcslashes(@$CARI['Email']), ENT_QUOTES); ?>" id="CARI_EMAIL"/>
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom: 0px;">
                                <label class="col-sm-4 control-label">Tanggal Lahir :</label>
                                <div class="col-sm-6">
                                    <input type="date" class="form-control input-sm" placeholder="Tanggal Lahir" name="CARI[TGL_LAHIR]" value="<?php echo htmlspecialchars(@$CARI['TGL_LAHIR'], ENT_QUOTES); ?>" id="CARI_TGL_LAHIR"/>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">

                            
                            <?php
                            // $min_tahun = 2017;
                            $default_cari_tahun = date('Y') - 1;
                            ?>
                            <?php if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31 || $this->session->userdata('ID_ROLE') == 108): ?>
                            <div class="form-group" style="margin-bottom: 12px;">
                                <label class="col-sm-4 control-label">Instansi :</label>
                                <div id="inpCariInstansiPlaceHolder" class="col-sm-6">
                                    <input type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI' value='' placeholder="-- Pilih Instasi --">
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="form-group" style="margin-bottom: 14px;">
                                <label class="col-sm-4 control-label">Unit Kerja:</label>
                                <div id="inpCariUnitKerjaPlaceHolder" class="col-sm-6">
                                    <input type='text' class="input-sm form-control" name='CARI[UNIT_KERJA]' style="border:none;padding:6px 0px;" id='CARI_UNIT_KERJA' value='' placeholder="-- Pilih Unit Kerja --">
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: 5px;">
                                <label class="col-sm-4 control-label">WL Tahun :</label>
                                <div class="col-sm-6">
                                    <select id='CARI_TAHUN_WL' name="CARI[TAHUN_WL]">
                                        <option value="">All</option>
                                        <?php while ($min_tahun <= date('Y') + 1): ?>
                                            <option value="<?php echo $min_tahun; ?>" <?php echo $default_cari_tahun == $min_tahun ? "selected=selected" : ""; ?>><?php echo $min_tahun; ?></option>
                                            <?php $min_tahun ++; ?>
                                        <?php endwhile; ?>
                                    </select>
                                    <button type="button" id="btnCari" class="btn btn-sm btn-default" onclick="submitCari();"><i class="fa fa-search"></i></button>
                                    <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="clearPencarian();">Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <!-- <table id="dt_completeNEW" class="table table-striped"> -->
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Tanggal Lahir</th>
                                <th>WL Tahun</th>
                                <th>Menu</th>
                                <th>Sub Menu</th>
                                <?php 
                                    // if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31):
                                 ?>
                                <!-- <th>Instansi</th> -->
                                <?php 
                                    // endif;
                                 ?>
                                <!-- <th>Unit kerja</th> -->
                                <!-- <th>Menu</th>
                                <th>Sub Menu</th>
                                 <th>Aksi</th> 
                                 <th width="50">Aksi</th> -->
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
    </div>
    </div><!-- /.row -->
</section><!-- /.content -->


<script type="text/javascript">
    // var gtblAllPnNonAktif;

    var tblAllPnNonAktif = {
        tableId: 'dt_completeNEW',
        reloadFn: {tableReload: true, tableCollectionName: 'tblAllPnNonAktif'},
        conf: {
            "cShowSearch": false,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "searching": false,
            "sAjaxSource": "<?php echo base_url('ereg/all_pn/load_page_tracking_pn_wl/1'); ?>",
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {
                var passData = getRecordDtTbl(sSource, aoData, oSettings);
                passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI").val()});
                passData.push({"name": "CARI[UNIT_KERJA]", "value": $("#CARI_UNIT_KERJA").val()});
                passData.push({"name": "CARI[NIK]", "value": $("#CARI_NIK").val()});
                passData.push({"name": "CARI[TGL_LAHIR]", "value": $("#CARI_TGL_LAHIR").val()});
                // passData.push({"name": "CARI[INSTANSI]", "value":1081});
                // passData.push({"name": "CARI[NAMA]", "value":"A. DAMANIK"});
                passData.push({"name": "CARI[NAMA]", "value": $("#CARI_NAMA").val()});
                passData.push({"name": "CARI[TAHUN_WL]", "value": $("#CARI_TAHUN_WL").val()});
                passData.push({"name": "CARI[EMAIL]", "value": $("#CARI_EMAIL").val()});
                $.getJSON(sSource, passData, function(json) {
                    fnCallback(json);
                });
            },
            "bAutoWidth": false,
            "aoColumns": [
                {"mDataProp": "NO_URUT", bSearchable: true,sWidth: "5%"},
                {"mDataProp": "NIK", bSearchable: true},
                {"mDataProp": "NAMA", bSearchable: true},
                {"mDataProp": "TGL_LAHIR", bSearchable: true},
                {"mDataProp": "TAHUN_WL", bSearchable: true},
                {"mDataProp": "MENU", bSearchable: true},
                {"mDataProp": "SUB_MENU", bSearchable: true},
                <?php 
                // if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31):
                ?>
                // {"mDataProp": "INST_NAMA", bSearchable: true},
                <?php 
                // endif;
                ?>
                // {"mDataProp": "UNIT_KERJA", bSearchable: true},
                // {
                //     "mDataProp": function(source, type, val) {
                //         var arr_showed_string = [];
                //         if (isObjectAttributeExists(source, 'N_JAB') && !isEmpty(source.N_JAB)) {
                //             arr_showed_string.push(source.N_JAB);
                //         }
                //         if (isObjectAttributeExists(source, 'N_SUK') && !isEmpty(source.N_SUK)) {
                //             arr_showed_string.push(source.N_SUK);
                //         }
                //         if (isObjectAttributeExists(source, 'N_UK') && !isEmpty(source.N_UK)) {
                //             arr_showed_string.push(source.N_UK);
                //         }
                //         return  arr_showed_string.join(' - ');
                //     },
                //     bSearchable: false
                // },

        //                 {
        //                     "mDataProp": function(source, type, val) {
        //                         var d = new Date();
        //                         var n = d.getTime();
        //                         var stl = false;

        //                         var btnAksi = '';
        //                         var btnGo = '';
        //                         if (!stl) {
        //                             if(source.MENU=="Verifikasi Data Individual"){
        //                               var btnGo = '<a href="index.php?dpg=248ca5d014905db3107d525268cc34c9'+n+'#index.php/ereg/all_pn/daftar_pn_individu_ver" class="btn btn-xs btn-primary"><i class="fa fa-external-link"></i></a>'
        //                             }else if(source.MENU=="PN/WL Online"){
        //                               // var btnDetail = '<button type="button" class="btn btn-sm btn-info btn-detail" href="index.php/ereg/all_pn/detailpn/' + source.ID_PN + '/' + source.ID + '" title="Preview" onclick="onButton.detail.click(this);"><i class="fa fa-search-plus"></i></button>';
        //                               // var btnDetail3 = '<button type="button" class="btn btn-sm btn-primary btn-detail" href="index.php/ereg/all_pn/reset_password/' + source.ID_USER + '" title="Reset Password" onclick="onButton.detail3.click(this);"><i class="fa fa-reddit-square"style="color:white;"></i></button>';
        //                               // var btnAktivasi = '<button type="button" class="btn btn-sm btn-primary btn-aktivasi" href="index.php/ereg/all_pn/kirimaktivasi/' + source.ID_USER + '" title="kirim ulang aktivasi" onclick="btnAktivasiOnClick(this);"><i class="fa fa-send"style="color:white;"></i></button>';
        //                               // var btnTerimaFormulir ;
                                    // <?php if ($this->session->userdata('ID_ROLE')=="1" || $this->session->userdata('ID_ROLE')=="2"): ?>
        //                               //     if(source.IS_FORMULIR_EFILLING == 0){
        //                               //         btnTerimaFormulir ='<button type="button" class="btn btn-sm btn-success btn-terima" href="index.php/ereg/all_pn/TerimaFormulir/' + source.ID_PN + '" title="Formulir Efilling Sudah Diterima" onclick="btnTerimaFormulirOnClick(this);"><i class="fa fa-check"style="color:white;"></i></button>';
        //                               //     }
        //                               //     else{
        //                               //         btnTerimaFormulir ='<button type="button" class="btn btn-sm btn-danger btn-terima" href="index.php/ereg/all_pn/BatalTerimaFormulir/' + source.ID_PN + '" title="Formulir Efilling Belum Diterima" onclick="btnBatalTerimaFormulirOnClick(this);"><i class="fa fa-close"style="color:white;"></i></button>';
        //                               //     }
                                    // <?php else: ?>
        //                               //     btnTerimaFormulir='';
                                    // <?php endif; ?>
        //                               //
        //                               // var disable = '', btnApprove = '', btnAksi = '';
        //                               // btnAksi += btnDetail ;
        //                               // btnAksi += ' ' + btnDetail3;
        //                               // btnAksi += ' ' + btnAktivasi;
        //                               // btnAksi += ' ' + btnTerimaFormulir;

        //                               var btnGo = '<a href="index.php?dpg=248ca5d014905db3107d525268cc34c9'+n+'#index.php/ereg/all_pn" class="btn btn-xs btn-primary"><i class="fa fa-external-link"></i></a>'
        //                             }else if(source.MENU=="PN/WL Offline"){
        //                               // var btnPreview = '<button type="button" class="btn btn-sm btn-info btn-detail" href="index.php/ereg/all_pn/detailpn/' + source.ID_PN + '/' + source.ID + '" title="Preview" onclick="onButton.click(this);"><i class="fa fa-search-plus"></i></button>';
        //                               // var btnDetail3 = '<button type="button" class="btn btn-sm btn-success btn-detail2" href="index.php/ereg/all_pn/editpn_daftarnonaktif/2/daftarindividu/' + source.ID_PN + '/' + source.ID_JAB + '/1" title="Online" onclick="onButton.click(this);"><i class="fa fa-user-plus"></i></button>';
        //                               // btnAksi += '<small>' + btnPreview + ' ' + btnDetail3 + '</small>';

        //                               var btnGo = '<a href="index.php?dpg=248ca5d014905db3107d525268cc34c9'+n+'#index.php/ereg/all_pn/nonaktif" class="btn btn-xs btn-primary"><i class="fa fa-external-link"></i></a>'
        //                             }else if(source.MENU=="Daftar Wajib Lapor"){
        //                               var btnGo = '<a href="index.php?dpg=248ca5d014905db3107d525268cc34c9'+n+'#index.php/ereg/all_pn/wl_aktif" class="btn btn-xs btn-primary"><i class="fa fa-external-link"></i></a>'
        //                             }else if(source.MENU=="Daftar Non Wajib Lapor"){
        //                               var btnGo = '<a href="index.php?dpg=248ca5d014905db3107d525268cc34c9'+n+'#index.php/ereg/all_pn/wl_nonaktif" class="btn btn-xs btn-primary"><i class="fa fa-external-link"></i></a>'
        //                             }else if(source.MENU=="PN/WL Offline"){
        //                               var btnGo = '<a href="index.php?dpg=248ca5d014905db3107d525268cc34c9'+n+'#index.php/ereg/all_pn/nonaktif" class="btn btn-xs btn-primary"><i class="fa fa-external-link"></i></a>'
        //                             }else if(source.MENU=="Rangkap Jabatan"){

        //                             }
        //                         }

        //                         return (btnGo).toString();
        //                         // return (btnAksi).toString();
        //                     },
        //                     bSortable: false,
        //                     bSearchable: false
        //                 }
            ]
        }
    };


    var gtblAllPnNonAktif;

    $(function() {
    	 console.log('gtblAllPnNonAktif');

        	//  gtblAllPnNonAktif = initDtTbl(tblAllPnNonAktif_init);

            gtblAllPnNonAktif = initDtTbl(tblAllPnNonAktif);
    });
    
    var clearPencarian = function() {

        $('#CARI_NIK').val('');
        $('#CARI_NAMA').val('');
        $('#CARI_EMAIL').val('');
        $('#CARI_TGL_LAHIR').val('');

        init_instansi_n_unitkerja();

        $('#inp_dt_completeNEW_cari').val('');
        reloadTableDoubleTime(gtblAllPnNonAktif);
    };

    var is_first = 0;

    var submitCari = function() {

        let cari_nik = $('#CARI_NIK').val();
        let cari_nama = $('#CARI_NAMA').val();
        let cari_email = $('#CARI_EMAIL').val();
        let cari_tgl_lahir = $('#CARI_TGL_LAHIR').val();

        if (cari_nik === "" && cari_nama === ""  && cari_email === ""  && cari_tgl_lahir === "") {
            alertify.warning('NIK / Nama / Email / Tanggal Lahir harus di isi !!');
        }
        
     	//  console.log('submit cari');
		// if(is_first==0){;
 		// 	gtblAllPnNonAktif = initDtTbl(tblAllPnNonAktif);
		// }else {
			reloadTableDoubleTime(gtblAllPnNonAktif);
		// }
		// is_first++;
    };


        // var initiateCariInstansi = function() {
        //   console.log('initiate cari instanse');
        //     $("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='' placeholder=\"-- Pilih Instasi --\">");
        //
        //     // initiateSelect2CariUnitKerja(LEMBAGA);
        //
        //
        //
        // }/* End initiate Cari Instansi */

    var onButton = {
        go: function(obj, size) {
            if (!isDefined(size)) {
                size = 'large';
            }
            var url = $(obj).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Wajib Lapor', html, '', size);
            });
            return false;
        },
        click: function(self) {
            onButton.go(self);
        },
        detail: {
            click: function (self) {
                onButton.go(self);
            }},
        detail3: {
            click: function (self) {
                onButton.go(self);
            }
        },
    };

    // var gtblAllPnNonAktif;
    // $(function() {
    //
    //     $('.btn-edit').click(function(e) {
    //         onButton.edit.click(this);
    //     });
    //
    //     $('.btn-delete').click(function(e) {
    //         onButton.delete.click(this);
    //     });
    //
    //
    // });

    var initiateCariInstansi = function() {

        $("#CARI_INSTANSI").remove();

        // $("#inpCariInstansiPlaceHolder").empty();
        <?php /* if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2" || $this->session->userdata('ID_ROLE') == "31"  || $this->session->userdata('ID_ROLE') == "60"): ?>
        $("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='1081' placeholder=\"KOMISI PEMBERANTASAN KORUPSI (KPK)\">");
        initiateSelect2CariUnitKerja(1081);
        <?php else: */ ?>
                    $("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='' placeholder=\"-- Pilih Instasi --\">");
        <?php /* endif; */ ?>
        var cari_instansi_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
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
                    $.ajax("<?php echo base_url('index.php/share/reff/getLembaga') ?>/" + id, {
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
            url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
            dataType: "json",
            async: false,
        }).done(function(data) {
            if (!isEmpty(data.item)) {
                cari_instansi_cfg.data = [{
                        id: data.item[1].id,
                        name: data.item[1].name
                    }];

                iins = data.item[1].id;
                $('#CARI_INSTANSI').select2(cari_instansi_cfg);
                if (iins != null) {
                    $("#CARI_INSTANSI").val(iins).trigger("change");

                    <?php if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2" || $this->session->userdata('ID_ROLE') == "31" || $this->session->userdata('ID_ROLE') == "108"): ?>
                        initiateSelect2CariUnitKerja(iins);
                    <?php endif; ?>
                    
                }
            }
        });

	};
    var initiateSelect2CariUnitKerja = function (LEMBAGA) {

	$("#CARI_UNIT_KERJA").remove();
	$("#inpCariUnitKerjaPlaceHolder").empty();
        var set_default_null = "PENCEGAHAN";
	if (LEMBAGA !== 1081) {
	$("#inpCariUnitKerjaPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA' value='' placeholder=\"-- Pilih Unit Kerja --\">");
	}
	else{
            $("#inpCariUnitKerjaPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA' value='' placeholder=\"DEPUTI BIDANG PENCEGAHAN DAN MONITORING\">");
            set_default_null = "PENCEGAHAN";
        }
        var cari_unit_kerja_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getUnitKerja'); ?>/" + LEMBAGA + "?setdefault_to_null="+set_default_null,
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
                var UNIT_KERJA = $('#CARI_UNIT_KERJA').val();
                if (UNIT_KERJA !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA + "/" + UNIT_KERJA + "?setdefault_to_null="+set_default_null, {
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
	if (isDefined(LEMBAGA)) {
		var __UNIT_KERJA = $('#CARI_UNIT_KERJA').val();

		$.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA + "/" + __UNIT_KERJA + "?setdefault_to_null=<?php echo $set_default_null; ?>", {
			dataType: "json"
		}).success(function (data) {
//                    console.log(data, data.item, !isEmpty(data.item));
			if (!isEmpty(data.item)) {
				cari_unit_kerja_cfg.data = [{
						id: data.item[0].id,
						name: data.item[0].name
					}];

				dsuk = data.item[0].id;

				$('#CARI_UNIT_KERJA').select2(cari_unit_kerja_cfg).on("change", function (e) {
					reloadTableDoubleTime(gtblAllPnNonAktif);
				});

				if (dsuk != null) {
					$("#CARI_UNIT_KERJA").val(dsuk).trigger("change");
				}
			}

		});
	}
	};

    var init_instansi_n_unitkerja = function()  {
        initiateCariInstansi();
        
        $('#CARI_INSTANSI').change(function() {
            initiateSelect2CariUnitKerja($(this).val());
        });
    }


    $(document).ready(function() {
      console.log('inisialisasi sistem');
        var data_is_wl = [{id: 0, text: 'Belum'}, {id: 1, text: 'Sudah'}];
        $("#CARI_TAHUN_WL").select2();
        
        init_instansi_n_unitkerja();


        <?php /* if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2" || $this->session->userdata('ID_ROLE') == "31" || $this->session->userdata('ID_ROLE') == "60"): ?>
                        initiateSelect2CariUnitKerja(1081);
        <?php else: ?>
                        initiateSelect2CariUnitKerja(<?php echo $this->session->userdata('INST_SATKERKD'); ?>);
        <?php endif; */  ?>

        <?php if ($this->session->userdata('ID_ROLE') != "1" || $this->session->userdata('ID_ROLE') != "2" || $this->session->userdata('ID_ROLE') != "31" || $this->session->userdata('ID_ROLE') != "108"): ?>
                initiateSelect2CariUnitKerja(<?php echo $this->session->userdata('INST_SATKERKD'); ?>);
        <?php endif; ?>
        
      

    //    submitCari();

       







        // $('.btn-reset').click(function(e) {
        //     url = $(this).attr('href');
        //     $.post(url, function(html) {
        //         OpenModalBox('Reset Password', html, '', 'standart');
        //     });
        //     return false;
        // });
        //
        // $(".btn-detail").click(function() {
        //     url = $(this).attr('href');
        //     $('#loader_area').show();
        //     $.post(url, function(html) {
        //         OpenModalBox('Detail PN/WL', html, '', 'large');
        //     });
        //     return false;
        // });
        //
        // $(".btn-detail2").click(function() {
        //     url = $(this).attr('href');
        //     $('#loader_area').show();
        //     $.post(url, function(html) {
        //         OpenModalBox('Aktifkan Status PN/WL', html, '', 'large');
        //     });
        //     return false;
        // });
        //
        // $('.btn-edit').click(function(e) {
        //     url = $(this).attr('href');
        //     $('#loader_area').show();
        //     $.post(url, function(html) {
        //         OpenModalBox('Pembaharuan PN/WL', html, '', 'large');
        //     });
        //     return false;
        // });
        //
        // $('.btn-delete').click(function(e) {
        //     url = $(this).attr('href');
        //     $('#loader_area').show();
        //     $.post(url, function(html) {
        //         OpenModalBox('Delete Penyelenggara Negara', html, '');
        //     });
        //     return false;
        // });
        //
        // $('.btn-keljab').click(function(e) {
        //     url = $(this).attr('href');
        //     $('#loader_area').show();
        //     $.post(url, function(html) {
        //         OpenModalBox('Riwayat Jabatan', html, '', 'large');
        //     });
        //     return false;
        // });
        //
        // $('.btnAktifkan').click(function(e) {
        //     url = $(this).attr('href');
        //     $.post(url, function(html) {
        //         OpenModalBox('Mengaktifkan Data', html, '', 'standart');
        //     });
        //     return false;
        // });
    });

</script>

<style type="text/css">
    .listjabatan{
        margin: 0px;
        padding:0px;
    }
    .listjabatan li.item{
        list-style: none;
        border: 1px solid #cfcfcf;
        padding-left: 10px;
        padding-bottom: 12px;
        margin-top: -1px;
    }
    ul.dropdown-menu {
        background-color: #99E1F4;
    }
</style>

<?php
//$js_page = isset($js_page) ? $js_page : '';
//if (is_array($js_page)) {
// foreach ($js_page as $page_js) {
//     echo $page_js;
//  }
//} else {
//  echo $js_page;
//}
?>

<style>
    td .btn {
        margin: 0px;
    }
</style>
