<div class="container">
    <div class="panel panel-default">

            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">

                            <div class="box-body">
                                <table id="dt_complete" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                    <thead>
                                        <tr>
                                            <th width="5%">No.</th>
                                            <th>NIP</th>
                                            <th>NIK</th>
                                            <th>Nama Lengkap</th>
                                            <th>Unit Kerja</th>
                                            <th>Jabatan</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Tanggal Lahir</th>
                                            <th>No Hp</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
//                                        $i = 0;
//                                        foreach ($list as $data):
//                                            $i++;
                                            ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        <?php // endforeach; ?>
                                    </tbody>
                                </table>
                            </div><!-- /.box-body -->

                        </div>
                    </div><!-- /.box -->
                </div><!-- /.col -->
        </div><!-- /.row -->
        </section><!-- /.content -->

        <script type="text/javascript">
            $(function() {
                $('#dt_complete').dataTable({
                });
            });
        </script>

    </div>
</div>
</div> <!-- /container -->
