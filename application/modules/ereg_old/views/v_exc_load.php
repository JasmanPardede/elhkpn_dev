<style>
    /* layout.css Style */
    .upload-drop-zone {
        height: 100px;
        border-width: 2px;
        margin-bottom: 20px;
    }

    /* skin.css Style*/
    .upload-drop-zone {
        color: #ccc;
        border-style: dashed;
        border-color: #ccc;
        line-height: 100px;
        text-align: center
    }
    .upload-drop-zone.drop {
        color: #222;
        border-color: #222;
    }

</style>

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Upload Files Excel</strong> <small>Upload Data PN/WL</small></div>
        <div class="panel-body">

            <!-- Standar Form -->
            <h4>Select files from your computer</h4>
            <form action="" method="post" enctype="multipart/form-data" id="js-upload-form">
                <div class="form-inline">
                    <div class="form-group">
                        <input type="file" name="files[]" id="js-upload-files" multiple>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary" id="js-upload-submit">Upload files</button>
                </div>
            </form>

            <!-- Drop Zone -->
            <h4>Or drag and drop files below</h4>
            <div class="upload-drop-zone" id="drop-zone">
                Just drag and drop files here
            </div>

            <!-- Progress Bar -->
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    <span class="sr-only">60% Complete</span>
                </div>
            </div>
            <!-- Upload Finished -->
            <div class="js-upload-finished">
                <h3>Processed files</h3>
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-success"><span class="badge alert-success pull-right">Success</span>data_pn.xls</a>
                </div>
            </div>

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

    </div>
</div>
</div> <!-- /container -->

<script>
    $(function() {
        'use strict';

// UPLOAD CLASS DEFINITION
// ======================

        var dropZone = document.getElementById('drop-zone');
        var uploadForm = document.getElementById('js-upload-form');

        var startUpload = function(files) {
            console.log(files)
        }

        uploadForm.addEventListener('submit', function(e) {
            var uploadFiles = document.getElementById('js-upload-files').files;
            e.preventDefault()

            startUpload(uploadFiles)
        })

        dropZone.ondrop = function(e) {
            e.preventDefault();
            this.className = 'upload-drop-zone';

            startUpload(e.dataTransfer.files)
        }

        dropZone.ondragover = function() {
            this.className = 'upload-drop-zone drop';
            return false;
        }

        dropZone.ondragleave = function() {
            this.className = 'upload-drop-zone';
            return false;
        }

    });
</script>