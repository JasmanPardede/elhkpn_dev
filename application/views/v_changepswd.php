<div id="wrapperFormAdd" class='form-horizontal'>
    <form method="post" id="ajaxFormEdit"  class="ajaxForm" action="index.php/profile/dochangePassword">
        <div class="box-body">
            <input type="hidden" name="ID" id="ID" value="<?= @$ID; ?>">
            <div class="form-group">
                <label class="col-sm-3 control-label">Password Lama <font color='red'>*</font>:</label>
                <div class="col-sm-5">
                    <input onblur="cek_pswd(this.value);" type="password" name="pwdLama" class="form-control" value='' id="pswdLama" required>
                    <span class="help-block"><font id='pswd_ada' style='display:none;' color='red'>Password tidak valid</font></span>
                </div>
                <div class="col-sm-1" style="margin-top: 5px;" id="div-pswdLama">
                    <img class="show-hide" id="fail" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                    <img class="show-hide" id="success" src="<?php echo base_url('img/success.png') ?>" width="24" />
                    <img class="show-hide" id="loading" src="<?php echo base_url('img/loading.gif') ?>" width="24" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Password Baru <font color='red'>*</font>:</label>
                <div class="col-sm-5">
                    <input type="password" name="pwdBaru" id="pwdBaru" class="form-control" value='' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Konfirmasi Password Baru<font color='red'>*</font>:</label>
                <div class="col-sm-5">
                    <input onblur="cek_konfirm(this.value);" id="pwdBaru2" type="password" name="pwdBaru2" class="form-control" value='' required>
                    <span class="help-block"><font id='konfirm_ada' style='display:none;' color='red'>Password tidak sama</font></span>
                </div>
                <div class="col-sm-1" style="margin-top: 5px;" id="div-konfirm">
                    <img class="show-hide" id="fail_konfirm" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                    <img class="show-hide" id="success_konfirm" src="<?php echo base_url('img/success.png') ?>" width="24" />
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="dochangepassword">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>    
</div>

<script type="text/javascript">
    function cek_pswd(param){
        var id      = $('#ID').val();
        var div     = $('#div-pswdLama');
        var loading = $('#loading', div);
        $('img', div).hide();


        loading.show();
        var url  = "index.php/profile/cekpswdLama";
        var xdata = { ID : id , PARAM : param };
        $.post(url,xdata,function(data){
            loading.hide();
            if(data == 'ada'){
                $('#success', div).show();
                $('#pswd_ada').hide();
            }else{
                $('#fail', div).show();
                $('#pswd_ada').show();
                document.getElementById('pswdLama').value = "";
            }

        });
    }

    function cek_konfirm(param){
        var pssBaru = $('#pwdBaru').val();
        var div     = $('#div-konfirm');
        $('img', div).hide();

        if(param != pssBaru){
            $("#konfirm_ada").show();
            $('#fail_konfirm', div).show();
            document.getElementById('pwdBaru2').value = "";
        }else{
            $("#konfirm_ada").hide();
            $('#success_konfirm', div).show();
        }
    }

    $(document).ready(function () {
        $('.breadcrumb a').click(function (e) {
            url = $(this).attr('href');
            window.location.hash = url;
            LoadAjaxContent(url);
            e.preventDefault(); //Prevent Default action.
            e.unbind();
            return false;
        });                 

        $('#btnBatal').click(function (e) {
            url = $(this).attr('href');
            window.location.hash = url;
            LoadAjaxContent(url);
            e.preventDefault(); //Prevent Default action.
            e.unbind();
            return false;
        }); 
    })
</script>