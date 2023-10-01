<script type="text/javascript">
   // var gtblAllPnNonAktif;
   
	function initiateCariInstansi() {

	$("#CARI_INSTANSI").remove();
	$("#inpCariInstansiPlaceHolder").empty();
        var LEMBAGA = '1081';
	<?php if ($this->session->userdata('ID_ROLE')=="1" || $this->session->userdata('ID_ROLE')=="2" || $this->session->userdata('ID_ROLE') == "7" || $this->session->userdata('ID_ROLE') == "10" || $this->session->userdata('ID_ROLE') == "13" || $this->session->userdata('ID_ROLE') == "14" || $this->session->userdata('ID_ROLE') == "18" || $this->session->userdata('ID_ROLE') == "31"): ?>
		$("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='1081' placeholder=\"KOMISI PEMBERANTASAN KORUPSI (KPK)\">");
//		initiateSelect2CariUnitKerja(1081);
	<?php else: ?>
		$("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='' placeholder=\"-- Pilih Instasi --\">");
                LEMBAGA = '<?php echo $default_instansi; ?>';
        <?php endif; ?>
            initiateSelect2CariUnitKerja(LEMBAGA);
	var cari_instansi_cfg = {
		minimumInputLength: 0,
		data: [],
		ajax: {
			url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
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
				$.ajax("<?php echo base_url('index.php/share/reff/getLembaga') ?>/" + id, {
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
		url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
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
//				initiateSelect2CariUnitKerja(iins);
			}
		}
	});

};


	var initiateSelect2CariUnitKerja = function (LEMBAGA) {

	$("#CARI_UNIT_KERJA").remove();
	$("#inpCariUnitKerjaPlaceHolder").empty();
        var set_default_to_null = '';
	if (LEMBAGA !== 1081) {
	$("#inpCariUnitKerjaPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA' value='' placeholder=\"-- Pilih Unit Kerja --\">");
	}
	else{
		$("#inpCariUnitKerjaPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA' value='' placeholder=\"DEPUTI BIDANG PENCEGAHAN DAN MONITORING\">");
		<?php
			$set_default_null = "pencegahan";
		?>

	}
	var cari_unit_kerja_cfg = {
		minimumInputLength: 0,
		data: [],
		ajax: {
			url: "<?php echo base_url('index.php/share/reff/getUnitKerja'); ?>/" + LEMBAGA + "?setdefault_to_null=<?php echo $set_default_null; ?>",
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
			var UNIT_KERJA = $('#CARI_UNIT_KERJA').val();
			if (UNIT_KERJA !== "") {
				$.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA + "/" + UNIT_KERJA + "?setdefault_to_null=<?php echo $set_default_null; ?>", {
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


    $(document).ready(function () {

		$(".pagination").find("a").click(function () {
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#ajaxFormCari'));
            return false;
        });

		$("#ajaxFormCari").submit(function (e) {
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this));
            return false;
        });

		$('.btn-reset').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Reset Password', html, '', 'standart');
            });
            return false;
        });

		$(".btn-detail").click(function () {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Detail PN/WL', html, '', 'large');
            });
            return false;
        });

		$(".btn-detail2").click(function () {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Aktifkan Status PN/WL', html, '', 'large');
            });
            return false;
        });

		$('.btn-edit').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Pembaharuan PN/WL', html, '', 'large');
            });
            return false;
        });

		$('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Delete Penyelenggara Negara', html, '');
            });
            return false;
        });

		$('.btn-keljab').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Riwayat Jabatan', html, '', 'large');
            });
            return false;
        });

		$('.btnAktifkan').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Mengaktifkan Data', html, '', 'standart');
            });
            return false;
        });

		var data_is_wl = [{id: 0, text: 'Belum'}, {id: 1, text: 'Sudah'}];

		$('#CARI_IS_WL').select2({
            data: data_is_wl
        });

		$("#CARI_TAHUN_WL").select2();
		initiateCariInstansi();

		$('#CARI_INSTANSI').change(function (event) {
                initiateSelect2CariUnitKerja($(this).val());
            });

		<?php // if ($this->session->userdata('ID_ROLE')=="1" || $this->session->userdata('ID_ROLE')=="2" || $this->session->userdata('ID_ROLE') == "7" || $this->session->userdata('ID_ROLE') == "10" || $this->session->userdata('ID_ROLE') == "13" || $this->session->userdata('ID_ROLE') == "14" || $this->session->userdata('ID_ROLE') == "18"): ?>
