<?php
$on_data_xl = isset($on_data_xl) ? $on_data_xl : FALSE;

$on_another_module = isset($on_another_module) ? $on_another_module : FALSE;

$showUnitKerja=isset($showUnitKerja) ? $showUnitKerja :FALSE;

$set_default_null = isset($on_another_module) ? "1" : "2";
?>
<script type="text/javascript">

    var initiateCariInstansi = function () {

        $("#CARI_INSTANSI").remove();
        $("#inpCariInstansiPlaceHolder").empty();
		<?php if ($this->session->userdata('ID_ROLE')=="1" || $this->session->userdata('ID_ROLE')=="2" || $this->session->userdata('ID_ROLE')=="31"): ?>
			$("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='1081' placeholder=\"KOMISI PEMBERANTASAN KORUPSI (KPK)\">");
			initiateSelect2CariUnitKerja(1081);
		<?php else: ?>
			$("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='' placeholder=\"-- Pilih Instasi --\">");
		<?php endif; ?>
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
            url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>?nudv=no&setdefault_to_null=<?php echo $set_default_null; ?>",
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
//                    initiateSelect2CariUnitKerja(iins);
                }
            }
        });

    };

    var getDataInstansiAndUnitKerja = function () {
	var arr_response = [], valInstansi = null, valUnitKerja = null;
	valInstansi = $("#CARI_INSTANSI_HDD").val();
	valUnitKerja = $("#CARI_UNIT_KERJA_HDD").val();

	<?php if ($isAdminAplikasi || $isAdminKPK || $isSuperadmin): ?>
		valInstansi = $("#CARI_INSTANSI").val();
		valUnitKerja = $("#CARI_UNIT_KERJA").val();
	<?php elseif ($isAdminInstansi): ?>
		valInstansi = $("#CARI_INSTANSI_HDD").val();
		valUnitKerja = $("#CARI_UNIT_KERJA").val();
	<?php endif; ?>

        //arr_response.push({"name": "CARI[INSTANSI]", "value": valInstansi});
        //arr_response.push({"name": "CARI[UNIT_KERJA]", "value": valUnitKerja});

        return arr_response;
    };

		var initiateSelect2CariUnitKerja = function (LEMBAGA) {
        $("#CARI_UNIT_KERJA").remove();
        $("#inpCariUnitKerjaPlaceHolder").empty();
		if(LEMBAGA ==="")
		{
			<?php if ($isAdminAplikasi || $isAdminKPK || $isSuperadmin): ?>
			LEMBAGA = "";
			<?php endif; ?>
		}
		//alert(LEMBAGA);
		if (LEMBAGA !== 1081) {
		$("#inpCariUnitKerjaPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA' value='' placeholder=\"-- Pilih Unit Kerja --\">");
		}
		else{
			//alert(LEMBAGA);
			$("#inpCariUnitKerjaPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA' value='' placeholder=\"DEPUTI BIDANG PENCEGAHAN DAN MONITORING\">");
			<?php
				$set_default_null = "PENCEGAHAN";
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
                UNIT_KERJA = $('#CARI_UNIT_KERJA').val();
				//alert(UNIT_KERJA);
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
			<?php
			$set_default_null="PENCEGAHAN";
			?>
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
					if(dsuk ==="")
					{
						<?php if ($isAdminAplikasi || $isAdminKPK || $isSuperadmin): ?>
						dsuk = "";
						<?php endif; ?>
					}
                    $('#CARI_UNIT_KERJA').select2(cari_unit_kerja_cfg).on("change", function (e) {

					<?php if ($on_data_xl && (!$on_another_module)): ?>
						ExecDatasss();
					<?php else: ?>
						$("#spanTabNonAktif").html(" (Loading ..)");

						<?php if (!$on_another_module): ?>
								ExecDatasss();
						<?php endif; ?>
					<?php endif; ?>
                    });

                    if (dsuk != null) {
                        $("#CARI_UNIT_KERJA").val(dsuk).trigger("change");
                    }
                }

            });
        }
    };
	$(document).ready(function () {
	<?php if ($isAdminAplikasi || $isAdminKPK || $isSuperadmin): ?>

		var check_select2_ok = false;
		while (!check_select2_ok) {
			if ($('#CARI_INSTANSI').data('select2')) {
				check_select2_ok = true;initiateCariInstansi();
			} 
			else {
				initiateCariInstansi();
			}
		}

		$('#CARI_INSTANSI').change(function (event) {
			initiateSelect2CariUnitKerja($(this).val());
		});
	<?php else: ?>
		initiateSelect2CariUnitKerja($("#CARI_INSTANSI_HDD").val());
	<?php endif; ?>

	<?php if (!$on_another_module): ?>
		<?php if ($isAdminAplikasi || $isAdminKPK || $isAdminInstansi || $isSuperadmin): ?>
					$('#CARI_UNIT_KERJA').change(function (event) {
						var UNIT_KERJA = ($('#CARI_UNIT_KERJA').val());
						if (UNIT_KERJA !== "") {
							//ExecDatasss();
						}
					});
		<?php else: ?>
					//ExecDatasss();
		<?php endif; ?>
	<?php else: ?>
		<?php if ($isAdminAplikasi || $isAdminKPK || $isAdminInstansi || $isSuperadmin): ?>
					$('#CARI_UNIT_KERJA').change(function (event) {
						var UNIT_KERJA = ($('#CARI_UNIT_KERJA').val());
						if (UNIT_KERJA !== "") {
							//ExecDatasss();
						}
					});
		<?php else: ?>
					//ExecDatasss();
		<?php endif; ?>	
	<?php endif; ?>
    });
</script>