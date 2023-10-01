<section class="content-header">
	<h1>
		Pengumuman
		<small>Daftar Pengumuman</small>
	</h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			 <div class="box box-primary">
				<div class="box-body">
					 <a href="index.php/cms/pengumuman/add" class="btn btn-sm btn-primary btn-add" style="margin-bottom:10px;">
					 	<i class="fa fa-plus"></i> Tambah
					 </a>
					 <table id="Tabel" style="width:100%;" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
						 <thead>
						 	<tr>
						 		<th style="text-align:left; padding:12px;">No.</th>
						 		<th style="text-align:left; padding:12px;">Tanggal Dibuat</th>
						 		<th style="text-align:left; padding:12px;">Isi Pengumuman</th>
						 		<th style="text-align:left; padding:12px;">Role</th>
						 		<th style="text-align:left; padding:12px;">Status</th>
						 		<th style="text-align:left; padding:px;">Aksi</th>
						 	</tr>
						 </thead>
						 <tbody>
						 </tbody>
					 </table>
				</div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
	$(document).ready(function(){

		$('#Tabel').dataTable
                    ({
						'bJQueryUI'      : false,
						'sPaginationType': 'full_numbers',
						'bServerSide'    : true,
						'bAutoWidth'     : true,
                        'sAjaxSource'    : '<?php echo base_url();?>cms/pengumuman/ListPengumuman',
                        'aoColumns'      :
                            [
                            null,
                            null,
                            null,
                            null,
                            null,
                            null
                        ],
                        'fnServerData': function(sSource, aoData, fnCallback)
                        {
                            $.ajax
                            ({
                                'dataType': 'json',
                                'type'    : 'POST',
                                'url'     : sSource,
                                'data'    : aoData,
                                'success' : fnCallback
                            });
                        },
                    });
      

        $(".btn-add").click(function () {
	       url = $(this).attr('href');
	       $('#loader_area').show();
	        $.post(url, function (html) {
	        	$('#loader_area').hide();
	            OpenModalBox('Tambah Pengumuman', html, '', 'large');
	            $('#ID_PENGUMUMAN').val("");
	            $('#METHOD').val("save");
	        });            
	        return false;
	    })

	    $("#Tabel tbody").on('click','.delete-action',function(event){
			 var id = $(this).attr('id');
			 var r = confirm("Apakah anda yakin akan menghapus data ? ");
			 if (r == true) {
			 	$.ajax({
			 		url: '<?php echo base_url();?>cms/pengumuman/delete/'+id,
			 		dataType: 'html',
			 		async: false,
			 		success:function(data){
			 			if(data=='1'){
			 				alertify.success("Data Berhasil Dihapus");
			 			}else{
			 				alertify.error("Data Gagal Dihapus !!");
			 			}
			 			$('#Tabel').DataTable().ajax.reload();
			 		}
			 	});
			 }
		});

		 $("#Tabel tbody").on('click','.edit-action',function(event){
			 var id = $(this).attr('id');
			 $('#loader_area').show();
			 $.ajax({
			 	url: '<?php echo base_url();?>cms/pengumuman/edit/'+id,
			 	dataType:'json',
			 	async:false,
			 	success:function(data){
			 		var obj = eval(data);
			 		setTimeout(function(){
			 			$.post('<?php echo base_url();?>cms/pengumuman/add', function (html) {
				        	$('#loader_area').hide();
				            OpenModalBox('Edit Pengumuman', html, '', 'large');
				            $('#txtPengumuman').val(data[0].PENGUMUMAN);
				            $('#ID_PENGUMUMAN').val(data[0].ID_PENGUMUMAN);
				            $('#METHOD').val("update");
				            if(data[0].IS_PUBLISH=='1'){
				            	$("#is_published").prop( "checked", true );
				            }else{
				            	$("#is_published").prop( "checked", false );	
				            }
				            if(data[0].ID_ROLE=='5'){
				            	$("#ID_ROLE_5").prop( "checked", true );
				            }else if(data[0].ID_ROLE=='1'){
				            	$("#ID_ROLE_1").prop( "checked", true );
				            }else if(data[0].ID_ROLE=='5,1'){
				            	$("#ID_ROLE_5").prop( "checked", true );
				            	$("#ID_ROLE_1").prop( "checked", true );
							}
				        });   
			 		},3000);
			 	}
			 });
		});

	});
</script>
