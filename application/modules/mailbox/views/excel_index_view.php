<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Excel To Grid
        <small>Text Excel</small>
    </h1>
    <?php echo $breadcrumb; ?>
</section>


<!-- Main content -->
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
                            $i = 0;
                            foreach ($list as $data):
                                $i++;
                                ?>
                                <tr>
                                    <td><?php echo $data['A']; ?></td>
                                    <td><?php echo $data['B']; ?></td>
                                    <td><?php echo $data['C']; ?></td>
                                    <td><?php echo $data['D']; ?></td>
                                    <td><?php echo $data['E']; ?></td>
                                    <td><?php echo $data['F']; ?></td>
                                    <td><?php echo $data['G']; ?></td>
                                    <td><?php echo $data['H']; ?></td>
                                    <td><?php echo $data['I']; ?></td>
                                    <td><?php echo $data['J']; ?></td>
                                </tr>
                            <?php endforeach; ?>
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