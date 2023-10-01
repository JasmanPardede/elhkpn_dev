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
 * @package Views/lhkpn
*/
// echo $id_lhkpn;
// print_r($item);
?>

<!-- Pines Steps -->
<!--script src="<?php echo base_url();?>js/google-code-prettify/prettify.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/google-code-prettify/lang-css.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/jquery.psteps.js" type="text/javascript"></script>

<link href="<?php echo base_url();?>css/bootstrap.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    
    /* ##psteps_simple# */
    /* ##psteps_add_validation# */
    /* ##psteps_strict# */
    /* ##psteps_conditional_validation# */
    /* ##psteps_simple_horiz_layout# */
    /* ##psteps_horiz_layout# */
    /* ##psteps_circle_steps_simple# */
    /* ##psteps_circle_steps# */
    .step-title {
        min-height: 20px;
        float:left;
        border-radius: 0;
    }
    .next-button, .submit-button, .back-button {
        float:right;
        margin:3px;
    }
    @media (max-width:600px) {
        .step-content {
            margin-top: 10px;
        }
    }
    /* #psteps_simple## */
    /* #psteps_add_validation## */
    /* #psteps_strict## */
    /* #psteps_conditional_validation## */
    /* #psteps_simple_horiz_layout## */
    /* #psteps_horiz_layout## */
    /* #psteps_circle_steps_simple## */
    /* #psteps_circle_steps## */

    /* ##psteps_simple# */
    /* Vertical Styles */
    #psteps_simple .step-title {
        padding-left: 0;
        padding-right: 0;
        width: 99.5% !important;
        min-height: 28px;
        display: block;
    }
    #psteps_simple .step-title .step-order {
        float: left;
        margin-left: 12%;
    }
    #psteps_simple .step-title .step-name {
        float: left;
        margin-left: 2%;
    }
    #psteps_simple .step-title [class^="icon-"] {
        float: right;
        margin-right: 7%;
        margin-top: 2%;
    }
    /* #psteps_simple## */
    
    /* #psteps_strict## */
    /* ##psteps_conditional_validation# */
    /* Vertical Styles */
    #psteps_conditional_validation .step-title {
        padding-left: 0;
        padding-right: 0;
        width: 99.5% !important;
        min-height: 28px;
        display: block;
    }
    #psteps_conditional_validation .step-title .step-order {
        float: left;
        margin-left: 12%;
    }
    #psteps_conditional_validation .step-title .step-name {
        float: left;
        margin-left: 2%;
    }
    #psteps_conditional_validation .step-title [class^="icon-"] {
        float: right;
        margin-right: 7%;
        margin-top: 2%;
    }
    /* #psteps_conditional_validation## */

    /* ##psteps_circle_steps_simple# */
    /* ##psteps_circle_steps# */
    .psteps_circle_titles .circle-step {
        width: 30px;
        height: 30px;
        padding: 10px;
        line-height: 9px;
        font-size: 14px;
        border-radius: 500px 500px 500px 500px;
        min-height:30px;
        float:left;
    }
    .psteps_circle_titles .step-line {
        border-bottom: 1px solid #ddd;
        display: inline-block;
        /*width: 20%;*/
        width: 40px;
        float:left;
        margin-top:15px;
    }
    .psteps_circle_titles .step-line-sub {
        border-bottom: 1px solid #ddd;
        display: inline-block;
        /*width: 20%;*/
        width: 8px;
        float:left;
        margin-top:15px;
    }
    .psteps_circle_contents {
        background: #F9F9F9;
        border: 1px dashed #CCCCCC;
        margin: 15px 0 0;
        padding: 20px;
    }
    /* #psteps_circle_steps## */
    .before-heading {
        margin-top: -32px;
        width: 99%;
    }
    /* #psteps_circle_steps_simple## */

    .colored-box {
        display:inline-block;
        cursor:pointer;
        width:50px;
        height:50px;
        opacity: .5;
    }

    .btn-group {
        margin: 10px;
    }
    
    .btn{
        -webkit-linear-gradient(top, #F9F927, #E6D828);
    }
    
    .step-order-sub{
        margin-left: -5px;
    }
</style>
<script type="text/javascript">
    $(function(){
        _alert = window.alert;
        window.alert = function(message) {
            $.pnotify({
                title: "Alert",
                text: message
            });
        };


        var configure_circle_lines = function(circle_container){
            $(window).resize(function(){
                setTimeout(function(){
                    var step_lines = circle_container.find('.step-line'),
                        num_circles = circle_container.find('.step-title').length,
                        container_width = circle_container.width(),
                        circle_widths = (circle_container.find('.step-title').outerWidth()) * num_circles,
                        line_width = Math.floor((container_width - circle_widths) / (num_circles - 1));
                    step_lines.width((line_width < 1) ? 0 : (line_width-1));
                }, 200);
            }).resize();
        }

        $('#psteps_circle_steps_simple').psteps({
            traverse_titles: 'visited',
            steps_width_percentage: false,
            content_headings: true,
            step_names: false,
            check_marks: false,
            content_headings_after: '.before-heading'
        });


        configure_circle_lines($('.psteps_circle_titles', '#psteps_circle_steps_simple'));
        /*
        * And that's it. :)
        */
        $('#psteps_circle_steps').psteps({
            traverse_titles: 'never',
            steps_width_percentage: false,
            alter_width_at_viewport: '600',
            content_height_equalize: true,
            content_headings: true,
            back: false,
            step_names: false,
            check_marks: false,
            validate_errors: false,
            validate_next_step: true,
            ignore_errors_on_next: true,
            ignore_errors_on_submit: true,
            steps_show: function(){
                var cur_step = $(this);
                if (!cur_step.hasClass('pstep1'))
                    cur_step.find('input.step-validate').focus();
            },
            validation_rule: function(){
                var cur_step = $(this);
                var validate_answers = function(cur_step, question, answer) {
                    var active = cur_step.hasClass('step-active');
                    var input = cur_step.find('.step-validate:[name=circle_'+question+']');
                    var input_val = input.val();
                    var answer = answer;
                    if (input_val == '') {
                        return false
                    } else if (input_val != answer) {
                        input.hide();
                        input.nextAll('.step-answer:first').remove();
                        input.after('<div class="step-answer">You answered: <strong>'+input_val+'</strong> <i class="icon-asterisk pf-required"></i><br/><br/>The correct answer is <strong>'+answer+'</strong>.</div>');
                        return 'error';
                    } else {
                        input.hide();
                        input.nextAll('.step-answer:first').remove();
                        input.after('<div class="step-answer">You answered: <strong>'+input_val+'</strong> <i class="icon-asterisk pf-completed"></i><br/><br/>The correct answer is <strong>'+answer+'</strong>.</div>');
                        return true;
                    }
                }

                if (cur_step.hasClass('pstep1'))
                    return validate_answers(cur_step, '1','5');

                if (cur_step.hasClass('pstep2'))
                    return validate_answers(cur_step, '2','5');

                if (cur_step.hasClass('pstep3'))
                    return validate_answers(cur_step, '3','56');

                if (cur_step.hasClass('pstep4'))
                    return validate_answers(cur_step, '4','12');

                if (cur_step.hasClass('pstep5'))
                    return validate_answers(cur_step, '5','25');

                if (cur_step.hasClass('pstep6'))
                    return validate_answers(cur_step, '6','-10');

                if (cur_step.hasClass('pstep7'))
                    return validate_answers(cur_step, '7','15');

                if (cur_step.hasClass('pstep8'))
                    return validate_answers(cur_step, '8','30');

                if (cur_step.hasClass('pstep9'))
                    return validate_answers(cur_step, '9','4');

                if (cur_step.hasClass('pstep10'))
                    return validate_answers(cur_step, '10','35');

                if (cur_step.hasClass('pstep11'))
                    return validate_answers(cur_step, '11','51');

            },
            before_next: "Please provide an answer to the math problem.",
            before_submit: "Please complete all sections before submitting.",
            load_after_steps: function(){
                var psteps = $(this),
                    after_steps = psteps.find('.after-steps'),
                    step_contents = psteps.find('.step-content'),
                    step_titles = psteps.find('.step-title');

                step_contents.hide();

                var num_steps = step_titles.length,
                    num_steps_right = psteps.find('.step-title.btn-success').length;

                after_steps.find('.num-questions').html(num_steps);
                after_steps.find('.results-answered').html(num_steps_right);

                var percentage = Math.round( (num_steps_right / num_steps) * 100);
                if (percentage >= 80 && percentage < 90)
                    percentage = "an "+percentage;
                else
                    percentage = "a "+percentage;

                after_steps.find('.results-percentage').html(percentage);

                after_steps.show();
                step_titles.addClass('disabled');

                var submit_button = psteps.find('.submit-button');
                submit_button.hide();

                var back_button = psteps.find('.back-button');
                back_button.hide();

                var review_button = $('<button class="btn btn-info" style="display: block; cursor: pointer; float:right; margin: 3px;">Review</button>');
                back_button.after(review_button);
                review_button.click(function(){
                    psteps.trigger('traverse_visited.psteps');
                    if (psteps.find('.step-title.step-error').length > 0)
                        psteps.trigger('go_to_first_error.psteps');
                    else
                        psteps.find('.step-title:first').trigger('go_to_step.psteps');
                    $(this).remove();
                });
            }
        }).find('.submit-button').click(function(){
            $(this).trigger('load_after_steps.psteps');
        });

        configure_circle_lines($('.psteps_circle_titles', '#psteps_circle_steps'));
    });
</script>
                    <section class="content">
                      <div>
                        <div class="col-md-12">
                          <div class="box">
                            <div class="box-body pad">
                               <div id="psteps_circle_steps_simple" class="pf-form">
                                    <div class="row-fluid" style="margin-bottom:30px;">
                                        <div class="psteps_circle_titles">
                                            <div class="step-title circle-step"><span class="step-order">1</span><span class="step-name hide">Page 1</span></div><div class="step-line"></div>
                                            <div class="step-title circle-step"><span class="step-order">2</span><span class="step-name hide">Page 2</span></div><div class="step-line"></div>
                                            <div class="step-title circle-step"><span class="step-order">3</span><span class="step-name hide">Page 3</span></div><div class="step-line"></div>
                                            <div class="step-title circle-step"><span class="step-order step-order-sub">4a</span><span class="step-name hide">Page 4a</span></div><div class="step-line-sub"></div>
                                            <div class="step-title circle-step"><span class="step-order step-order-sub">4b</span><span class="step-name hide">Page 4b</span></div><div class="step-line-sub"></div>
                                            <div class="step-title circle-step"><span class="step-order step-order-sub">4c</span><span class="step-name hide">Page 4c</span></div><div class="step-line-sub"></div>
                                            <div class="step-title circle-step"><span class="step-order step-order-sub">4d</span><span class="step-name hide">Page 4d</span></div><div class="step-line-sub"></div>
                                            <div class="step-title circle-step"><span class="step-order step-order-sub">4e</span><span class="step-name hide">Page 4e</span></div><div class="step-line-sub"></div>
                                            <div class="step-title circle-step"><span class="step-order step-order-sub">4f</span><span class="step-name hide">Page 4f</span></div><div class="step-line-sub"></div>
                                            <div class="step-title circle-step"><span class="step-order step-order-sub">4g</span><span class="step-name hide">Page 4g</span></div><div class="step-line"></div>
                                            <div class="step-title circle-step"><span class="step-order">5</span><span class="step-name hide">Page 5</span></div><div class="step-line"></div>
                                            <div class="step-title circle-step"><span class="step-order">6</span><span class="step-name hide">Page 6</span></div><div class="step-line"></div>
                                            <div class="step-title circle-step"><span class="step-order step-order-sub">7a</span><span class="step-name hide">Page 7a</span></div><div class="step-line-sub"></div>
                                            <div class="step-title circle-step"><span class="step-order step-order-sub">7b</span><span class="step-name hide">Page 7b</span></div><div class="step-line-sub"></div>
                                            <div class="step-title circle-step"><span class="step-order step-order-sub">7c</span><span class="step-name hide">Page 7c</span></div><div class="step-line-sub"></div>
                                            <div class="step-title circle-step"><span class="step-order step-order-sub">7d</span><span class="step-name hide">Page 7d</span></div><div class="step-line"></div>
                                            <div class="step-title circle-step"><span class="step-order">8</span><span class="step-name hide">Page 8</span></div>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="clearfix psteps_circle_contents">
                                            <div class="step-content">
                                                <div class="before-heading alert alert-success"><i class="icon-pencil"></i>I. RINGKASAN LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA</div>
                                                Content 1...
                                            </div>
                                            <div class="step-content">
                                                <div class="before-heading alert alert-success"><i class="icon-phone"></i>II. Data Pribadi</div>
                                                Content 2...
                                            </div>
                                            <div class="step-content">
                                                <div class="before-heading alert alert-success"><i class="icon-qrcode"></i>III. Data Keluarga Inti</div>
                                                Content 3...
                                            </div>
                                            <div class="step-content">
                                                <div class="before-heading alert alert-success"><i class="icon-qrcode"></i>IV.1 Harta Tidak Bergerak (Tanah dan/atau bangunan)</div>
                                                Content 4a... There is no before heading section on this step.
                                            </div>
                                            <div class="step-content">
                                                <div class="before-heading alert alert-success"><i class="icon-qrcode"></i>IV.2.1 Harta Bergerak (Alat Transportasi dan mesin)</div>
                                                Content 4b... There is no before heading section on this step.
                                            </div>
                                            <div class="step-content">
                                                <div class="before-heading alert alert-success"><i class="icon-qrcode"></i>IV.2.2  Harta Bergerak (Perabotan Rumah Tangga, Barang Elektronik, Perhiasan & Logam/batu Mulia, Barang Seni/antik, Persediaan Dan Harta Bergerak Lainnya)</div>
                                                Content 4c... There is no before heading section on this step.
                                            </div>
                                            <div class="step-content">
                                                <div class="before-heading alert alert-success"><i class="icon-qrcode"></i>IV.3 Surat Berharga</div>
                                                Content 5...
                                            </div>
                                            <div class="step-content">
                                                <div class="before-heading alert alert-success"><i class="icon-qrcode"></i>IV.4 Kas dan Setara Kas</div>
                                                Content 5...
                                            </div>
                                            <div class="step-content">
                                                <div class="before-heading alert alert-success"><i class="icon-qrcode"></i>IV.5 Harta Lainnya</div>
                                                Content 5...
                                            </div>
                                            <div class="step-content">
                                                <div class="before-heading alert alert-success"><i class="icon-qrcode"></i>IV.6 Hutang</div>
                                                Content 6...
                                            </div>
                                            <div class="step-content">
                                                <div class="before-heading alert alert-success"><i class="icon-qrcode"></i>V. INFORMASI PENERIMAAN KAS</div>
                                                Content 7...
                                            </div>
                                            <div class="step-content">
                                                <div class="before-heading alert alert-success"><i class="icon-qrcode"></i>VI. INFORMASI PENGELUARAN KAS</div>
                                                Content 8...
                                            </div>
                                            <div class="step-content">
                                                <div class="before-heading alert alert-success"><i class="icon-qrcode"></i>LAMPIRAN 1. INFORMASI PENJUALAN/PELEPASAN HARTA KEKAYAAN DAN PENERIMAAN HIBAH DALAM SETAHUN</div>
                                                Content 9...
                                            </div>
                                            <div class="step-content">
                                                <div class="before-heading alert alert-success"><i class="icon-qrcode"></i>LAMPIRAN 2. INFORMASI PENERIMAAN FASILITAS DALAM SETAHUN</div>
                                                Content 10...
                                            </div>
                                            <div class="step-content">
                                                <div class="before-heading alert alert-success"><i class="icon-qrcode"></i>LAMPIRAN 3. SURAT KUASA MENGUMUMKAN</div>
                                                Content 11...
                                            </div>
                                            <div class="step-content">
                                                <div class="before-heading alert alert-success"><i class="icon-qrcode"></i>LAMPIRAN 4. SURAT KUASA</div>
                                                Content 12...
                                            </div>
                                            <div class="step-content">
                                                <div class="before-heading alert alert-success"><i class="icon-qrcode"></i>Dokumen Pendukung</div>
                                                Content 13...
                                            </div>
                                            <div style="clear:both;"></div>
                                            <button class="next-button btn">Next</button>
                                            <button class="submit-button btn">Done</button>
                                            <button class="back-button btn">Back</button>
                                        </div>
                                    </div>
                                </div>   
                            </div>
                          </div>
                        </div>
                      </div>
                    </section>
            <script src="<?php echo base_url();?>plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script-->

<link rel="stylesheet" href="<?php echo base_url();?>css/mini-menu.css" />

Jenis Laporan <?php echo $item->JENIS_LAPORAN;?><br>
Laporan LHKPN <?php echo $item->TGL_LAPOR;?><br>
Nama PN <?php echo $item->ID_PN;?><br>


<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        LHKPN
        <small>5 Kali</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#index.php/efill/lhkpn/entry/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">LHKPN</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <button class='menu_lhkpn btn btn-sm btn-danger' onclick="togglerow()" id="button_hide">Hide Menu</button>
    <div class="row">
        <div class="col-md-3" id='menu_lhkpn_row'>
            <!-- <a href="mailbox.html" class="btn btn-primary btn-block margin-bottom">Back to Inbox</a> -->

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/1">I. RINGKASAN LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA</a></h3>
                    <div class='box-tools'>
                        <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div>
                </div>
                <div class="box-body no-padding">
                </div><!-- /.box-body -->
            </div><!-- /. box -->

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/2">II. Data Pribadi</a></h3>
                    <div class='box-tools'>
                        <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div>
                </div>
                <div class="box-body no-padding">
                </div><!-- /.box-body -->
            </div><!-- /. box -->

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/3">III. Data Keluarga Inti</a></h3>
                    <div class='box-tools'>
                        <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div>
                </div>
                <div class="box-body no-padding">
                </div><!-- /.box-body -->
            </div><!-- /. box -->


            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">IV. Harta Kekayaan</h3>
                    <div class='box-tools'>
                        <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/4"><i class="fa fa-envelope-o"></i>IV.1 Harta Tidak Bergerak (Tanah dan/atau bangunan)</a></li>
                        <li><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/5"><i class="fa fa-envelope-o"></i>IV.2.1 Harta Bergerak (Alat Transportasi dan mesin)</a></li>
                        <li><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/6"></i>IV.2.2  Harta Bergerak (Perabotan Rumah Tangga, Barang Elektronik, Perhiasan & Logam/batu Mulia, Barang Seni/antik, Persediaan Dan Harta Bergerak Lainnya)</a></li>
                        <li><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/7"><i class="fa fa-envelope-o"></i>IV.3 Surat Berharga</a></li>
                        <li><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/8"><i class="fa fa-envelope-o"></i>IV.4 Kas dan Setara Kas</a></li>
                        <li><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/9"><i class="fa fa-envelope-o"></i>IV.5 Harta Lainnya</a></li>
                        <li><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/10"><i class="fa fa-envelope-o"></i>IV.6 Hutang</a></li>
                    </ul>
                </div><!-- /.box-body -->
            </div><!-- /. box -->

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/11">V. INFORMASI PENERIMAAN KAS</a></h3>
                    <div class='box-tools'>
                        <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div>
                </div>
                <div class="box-body no-padding">
                </div><!-- /.box-body -->
            </div><!-- /. box -->

            <div class="box box-solid">
                <div class="box-header with-border INFORMASI PENGELUARAN KAS">
                    <h3 class="box-title"><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/12">VI. INFORMASI PENGELUARAN KAS</a></h3>
                    <div class='box-tools'>
                        <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div>
                </div>
                <div class="box-body no-padding">
                </div><!-- /.box-body -->
            </div><!-- /. box -->

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">LAMPIRAN</h3>
                    <div class='box-tools'>
                        <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/13"><i class="fa fa-envelope-o"></i>LAMPIRAN 1. INFORMASI PENJUALAN/PELEPASAN HARTA KEKAYAAN DAN PENERIMAAN HIBAH DALAM SETAHUN</a></li>
                        <li><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/14"><i class="fa fa-envelope-o"></i>LAMPIRAN 2. INFORMASI PENERIMAAN FASILITAS DALAM SETAHUN</a></li>
                        <li><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/15"><i class="fa fa-envelope-o"></i>LAMPIRAN 3. SURAT KUASA MENGUMUMKAN</a></li>
                        <li><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/16"><i class="fa fa-envelope-o"></i>LAMPIRAN 4. SURAT KUASA</a></li>
                    </ul>
                </div><!-- /.box-body -->
            </div><!-- /. box -->

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/17">Dokumen Pendukung</a></h3>
                    <div class='box-tools'>
                        <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div>
                </div>
                <div class="box-body no-padding">
                </div><!-- /.box-body -->
            </div><!-- /. box -->

        </div><!-- /.col -->


        <div class="col-md-1" style="display:none;" id='menu_lhkpn_mini_row'>
            <!-- <a href="mailbox.html" class="btn btn-primary btn-block margin-bottom">Back to Inbox</a> -->

            <div class="box box-solid sidebox1">
                <a href="javascript:;" onClick="showTable(this);" class="tooltips" title="RINGKASAN LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA" data-url="index.php/efill/lhkpn/showTable/1">
                <div class="box-header with-border">
                    <h3 id='boxtitle1' class="box-title">I</h3><h5 class="box-title" id='sidetitle1' style="display:none"> RINGKASAN LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA</h5>
                </div>
                </a>
            </div><!-- /. box -->

            <div class="box box-solid sidebox2">
                <a href="javascript:;" title='Data Pribadi' class="tooltips" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/2">
                <div class="box-header with-border">
                    <h3 id='boxtitle2' class="box-title">II</h3><h5 class="box-title" id='sidetitle2' style="display:none"> Data Pribadi</h5>
                </div>
                </a>
            </div><!-- /. box -->

            <div class="box box-solid sidebox3">
                <a href="javascript:;" onClick="showTable(this);" title="Data Keluarga Inti" class="tooltips" data-url="index.php/efill/lhkpn/showTable/3">
                <div class="box-header with-border">
                    <h3 id='boxtitle3' class="box-title">III</h3><h5 class="box-title" id='sidetitle3' style="display:none"> Data Keluarga Inti</h5>
                </div>
                </a>
            </div><!-- /. box -->


            <div class="box box-solid">
                <div class="box-header with-border tooltips" title='Harta Kekayaan'>
                    <h3 class="box-title">IV</h3>
                    <div class='box-tools'>
                        <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li class="tooltips sidebox4" title="Harta Tidak Bergerak (Tanah dan/atau bangunan)"><a  href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/4"><span id='boxtitle4'>IV.1</span><span id='sidetitle4' style="display:none">Harta Tidak Bergerak (Tanah dan/atau bangunan)</span></a></li>
                        <li class="tooltips sidebox5" title="Harta Bergerak (Alat Transportasi dan mesin)"><a  href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/5"><span id='boxtitle5'>IV.2.1</span><span id='sidetitle5' style="display:none">Harta Bergerak (Alat Transportasi dan mesin)</span></a></li>
                        <li class="tooltips sidebox6" title="Harta Bergerak (Perabotan Rumah Tangga, Barang Elektronik, Perhiasan & Logam/batu Mulia, Barang Seni/antik, Persediaan Dan Harta Bergerak Lainnya)"><a  href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/6"><span id='boxtitle6'>IV.2.2</span><span id='sidetitle6' style="display:none">Harta Bergerak (Perabotan Rumah Tangga, Barang Elektronik, Perhiasan & Logam/batu Mulia, Barang Seni/antik, Persediaan Dan Harta Bergerak Lainnya)</span></a></li>
                        <li class="tooltips sidebox7" title="Surat Berharga"><a  href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/7"><span id='boxtitle7'>IV.3</span><span id='sidetitle7' style="display:none">Surat Berharga</span></a></li>
                        <li class="tooltips sidebox8" title="Kas dan Setara Kas"><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/8"><span id='boxtitle8'>IV.4</span><span id='sidetitle8' style="display:none">Kas dan Setara Kas</span></a></li>
                        <li class="tooltips sidebox9" title="Harta Lainnya"><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/9"><span id='boxtitle9'>IV.5</span><span id='sidetitle9' style="display:none">Harta Lainnya</span></a></li>
                        <li class="tooltips sidebox10" title="Hutang"><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/10"><span id='boxtitle10'>IV.6</span><span id='sidetitle10' style="display:none">Hutang</span></a></li>
                    </ul>
                </div><!-- /.box-body -->
            </div><!-- /. box -->

            <div class="box box-solid sidebox11">
                <a href="javascript:;" class="tooltips" title="INFORMASI PENERIMAAN KAS" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/11">
                <div class="box-header with-border">
                    <h3 class="box-title" id='boxtitle11'>V</h3><h5 class="box-title" id='sidetitle11' style="display:none"> INFORMASI PENERIMAAN KAS</h5>
                </div>
                </a>
            </div><!-- /. box -->

            <div class="box box-solid sidebox12">
                <a href="javascript:;" title="INFORMASI PENGELUARAN KAS" class="tooltips" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/12">
                <div class="box-header with-border">
                    <h3 class="box-title" id='boxtitle12'>VI</h3><h5 class="box-title" id='sidetitle12' style="display:none"> INFORMASI PENGELUARAN KAS</h5>
                </div>
                </a>
            </div><!-- /. box -->

            <div class="box box-solid">
                <div class="box-header with-border" class="tooltips" title="LAMPIRAN">
                    <h3 class="box-title">VII</h3>
                    <div class='box-tools'>
                        <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li class="tooltips sidebox13" title="LAMPIRAN 1. INFORMASI PENJUALAN/PELEPASAN HARTA KEKAYAAN DAN PENERIMAAN HIBAH DALAM SETAHUN"><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/13"><i class="fa fa-envelope-o" id='boxtitle13'></i><span id='sidetitle13' style="display:none">LAMPIRAN 1. INFORMASI PENJUALAN/PELEPASAN HARTA KEKAYAAN DAN PENERIMAAN HIBAH DALAM SETAHUN</span></a></li>
                        <li class="tooltips sidebox14" title="LAMPIRAN 2. INFORMASI PENERIMAAN FASILITAS DALAM SETAHUN"><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/14"><i class="fa fa-envelope-o" id='boxtitle14'></i><span id='sidetitle14' style="display:none">LAMPIRAN 2. INFORMASI PENERIMAAN FASILITAS DALAM SETAHUN</span></a></li>
                        <li class="tooltips sidebox15" title="LAMPIRAN 3. SURAT KUASA MENGUMUMKAN"><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/15"><i class="fa fa-envelope-o" id='boxtitle15'></i><span id='sidetitle15' style="display:none">LAMPIRAN 3. SURAT KUASA MENGUMUMKAN</span></a></li>
                        <li class="tooltips sidebox16" title="LAMPIRAN 4. SURAT KUASA"><a href="javascript:;" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/16"><i class="fa fa-envelope-o" id='boxtitle16'></i><span id='sidetitle16' style="display:none">LAMPIRAN 4. SURAT KUASA</span></a></li>
                    </ul>
                </div><!-- /.box-body -->
            </div><!-- /. box -->

            <div class="box box-solid sidebox17">
                <a href="javascript:;" title="Dokumen Pendukung" onClick="showTable(this);" data-url="index.php/efill/lhkpn/showTable/17">
                <div class="box-header with-border">
                    <h3 class="box-title" id='boxtitle17'>VIII</h3><h5 class="box-title" id='sidetitle17' style="display:none"> Dokumen Pendukung</h5>
                </div>
                </a>
            </div><!-- /. box -->

        </div><!-- /.col -->

        <!-- Main -->

        <div class="col-md-9" id="block">
            <div class="box box-primary" id="container">

            </div><!-- /. box -->
        </div><!-- /.col -->

        <!-- /.Main -->
    </div><!-- /.row -->

    <div class="row" style="text-align: center;">
        <button type="button" class="btn btn-sm btn-default" onclick="OpenModalBox('Konfirmasi', 'Data Yang Telah Dikirim Tidak dapat diperbaharui!', '');">Kirim Ke KPK</button>
      
    </div>

</section><!-- /.content -->


<script src="<?php echo base_url();?>js/mini-menu.js"></script>
<script type="text/javascript">
    var statusmenu = 1;

    function togglerow()
    {
        if(statusmenu == 1)
        {
            document.getElementById("button_hide").className = "menu_lhkpn btn btn-sm btn-default";
            document.getElementById("block").className = "col-md-11";
            document.getElementById("button_hide").innerHTML = "Show Menu";
            statusmenu = 0;
            var counter = 0;
            var interval = setInterval(function() {
                counter++;
                // Display 'counter' wherever you want to display it.
                if (counter == 1) {
                    // Display a login box
                    $('#menu_lhkpn_mini_row').show('drop' , 0);
                    clearInterval(interval);
                }
            }, 1000);
            $('#menu_lhkpn_row').hide('drop' , 1000);
        }
        else
        {
            document.getElementById("button_hide").className = "menu_lhkpn btn btn-sm btn-danger";
            document.getElementById("button_hide").innerHTML = "Hide Menu";
            statusmenu = 1;
            var counter = 0;
            var interval = setInterval(function() {
                counter++;
                // Display 'counter' wherever you want to display it.
                if (counter == 1) {
                    // Display a login box
                    document.getElementById("block").className = "col-md-9";
                    $('#menu_lhkpn_row').show('drop' , 0);
                    clearInterval(interval);
                }
            }, 1000);
            $('#menu_lhkpn_mini_row').hide('drop' , 1000);
        }
        
    }

    function showTable(ele)
    {
        var url = $(ele).attr('data-url')+'<?php echo "/".$id_lhkpn;?>';
        $('#block').block({
            message: '<img src="images/loading.gif" width="100px" />'
        });

        $.get(url, function (html) {
            $('#container').html(html);
            $('#block').unblock();
        });
    }

    $('#block').block({
        message: '<img src="images/loading.gif" width="100px" />'
    });    
    var table = '1';
    var message = "<?=$this->session->flashdata('message');?>";
    if (message != '')
    {
        table = '2';
    };
    $.get('index.php/efill/lhkpn/showTable/'+table+'<?php echo "/".$id_lhkpn;?>', function (html) {
        $('#container').html(html);
        $('#block').unblock();
    });    
</script>