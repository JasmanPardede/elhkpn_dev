<link class="iniicon" href="<?php echo base_url('img/favicon.ico'); ?>" rel="shortcut icon" type="image/x-icon">
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        
    </h1>
    <ol class="breadcrumb">
        <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
    </ol>
</section>
<?php
$btn = ' btn-default ';
if (@$this->session->userdata('IS_KPK') == '1' || @$this->session->userdata('IS_INSTANSI') == '1') {
    $btn = ' btn-primary ';
}
?>

<link href="<?php echo base_url(); ?>plugins/wizard/bootstrap-responsive.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>plugins/wizard/bwizard.css" rel="stylesheet" />

<!-- Main content -->
<section class="content">
    <h1></h1>
    <div class="row">
        

        <div class="col-md-12">
            <div class="box">
            <div class="box-body">
            
            <button class="btn btn-sm btn-primary" id="btn-add" href="http://localhost/lhkpn/index.php/efill/lhkpnoffline/edit/penerimaan">
                        <i class="fa fa-plus"></i> Tambah Data
            </button>

            <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                        <tr>
                            <th width="30">No.</th>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>Last Login</th>
                            <th>Email / Handphone</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
            </table>


<script> 
$(document).ready(function(){

    $("#a").show();

    $("#pasienLama1").click(function () {
                $("#a").slideDown(500);
                $("#b").hide();
            });

    $("#pasienLama2").click(function () {
                $("#a").hide(500);
                $("#b").slideDown();
            });
});
</script>

<style> 
.panel {display: none;}
</style>


<div id="pasienLama1">a</div>
<div id="pasienLama2">b</div>

<div class="" id="a">Hello world! AAAA</div>
<div class="panel" id="b">Hello world! BBBB</div>





            <div class="wizard">
            <ol>
                <li>Large Paragraph</li>
                <li>Paragraph</li>
                <li>Unordered List</li>
                <li>Kitchen Sink</li>
            </ol>
            <div>
                <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>
            </div>
            <div>
                <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>
            </div>
            <div>
                <ul>
                   <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
                   <li>Aliquam tincidunt mauris eu risus.</li>
                   <li>Vestibulum auctor dapibus neque.</li>
                </ul>
            </div>
            <div>
                <h1>Kitchen Sink</h1>
                           
                <p><strong>Pellentesque habitant morbi tristique</strong> senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. <em>Aenean ultricies mi vitae est.</em> Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, <code>commodo vitae</code>, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. <a href="#">Donec non enim</a> in turpis pulvinar facilisis. Ut felis.</p>
                
                <h2>Header Level 2</h2>
                           
                <ol>
                   <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
                   <li>Aliquam tincidunt mauris eu risus.</li>
                </ol>
                
                <blockquote><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue. Ut a est eget ligula molestie gravida. Curabitur massa. Donec eleifend, libero at sagittis mollis, tellus est malesuada tellus, at luctus turpis elit sit amet quam. Vivamus pretium ornare est.</p></blockquote>
                
                <h3>Header Level 3</h3>
                
                <ul>
                   <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
                   <li>Aliquam tincidunt mauris eu risus.</li>
                </ul>
                
                <pre><code>
                #header h1 a { 
                    display: block; 
                    width: 300px; 
                    height: 80px; 
                }
                </code></pre>
            </div>
        </div>

            </div>
            </div>
        </div>
        </div>
        </div>
    </div>

    <script src="<?php echo base_url(); ?>plugins/wizard/bwizard.js" type="text/javascript"></script>
    <script type="text/javascript">
       $(".wizard").bwizard();
    </script>