<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="box-tools">

                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">

                    <div class="row">

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">User</span>
                                    <span class="info-box-number"><?php echo $USER->JML; ?><!-- <small>%</small> --></span>
                                    <a href="#">Selengkapnya</a>
                                </div><!-- /.info-box-content -->
                            </div><!-- /.info-box -->
                        </div>

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Menu</span>
                                    <span class="info-box-number"><?php echo 'Menu'; ?><!-- <small>%</small> --></span>
                                    <a href="#">Selengkapnya</a>
                                </div><!-- /.info-box-content -->
                            </div><!-- /.info-box -->
                        </div>

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Role</span>
                                    <span class="info-box-number"><?php echo 'Role'; ?><!-- <small>%</small> --></span>
                                    <a href="#">Selengkapnya</a>
                                </div><!-- /.info-box-content -->
                            </div><!-- /.info-box -->
                        </div>

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Instansi</span>
                                    <span class="info-box-number"><?php echo $INSTANSI->JML; ?><!-- <small>%</small> --></span>
                                    <a href="#">Selengkapnya</a>
                                </div><!-- /.info-box-content -->
                            </div><!-- /.info-box -->
                        </div>                    

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Jabatan</span>
                                    <span class="info-box-number"><?php echo $JABATAN->JML; ?><!-- <small>%</small> --></span>
                                    <a href="#">Selengkapnya</a>
                                </div><!-- /.info-box-content -->
                            </div><!-- /.info-box -->
                        </div>

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">User Online</span>
                                    <span class="info-box-number"><?php echo 100; ?><!-- <small>%</small> --></span>
                                    <a href="#">Selengkapnya</a>
                                </div><!-- /.info-box-content -->
                            </div><!-- /.info-box -->
                        </div>
                    </div>

<!--                     <div class="row">
                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">

                            <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                <thead>
                                    <tr>
                                        <th>ROLE</th>
                                        <th>JUMLAH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($USERPERROLE as $userperrole) {
                                        ?>
                                        <tr>
                                            <td><?php echo $userperrole->ID_ROLE; ?></td>
                                            <td><?php echo $userperrole->JML; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div> -->

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->