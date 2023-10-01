<div class="box-body">
	<div class="tabbable block-body" id="tab-data-lampiran">
        <ul class="nav nav-tabs menu-filling">
            <li class="active"><a href="#tab-ax" id="tab-a" >Penjualan/Pelepasan/Pemberian Harta</a></li>
            <li ><a href="#tab-bx" id="tab-b">Penerimaan Harta</a></li>
            <!-- <li  ><a href="#tab-c" >Dokumen Pendukung</a></li> -->
        </ul>
    </div>
    <div class="tab-content block-body">
    	<div class="tab-pane fade in active" id="tab-ax">
             <?= FormHelpAccordionEfiling('lampiran_penjualan_pelepasan', 1); ?>
             <br>
             <span class="block-body">
                <table id="TablePelepasan"  class="table table-striped table-bordered table-hover table-heading no-border-bottom table-filing">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>URAIAN</th>
                            <th>URAIAN HARTA</th>
                            <th>NILAI</th>
                            <th>INFORMASI PIHAK KEDUA</th>
                        </tr>
                    </thead>
                    <tbody>   
                    </tbody>
                </table>
                <div style="overflow:hidden;" class="tab-a">
                    <div class="pull-right">
                    <a href="javascript:void(0)" onclick="pindah(6)" class="btn btn-warning btn-sm" style="margin-left:5px;">
                      <i class="fa fa-backward"></i>  Sebelumnya
                    </a>
                    <a href="javascript:void(0)" onclick="to_target('#tab-b')" class="btn btn-warning btn-sm" style="margin-left:5px;">
                       Selanjutnya <i class="fa fa-forward"></i>  
                   </a>
                  </div>
                </div>
             </span>
    	</div>
    	<div class="tab-pane fade in " id="tab-bx">
             <?= FormHelpAccordionEfiling('lampiran_penerimaan', 2); ?>
            <br>
            <table id="TableHibah"  class="table table-striped table-bordered table-hover table-heading no-border-bottom  table-filing">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>URAIAN</th>
                        <th>URAIAN HARTA</th>
                        <th>NILAI</th>
                        <th>INFORMASI PIHAK KEDUA</th>
                    </tr>
                </thead>
                <tbody id="THibah">
                </tbody>
            </table>
            <div style="overflow:hidden;" class="tab-b">
                <div class="pull-right">
                <a href="javascript:void(0)" onclick="to_target('#tab-a')" class="btn btn-warning btn-sm" style="margin-left:5px;">
                  <i class="fa fa-backward"></i>  Sebelumnya
                </a>
                <a href="javascript:void(0)" onclick="pindah(7)"class="btn btn-warning btn-sm" style="margin-left:5px;">
                   Selanjutnya <i class="fa fa-forward"></i>  
               </a>
              </div>
            </div>
    	</div>
    	<!-- <div class="tab-pane fade in active" id="tab-c">
    		<h5 class="">"Lampiran KTP, KK, NPWP, SK, Jabatan , Kartu Keluarga (KK) dan Bukti Rekening Deposito"</h5>
            <br>
            <div class="box box-success">
                <div class="box-header with-border">
                    <h2>DATA PRIBADI</h2>
                </div>
                <div class="box-body">
                  AAA
                </div>
            </div>
            <div class="box box-success">
                <div class="box-header with-border">
                    B
                </div>
                <div class="box-body">
                  AAA
                </div>
            </div>
            <div class="box box-success">
                <div class="box-header with-border">
                    C
                </div>
                <div class="box-body">
                  AAA
                </div>
            </div>
    	</div> -->
    </div>
</div>
<div class="box-FOOTER">
</div>


<script type="text/javascript">

	$(document).ready(function(){


        $('html, body').animate({
            scrollTop: 0
        }, 2000);
		$('.nav-tabs a').click(function(e) {
            e.preventDefault();
            $(this).tab('show');
        });
        $.ajax({
            url: base_url + 'portal/review_lampiran/grid_hibah/' + ID_LHKPN,
            async: false,
            dataType: 'html',
            success: function(data) {
               if(data){
                   $('#THibah').empty();
                   $('#THibah').html(data);
               }
            }
        });
         $.ajax({
            url: base_url + 'portal/review_lampiran/grid_pelepasan_now/' + ID_LHKPN,
            async: false,
            dataType: 'html',
            success: function(data) {
               if(data){
                   $('#TablePelepasan tbody').empty();
                   $('#TablePelepasan tbody').html(data);
               }
            }
        });
        $('#TablePelepasan,#TableHibah').dataTable({
            "oLanguage": ecDtLang,
            "bPaginate": true,
            "Processing": true,
            "bLengthChange": false,
            "bFilter": true,
            "bSort": true,
//            "bInfo": false,
            "bAutoWidth": true,
        });
        $('select').select2();
        $('[data-toggle="popover"]').popover({
             placement : 'left',
        }); 
        $('a.over').css('cursor','pointer');
        $('a.over').on('click', function (e) {
            $('a.over').not(this).popover('hide');
        });
        $("#tab-data-lampiran").sticky({topSpacing:120});
        $("#tab-data-lampiran").css({
            'position':'absolute',
            'z-index':'99',
            'background-color':'#fff',
            'border-bottom':'1px solid #ddd',
            'width':'98%'
        });
        $('h5').css({'padding-top':'20px'});
	});
</script>