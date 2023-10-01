
<!DOCTYPE html><html>
    <head>
        <title>e-LHKPN</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Waditra Reka Cipta">
        <link href="<?php echo base_url(); ?>portal-assets/img/favicon.ico" rel="shortcut icon" type="image/x-icon">

        <link rel="stylesheet" href="<?php echo base_url(); ?>portal-assets/css/bootstrap.min.css" type="text/css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>portal-assets/font-awesome/css/font-awesome.min.css" type="text/css">
        <style>
            /* Error Page Inline Styles */
            body {
                background-image: url('<?php echo base_url(); ?>portal-assets/img/gedung1.jpg');
                background-size: cover;
                color: white;
            }
            /* Layout */
            .jumbotron {
                font-size: 21px;
                font-weight: 200;
                line-height: 2.1428571435;
                /*color: inherit;*/
                color: white;
                padding: 10px 0px;
            }
            /* Everything but the jumbotron gets side spacing for mobile-first views */
            .masthead, .body-content {
                padding-left: 15px;
                padding-right: 15px;
            }
            /* Main marketing message and sign up button */
            .jumbotron {
                text-align: center;
                background-color: transparent;
            }
            .jumbotron .btn {
                font-size: 21px;
                padding: 14px 24px;
            }

            .overlaybackground{
                background: rgba(6,6,6,0.4);

                position:fixed;
                padding:0;
                margin:0;

                top:0;
                left:0;

                width: 100%;
                height: 100%;
            }

            /* Colors */
            .green {color:#5cb85c;}
            .orange {color:#f0ad4e;}
            .red {color:#d9534f;}
        </style>
    </head>
    <body>
        <div class="overlaybackground">
            <div class="container">
                <!-- Jumbotron -->
                <div class="jumbotron">
                    <h1><i class="fa fa-frown-o red"></i> Upss ..</h1>
                    <p class="lead">Jaringan sedang sibuk silahkan tunggu beberapa saat atau hubungi administrator <em><span id="display-domain"></span></em>.</p>
                </div>
            </div>
            <div class="container">
                <div class="body-content">
                    <div class="row">
                        <div class="col-md-6">
                            <h2>Apa yang sedang terjadi?</h2>
                            <p class="lead">Halaman ini mengindikasikan bahwa terjadi insiden yang harus segera dilaporkan ke administrator.</p>
                        </div>
                        <div class="col-md-6">
                            <h2>Apa yang harus dilakukan ?</h2>
                            <p class="lead">Jika anda Penyelenggara Negara</p>
                            <p>Hubungi Admin Instansi atau unit kerja anda.</p>
                            <p class="lead">Jika anda Admin Instansi atau unit kerja</p>
                            <p>Hubungi Admin KPK.</p>
                            <br />
                            <br />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <!-- jQuery -->
    <script src="<?php echo base_url(); ?>portal-assets/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url(); ?>portal-assets/js/bootstrap.min.js"></script>
</html>