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
?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Daftar Admin Instansi
            <small></small>
          </h1>
          <?php echo $breadcrumb;?>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <!-- <h3 class="box-title">Bordered Table</h3> -->
                &nbsp;
                <?php
                // if ( $this->makses->is_write )
                    echo '<button class="btn btn-sm btn-primary" id="btn-add" href="index.php/ereg/admin_instansi/adduser"><i class="fa fa-plus"></i> Tambah Data</button>';
                ?>
                  <div class="box-tools">
				  <?php 
				     /*
						<form method="post" id="ajaxFormCari" action="index.php/ereg/admin_instansi/index">

						<div class="input-group">
							<input type='text' class="input-sm select" name='INST' style="border:none;width:300px;" id='INST' value='<?=@$instansi?>' placeholder="-- Pilih Instansi --">
						  <!--  -->
							&nbsp;
						  <input type="text" name="cari" class="form-control input-sm pull-right" style="width: 250px;" placeholder="Search by Username or Nama or Email" value="<?php echo $cari;?>" id="cari"/>
						  <div class="input-group-btn">
							<button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
							<button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#cari').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
						  </div>
							&nbsp;
						</div>
						</form>
					 */
				  ?>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body with-border">
                <!-- <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->
                <table id="dt_completeNEW" class="table">
                    <thead>
                        <tr>
                            <?php /*<th align="center" width="30">No.</th>*/ ?>
							<th align="center" width="100">Username</th>
							<th>Nama</th>
							<th align="center" width="120"class="hidden-xs hidden-sm">Last Login</th>
                            <th class="hidden-xs hidden-sm">Email</th>
                            <th>Instansi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                </div><!-- /.box-body -->
				<?php /*
                <div class="box-footer clearfix">
                    <?php
                        if($total_rows){
                    ?>
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="datatable-1_info">Showing <?php echo $start; ?> to <?php echo $end; ?>
                            of <?php echo $total_rows; ?> entries
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                    <div class="col-sm-6 text-right">
                        <div class="dataTables_paginate paging_bootstrap">
                            <?php echo $pagination; ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
				*/ ?>
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

<script language="javascript">
$(document).ready(function () {
    $(".pagination").find("a").click(function () {
        var url = $(this).attr('href');
        url = url.replace('<?=base_url();?>', '');
        window.location.hash = url;
        ng.LoadAjaxContentPost(url, $('#ajaxFormCari'));
        return false;
    });  

    $('input[name="INST"]').select2({
        minimumInputLength: 0,
        ajax: {
            url: "<?=base_url('index.php/ereg/admin_instansi/getInst')?>",
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
                $.ajax("<?=base_url('index.php/ereg/admin_instansi/getInst')?>/"+id, {
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

    $("#ajaxFormCari").submit(function (e) {
        var url = $(this).attr('action');
        ng.LoadAjaxContentPost(url, $(this));
        return false;            
    });        

    $("#ajaxFormCari").find("#INST").change(function () {
        $("#ajaxFormCari").submit();
    });
    
    $('#btn-clear').click(function(event) {
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
    
    
    
});

function cek_user(username){
    var div = $('#div-username');
    var loading = $('#loading', div);
    $('img', div).hide();
    username = username.replace(/^\s+|\s+$/g,'');
    if(username == ''){
        $('#fail', div).show();
        return;
    }
    loading.show();
    var url = "index.php/ereg/admin_instansi/cek_user/"+username;
        $.post(url,function(data){
            loading.hide();
            if (data == 'ada')
            {
                $('#fail', div).show();
                $("#username_ada").show();
                 document.getElementById('USERNAMEadd').value = "";
            }
            else
            {
                $('#success', div).show();
                $("#username_ada").hide();
            };
        });
};

function cek_email(email, id){
    var div = $('#div-email');
    var loading = $('#loading', div);
    $('img', div).hide();
    loading.show();
    var url    = "index.php/ereg/all_user_instansi/cek_email/"+encodeURIComponent(email)+'/'+id;
    $.post(url,function(data){
        loading.hide();
        if(data=='0'){
            $('#fail', div).show();
            $('#email_ada').hide();
            $('#email_salah').show();
        }else if(data=='1'){
            $('#fail', div).show();
            $('#email_ada').show();
            $('#email_salah').hide();
        }else{
            $('#success', div).show();
            $('#email_ada').hide();
            $('#email_salah').hide();
        }
    });
};

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
            OpenModalBox('Hapus Admin Instansi', html, 'standart');
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
//DataTables
	var tblDaftarInstansi = {
        tableId: 'dt_completeNEW',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarInstansi'},
        conf: {
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "sAjaxSource": "<?php echo base_url('ereg/admin_instansi/load_data_index'); ?>",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {

                var passData = getRecordDtTbl(sSource, aoData, oSettings);
                $.getJSON(sSource, passData, function (json) {
                    fnCallback(json);
                });
            },
            "aoColumns": [
                {"mDataProp": "USERNAME", bSearchable: true},
                {"mDataProp": "NAMA", bSearchable: true},
				{
                    "mDataProp": function (source, type, val) {
						var columnData = "";
							if(source.LAST_LOGIN){
								var unixTime = source.LAST_LOGIN;
								var date = new Date(unixTime * 1000);
								columnData = moment(date).format('DD-MM-YYYY h:mm:ss');
							}else{
								columnData = "";
							}
						return  columnData;
                    },
                    bSearchable: true
                },
                {"mDataProp": "EMAIL", bSearchable: true},
				{
                    "mDataProp": function (source, type, val) {
						var columnData = "";
							if(source.UK_ID){
								columnData = " <b>"+ source.UK_NAMA +"</b>)";
							}
						return  columnData;
                    },
                    bSearchable: true
                },
                {
                    "mDataProp": function (source, type, val) {
						var btnEdit = "";
						
						var	btnEdit = '<button type="button" class="btn btn-success btn-sm btn-edit" href="index.php/ereg/admin_instansi/edituser/'+ source.ID_USER +'" title="Edit" onclick="btnEditOnClick(this);"><i class="fa fa-pencil"></i></button>';
						
						var	btnDelete = '<button type="button" class="btn btn-danger btn-sm btn-delete" href="index.php/ereg/admin_instansi/deleteuser/'+ source.ID_USER +'" title="Delete" onclick="btnDeleteOnClick(this);"><i class="fa fa-trash" style="color:white;"></i></button>';
						
						var btnReset = '<button type="button" class="btn btn-sm btn-primary btn-reset" href="index.php/ereg/admin_instansi/resetpassword/'+ source.ID_USER +'" title="reset password" onclick="btnResetOnClick(this);"><i class="fa fa-reddit-square"style="color:white;"></i></button>';

                        return (btnEdit + " " + btnDelete + " " + btnReset).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                }
            ],
            "fnRowCallback": function (nRow, aData) {
                return nRow;
            }
        }
    };
	
    $(function () {	
        var gtblDaftarIndividual = initDtTbl(tblDaftarInstansi);
    });
	
    
</script>