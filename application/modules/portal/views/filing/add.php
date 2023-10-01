<nav id="menu-nav" class="navbar navbar-default">
    <div class="container-fluid">
        <div class="row" id="main-menu">
            <div id="wrapper-menu" class="col-lg-12">
                <ul>
                    <li><a href="<?php echo base_url(); ?>portal/filing"  class="active">E-Filling</a></li>
                    <li><a href="<?php echo base_url(); ?>portal/mailbox">Mailbox</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<section id="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div id="wrapper-main" class="my_wizard">
                <div class="col-lg-2">
                    <ul class="nav nav-stacked affix nav-pills" id="sidebar">
                        <li>
                            <div class="action" id="btn-prev">
                               <i alt="Sebelumnya" title="Sebelumnya" class="fa fa-arrow-circle-up fa-3x"></i>
                            </div>
                        </li>
                        <li class="tab1"><a href="javascript:void(0)"  onclick="View(1,'DATA PRIBADI')">Data Pribadi</a></li>
                        <li class="tab2"><a href="javascript:void(0)"  onclick="View(2,'DATA JABATAN')">Jabatan</a></li>
                        <li class="tab3"><a href="javascript:void(0)"  onclick="View(3,'DATA KELUARGA')">Data Keluarga</a></li>
                        <li class="tab4"><a href="javascript:void(0)"  onclick="View(4,'DATA HARTA')">Harta</a></li>
                        <li class="tab5"><a href="javascript:void(0)"  onclick="View(5,'DATA PENERIMAAN')">Penerimaan</a></li>
                        <li class="tab6"><a href="javascript:void(0)"  onclick="View(6,'DATA PENGELUARAN')">Pengeluaran</a></li>
                        <li class="tab7"><a href="javascript:void(0)"  onclick="View(7,'DATA FASIILITAS')">Fasilitas</a></li>
                        <li class="tab8"><a href="javascript:void(0)"  onclick="View(8,'REVIEW LAMPIRAN')">Review Lampiran</a></li>
                        <li>
                            <div class="action" id="btn-next">
                                <i alt="Lanjut" title="Lanjut" class="fa fa-arrow-circle-down fa-3x"></i>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-10">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                           <h3 class="box-title"></h3>
                           <input type="hidden" id="current"/>
                        </div> 
                        <div id="container"></div>
                    </div>  
                </div>
            </div>
        </div>    
    </div>
</section>
<div id="ModalLoading" class="modal fade" role="dialog">
    <div class='modal-dialog loader'>    
    </div>
</div>
<div id="ModalWarning" class="modal fade" role="dialog">
    <div class='modal-dialog' style="margin:15% auto">    
       <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pemberitahuan</h4>
            </div> 
            <div class="modal-body">
              <div class="alert alert-danger">
                  <i class="fa fa-warning" aria-hidden"true"=""></i>
                  <span id="notif-text"></span>
              </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
            </div>
       </div>
    </div>
</div>
<script type="text/javascript">
    var base_url = '<?php echo base_url();?>';
    var date_now = '<?php echo date("d/m/Y");?>';
</script>
<?php
    echo $this->load->view('filing/v_index/v_include_js', [], TRUE);
?>