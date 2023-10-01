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

                    <?php
                    
                    if($this->session->userdata('ID_ROLE')==ID_ROLE_ADMAPP){
                        ?>
                        <h4>AS Administrator</h4><br>
                        Jml PN Yang Sudah mengsubmit LHKPN, sedang memproses<br>
                    <?php
                    }
                    if($this->session->userdata('ID_ROLE')==ID_ROLE_AK){
                        ?>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <b>Data LHKPN 2015</b><br>
                            <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                <thead>
                                    <tr>
                                        <th>Instansi</th>
                                        <th>LHKPN Masuk</th>
                                        <th>Belum Mengirim LHKPN</th>
                                        <th>Ketaatan</th>
                                        <th>Diverifikasi</th>
                                        <th>Belum Diverifikasi</th>
                                        <th>TBN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><b>KPK</b></td>
                                        <td align="center">10</td>
                                        <td align="center">3</td>
                                        <td align="center">80%</td>
                                        <td align="center">7</td>
                                        <td align="center">3</td>
                                        <td align="center">5</td>
                                    </tr>
                                    <tr>
                                        <td><b>BPK</b></td>
                                        <td align="center">50</td>
                                        <td align="center">6</td>
                                        <td align="center">79%</td>
                                        <td align="center">30</td>
                                        <td align="center">20</td>
                                        <td align="center">15</td>
                                    </tr>
                                    <tr>
                                        <td><b>Kelautan</b></td>
                                        <td align="center">50</td>
                                        <td align="center">6</td>
                                        <td align="center">87%</td>
                                        <td align="center">30</td>
                                        <td align="center">20</td>
                                        <td align="center">15</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php
                    }
                    if($this->session->userdata('ID_ROLE')==ID_ROLE_AI){
                        ?>
                        <?php
                    }
                    if($this->session->userdata('ID_ROLE')==ID_ROLE_UI){
                        ?>
                        <?php
                    }
                    if($this->session->userdata('ID_ROLE')==ID_ROLE_PN){
                        ?>
                        <h4>AS PN</h4><br>
                        LHKPN, sedang diproses<br>
                        Ketaatan<br>
                        <?php
                    }
                    if($this->session->userdata('ID_ROLE')==ID_ROLE_VER){
                        ?>
                        <h4>AS Verifikator</h4><br>
                        <?php
                    }
                    ?>

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->