<div id="wrapperFormUpdateSetting" class="form-horizontal">
	<form method="post" id="ajaxFormUpdateSetting" action="index.php/admin/syssetting/save/direkturpp">
        <div class="form-group">
            <label class="col-sm-3 control-label">PN<font color='red'>*</font> :</label>
            <div class="col-sm-4">
                <div class='col-sm-12' style="margin-left: -20px;">
                    <input type='text' class="form-control form-select2" name='PN' style="border:none;" id='PN' value="<?php echo $item->VALUE; ?>"  placeholder="PN" required>
                </div>
            </div>
        </div>
		<div class="form-group">
			<div class="pull-right col-sm-9">
				<input type="hidden" name="act" value="doupdatesetting">
				<input type="hidden" name="OWNER" value="app.lhkpn">
				<input type="hidden" name="SETTING" value="direkturpp">
				<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
				<!--<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>-->
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		ng.formProcess($("#ajaxFormUpdateSetting"), 'insert', 'index.php/admin/syssetting/index/direkturpp/', '');

        $('input[name="PN"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getPN')?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) {
                    return { results: data.item };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getPN')?>/"+id, {
                        dataType: "json"
                    }).done(function(data) { callback(data[0]); });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection:  function (state) {
                return state.name;
            }
        });
	});
</script>