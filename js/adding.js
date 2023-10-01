function initiateCariInstansi() {

	$("#CARI_INSTANSI").remove();
	$("#inpCariInstansiPlaceHolder").empty();
	$("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='' placeholder=\"-- Pilih Instasi --\">");

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
				initiateSelect2CariUnitKerja(iins);
			}
		}
	});

};


var initiateSelect2CariUnitKerja = function (LEMBAGA) {

	$("#CARI_UNIT_KERJA").remove();
	$("#inpCariUnitKerjaPlaceHolder").empty();
	$("#inpCariUnitKerjaPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA' value='' placeholder=\"-- Pilih Unit Kerja --\">");

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
			UNIT_KERJA = $('#CARI_UNIT_KERJA').val();
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
					ExecDatasss();
				});

				if (dsuk != null) {
					$("#CARI_UNIT_KERJA").val(dsuk).trigger("change");
				}
			}

		});
	}
};