//		initiateCariInstansi();
		<?php // else: ?>
//			initiateSelect2CariUnitKerja(<?php echo $this->session->userdata('INST_SATKERKD');?>);
		<?php // endif; ?>



	});

    var onButton = {
        go: function (obj, size) {

            if (!isDefined(size)) {
                size = 'large';
            }

            var url = $(obj).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Wajib Lapor', html, '', size);
            });
            return false;
        },
        click: function (self) {
            onButton.go(self);
        }
    };

	var tblAllPnNonAktif = {
        tableId: 'dt_completeNEW',
        reloadFn: {tableReload: true, tableCollectionName: 'tblAllPnNonAktif'},
        conf: {
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "sAjaxSource": "<?php echo base_url('ereg/all_pn/load_page_offline/1'); ?>",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                var passData = getRecordDtTbl(sSource, aoData, oSettings);
				passData.push({"name": "CARI[TEXT]", "value": $("#inp_dt_completeNEW_cari_").val()});
                passData.push({"name": "CARI[IS_WL]", "value": $("#CARI_IS_WL").val()});
                passData.push({"name": "CARI[TAHUN_WL]", "value": $("#CARI_TAHUN_WL").val()});
				passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI").val()});
				passData.push({"name": "CARI[UNIT_KERJA]", "value": $("#CARI_UNIT_KERJA").val()});
                $.getJSON(sSource, passData, function (json) {
                    fnCallback(json);
                });
            },
            "aoColumns": [
                {"mDataProp": "NO_URUT", bSearchable: true},
                {"mDataProp": "NIK", bSearchable: true},
                {"mDataProp": "NAMA", bSearchable: true},
                {
                    "mDataProp": function (source, type, val) {
                        var arr_showed_string = [];
                        if (isObjectAttributeExists(source, 'N_JAB') && !isEmpty(source.N_JAB)) {
                            arr_showed_string.push(source.N_JAB);
                        }
                        if (isObjectAttributeExists(source, 'N_SUK') && !isEmpty(source.N_SUK)) {
                            arr_showed_string.push(source.N_SUK);
                        }
                        if (isObjectAttributeExists(source, 'N_UK') && !isEmpty(source.N_UK)) {
                            arr_showed_string.push(source.N_UK);
                        }
                        return  arr_showed_string.join(' - ');
                    },
                    bSearchable: false
                },
                {
                    "mDataProp": function (source, type, val) {

                        var stl = false;

                        var btnPreview = '<button type="button" class="btn btn-sm btn-info btn-detail" href="index.php/ereg/all_pn/detailpn/' + source.ID_PN + '/' + source.ID + '" title="Preview" onclick="onButton.click(this);"><i class="fa fa-search-plus"></i></button>';

                        var btnDetail3 = '<button type="button" class="btn btn-sm btn-success btn-detail2" href="index.php/ereg/all_pn/editpn_daftarnonaktif/2/daftarindividu/' + source.ID_PN + '/' + source.ID_JAB + '/1" title="Online" onclick="onButton.click(this);"><i class="fa fa-user-plus"></i></button>';

//                        var btnDetail2 = '<button type="button" class="btn btn-sm btn-success btn-detail2" href="index.php/ereg/all_pn/editpn_daftar_wl/2/daftarindividu/' + source.ID_PN + '/' + source.ID + '" title="Aktifkan Kmbali" onclick="onButton.click(this);"><i class="fa fa-user-plus"></i></button>';




                        var btnAksi = '';

                        if (!stl) {
                            btnAksi += '<small>'+btnPreview + ' ' + btnDetail3 +'</small>';
                        }

                        return (btnAksi).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                }
            ]
        }
    };




	$(function () {

        $('.btn-edit').click(function (e) {
            onButton.edit.click(this);
        });

        $('.btn-delete').click(function (e) {
            onButton.delete.click(this);
        });

      var  gtblAllPnNonAktif = initDtTbl(tblAllPnNonAktif);
    });

	var clearPencarian = function () {
        $('#CARI_IS_WL').val('');
        $('#CARI_TAHUN_WL').val('');
		$('#CARI_INSTANSI').val('');
        $('#inp_dt_completeNEW_cari').val('');
        reloadTableDoubleTime(gtblAllPnNonAktif);
    };

	var submitCari = function () {
        reloadTableDoubleTime(gtblAllPnNonAktif);
    };




	</script>