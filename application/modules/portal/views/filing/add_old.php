<script type="text/javascript" src="<?php echo base_url();?>portal-assets/js/jquery.bootstrap.wizard.min.js"></script>
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
            <div id="wrapper-main">
                <div class="col-lg-2">
                    <ul class="nav nav-stacked affix nav-pills" id="sidebar">
                        <li><a href="#tab1" data-toggle="tab">Data Pribadi</a></li>
                        <li><a href="#tab2" data-toggle="tab">Jabatan</a></li>
                        <li><a href="#tab3" data-toggle="tab">Data Keluarga</a></li>
                        <li><a href="#tab4" data-toggle="tab">Harta</a></li>
                        <li><a href="#tab5" data-toggle="tab">Penerimaan</a></li>
                        <li><a href="#tab6" data-toggle="tab">Pengeluaran</a></li>
                        <li><a href="#tab7" data-toggle="tab">Fasilitas</a></li>
                        <li><a href="#tab8" data-toggle="tab">Review Lampiran</a></li>
                        <li><a href="#tab9" data-toggle="tab"> </a></li>
                    </ul>
                </div>
                <div class="col-lg-10 wrapper-border" >
                    <div class="row">
                        <div class="col-lg-10" id="f-progress" >
                            <label id="title-page">
                                Tambah LHKPN BARU
                            </label>
                            <div id="bar" class="progress progress-danger progress-striped active">
                                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                </div>
                            </div>
                        </div> 

                    </div>
                    <div class="tab-content" >
                        <div class="row tab-pane fade active in" id="tab1" >
                            <div class="col-lg-10" id="wizard_1" style="width:100%;">
                                <!--Data Pribadi-->
                                <div class="tabbable" id="tab-data-pribadi">
                                    <ul class="nav nav-tabs menu-filling">
                                        <li class="active"><a href="#data_pribadi">Data Pribadi</a></li>
                                        <li><a href="#data_kontak">Data Kontak</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade active in box box-primary" id="data_pribadi">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Data Pribadi</h3>
                                            </div>
                                            <form role="form">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-lg-5 text-center">
                                                            <img src="<?php echo base_url(); ?>portal-assets/img/avatar.png" class="img-circle" alt="User Image">
                                                        </div>
                                                        <div class="col-lg-5">
                                                            <div class="form-group">
                                                                <label for="exampleInputFile">Foto</label>
                                                                <input type="file" id="foto" data-allowed-file-extensions='["jpg", "jpeg","png"]'>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleInputFile">NPWP</label>
                                                                <input type="file" id="npwp" data-allowed-file-extensions='["jpg", "pdf"]'>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleInputFile">KTP</label>
                                                                <input type="file" id="ktp" data-allowed-file-extensions='["jpg", "pdf"]'>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-5">
                                                            <div class="form-group">
                                                                <label>NIK</label>
                                                                <input type="text" class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Nama Lengkap</label>
                                                                <input type="text" class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Jenis Kelamin</label>
                                                                <div class="radio">
                                                                    <label>
                                                                        <input type="radio" name="gender" id="gender" value="pria" checked="">
                                                                        Pria
                                                                    </label>
                                                                    <label>
                                                                        <input type="radio" name="gender" id="gender" value="wanita" checked="">
                                                                        Wanita
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Tempat Lahir</label>
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-5">
                                                            <div class="form-group">
                                                                <label>NPWP</label>
                                                                <input type="text" class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Status Kawin</label>
                                                                <div class="radio">
                                                                    <label>
                                                                        <input type="radio" name="status_nikah" id="status_nikah" value="1" checked="">
                                                                        Kawin
                                                                    </label>
                                                                    <label>
                                                                        <input type="radio" name="status_nikah" id="status_nikah" value="2" checked="">
                                                                        Belum Kawin
                                                                    </label>
                                                                    <label>
                                                                        <input type="radio" name="status_nikah" id="status_nikah" value="3" checked="">
                                                                        Janda / Duda
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Agama</label>
                                                                <select class="form-control" id="cb_agama">
                                                                    <option></option>
                                                                    <option>Islam</option>
                                                                    <option>Kristen</option>
                                                                    <option>Katolik</option>
                                                                    <option>Budha</option>
                                                                    <option>Hindu</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Tanggal Lahir</label>
                                                                <div class="input-group date">
                                                                    <div class="input-group-btn">
                                                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                                                    </div>
                                                                    <input type="text" name="date_last"  id="tgl_lahir" class="form-control" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="box-footer">
                                                    <div>
                                                        <ul>
                                                            <li class="next push-right">
                                                                <a href="#" class="btn btn-info btn-next-data-pribadi">
                                                                    Selanjutnya
                                                                    <i class="fa fa-forward"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <button class="btn btn-primary">
                                                        <i class="fa fa-save"></i>
                                                        Simpan Data Pribadi
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade box box-primary" id="data_kontak">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Data Kontak</h3>
                                            </div>
                                            <form role="form">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-lg-5">
                                                            <div class="form-group">
                                                                <label>Negara</label>
                                                                <select class="form-control">
                                                                    <option></option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Provinsi</label>
                                                                <select class="form-control">
                                                                    <option></option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Kota / Kabupaten</label>
                                                                <select class="form-control">
                                                                    <option></option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Kecamatan</label>
                                                                <select class="form-control">
                                                                    <option></option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Kelurahan</label>
                                                                <select class="form-control">
                                                                    <option></option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Alamat Rumah</label>
                                                                <textarea class="form-control" rows="4" placeholder="Enter ...">
                                                                </textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-5">
                                                            <div class="form-group">
                                                                <label>Nomor Telepon Rumah</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                                                                    <input type="email" class="form-control" >
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Email (Aktif)</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><i class="fa fa-envelope"></i></div>
                                                                    <input type="email" class="form-control" >
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Nomor Handphone </label>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><i class="fa fa-mobile-phone"></i></div>
                                                                    <input type="email" class="form-control" >
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Nomor Handphone (Bila ada)</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><i class="fa fa-mobile-phone"></i></div>
                                                                    <input type="email" class="form-control" >
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Nomor Handphone (Bila ada)</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><i class="fa fa-mobile-phone"></i></div>
                                                                    <input type="email" class="form-control" >
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Nomor Handphone (Bila ada)</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><i class="fa fa-mobile-phone"></i></div>
                                                                    <input type="email" class="form-control" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="box-footer">
                                                    <div>
                                                        <ul class="wizard">
                                                            <li class="previous">
                                                                <a href="#" class="btn btn-warning btn-prev-data-kontak">
                                                                    <i class="fa fa-backward"></i>
                                                                    Sebelumnya
                                                                </a>
                                                            </li>
                                                            <li class="next push-right">
                                                                <a href="#" class="btn btn-info">
                                                                    Selanjutnya
                                                                    <i class="fa fa-forward"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <button class="btn btn-primary">
                                                        <i class="fa fa-save"></i>
                                                        Simpan Data Kontak
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row tab-pane" id="tab2">
                            <div class="col-lg-10" style="width:100%;">
                                <!--Jabatan-->
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Data Jabatan</h3>
                                    </div>
                                    <div class="box-body">

                                        <span class="block-body">
                                            <a href="javscript:void(0)" id="add-jabatan" class="btn btn-info" data-remodal-target="FormJabatan">
                                                <i class="fa fa-plus"></i> Tambah
                                            </a>
                                        </span>

                                        <span class="block-body">
                                            <table id="TJabatan" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                                <thead>
                                                    <tr>
                                                        <th>NO</th>
                                                        <th>JABATAN - DESKRIPSI JABATAN / ESELON</th>
                                                        <th>LEMBAGA</th>
                                                        <th>UNIT KERJA</th>
                                                        <th>TMT/SD</th>
                                                        <th>FILE SK</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </span>


                                    </div>
                                    <div class="box-footer">
                                        <div>
                                            <ul class="wizard">
                                                <li class="previous">
                                                    <a href="#" class="btn btn-warning btn-prev-data-kontak">
                                                        <i class="fa fa-backward"></i>
                                                        Sebelumnya
                                                    </a>
                                                </li>
                                                <li class="next push-right">
                                                    <a href="#" class="btn btn-info">
                                                        Selanjutnya
                                                        <i class="fa fa-forward"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row tab-pane" id="tab3">
                            <div class="col-lg-10" style="width:100%;">
                                <!--Data Keluarga-->
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Data Keluarga</h3>
                                    </div>
                                    <div class="box-body">

                                        <span class="block-body">
                                            <a href="javscript:void(0)" id="add-jabatan" class="btn btn-info" data-remodal-target="FormKeluarga">
                                                <i class="fa fa-plus"></i> Tambah
                                            </a>
                                        </span>

                                        <span class="block-body">
                                            <table id="TKeluarga" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                                <thead>
                                                    <tr>
                                                        <th>NO</th>
                                                        <th>NAMA ISTRI / SUAMI / ANAK</th>
                                                        <th>TEMPAT & TANGGAL LAHIR / JENIS KELAMIN</th>
                                                        <th>PEKERJAAN</th>
                                                        <th>ALAMAT RUMAH / NOMOR TELEPON</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </span>


                                    </div>
                                    <div class="box-footer">
                                        <div>
                                            <ul class="wizard">
                                                <li class="previous">
                                                    <a href="#" class="btn btn-warning btn-prev-data-kontak">
                                                        <i class="fa fa-backward"></i>
                                                        Sebelumnya
                                                    </a>
                                                </li>
                                                <li class="next push-right">
                                                    <a href="#" class="btn btn-info">
                                                        Selanjutnya
                                                        <i class="fa fa-forward"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row tab-pane" id="tab4">
                            <div class="col-lg-10" style="width:100%;">
                                <!--Harta-->
                                 <div class="tabbable" id="tab-data-harta">
                                    <ul class="nav nav-tabs menu-filling">
                                        <li class="active"><a href="#data_tanah">Tanah /  Bangunan</a></li>
                                        <li><a href="#data_mesin">Alat Transportasi / Mesin</a></li>
                                        <li><a href="#data_hbergerak">Harta Bergerak</a></li>
                                        <li><a href="#data_sberharga">Surat Berharga</a></li>
                                        <li><a href="#data_kas">KAS / Setara KAS</a></li>
                                        <li><a href="#data_hlainnya">Harta Lainnya</a></li>
                                        <li><a href="#data_hutang">Hutang</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade active in box box-primary" id="data_tanah">
                                             <div class="box-header with-border">
                                                <h3 class="box-title">"Harta Tidak Bergerak berupa Tanah dan/atau bangunan"</h3>
                                            </div>
                                            <div class="box-body">
                                                 
                                            <span class="block-body">
                                                <a href="javscript:void(0)" id="" class="btn btn-info" data-remodal-target="FormHartaTanah">
                                                     <i class="fa fa-plus"></i> Tambah
                                                </a>
                                            </span>

                                            <span class="block-body">
                                                <table id="" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                                    <thead>
                                                        <tr>
                                                            <th>NO</th>
                                                            <th>STATUS </th>
                                                            <th>LOKASI/ALAMAT LENGKAP</th>
                                                            <th>LUAS TANAH/ BANGUNAN</th>
                                                            <th>KEPEMILIKAN</th>
                                                            <th>TAHUN PEROLEHAN</th>
                                                            <th>NILAI PEROLEHAN</th>
                                                            <th>NILAI PELAPORAN</th>
                                                            <th>AKSI</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                        </tr>
                                                        <tr>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                        </tr>
                                                        <tr>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </span>


                                            </div>
                                            <div class="box-footer">
                                                FOOTER
                                            </div>
                                        </div>
                                        <div class="tab-pane fade  in box box-primary" id="data_mesin">
                                             <div class="box-header with-border">
                                                <h3 class="box-title">Data Alat Transportasi / Mesin</h3>
                                            </div>
                                            <div class="box-body">
                                                 BODY
                                            </div>
                                            <div class="box-footer">
                                                FOOTER
                                            </div>
                                        </div>
                                        <div class="tab-pane fade  in box box-primary" id="data_hbergerak">
                                             <div class="box-header with-border">
                                                <h3 class="box-title">Data Harta Bergerak</h3>
                                            </div>
                                            <div class="box-body">
                                                 BODY
                                            </div>
                                            <div class="box-footer">
                                                FOOTER
                                            </div>
                                        </div>
                                        <div class="tab-pane fade  in box box-primary" id="data_sberharga">
                                             <div class="box-header with-border">
                                                <h3 class="box-title">Data Surat Berharga</h3>
                                            </div>
                                            <div class="box-body">
                                                 BODY
                                            </div>
                                            <div class="box-footer">
                                                FOOTER
                                            </div>
                                        </div>
                                         <div class="tab-pane fade  in box box-primary" id="data_kas">
                                             <div class="box-header with-border">
                                                <h3 class="box-title">Data Kas</h3>
                                            </div>
                                            <div class="box-body">
                                                 BODY
                                            </div>
                                            <div class="box-footer">
                                                FOOTER
                                            </div>
                                        </div>
                                        <div class="tab-pane fade  in box box-primary" id="data_hlainnya">
                                             <div class="box-header with-border">
                                                <h3 class="box-title">Data Harta Lainnya</h3>
                                            </div>
                                            <div class="box-body">
                                                 BODY
                                            </div>
                                            <div class="box-footer">
                                                FOOTER
                                            </div>
                                        </div>
                                        <div class="tab-pane fade  in box box-primary" id="data_hutang">
                                             <div class="box-header with-border">
                                                <h3 class="box-title">Data Hutang</h3>
                                            </div>
                                            <div class="box-body">
                                                 BODY
                                            </div>
                                            <div class="box-footer">
                                               <div>
                                                    <ul class="wizard">
                                                        <li class="previous">
                                                            <a href="#" class="btn btn-warning btn-prev-data-kontak">
                                                                <i class="fa fa-backward"></i>
                                                                Sebelumnya
                                                            </a>
                                                        </li>
                                                        <li class="next push-right">
                                                            <a href="#" class="btn btn-info">
                                                                Selanjutnya
                                                                <i class="fa fa-forward"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                            </div>
                        </div>
                        <div class="row tab-pane" id="tab5">
                            <div class="col-lg-10">
                                <!--Penerimaan-->
                                <h2>Form Penerimaan</h2>
                            </div>
                        </div>
                        <div class="row tab-pane" id="tab6">
                            <div class="col-lg-10">
                                <!--Pengeluaran-->
                                <h2>Form Pengeluaran</h2>
                            </div>
                        </div>
                        <div class="row tab-pane" id="tab7">
                            <div class="col-lg-10" style="width:100%">
                                <!--Fasilitas-->
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Data Fasilitas</h3>
                                    </div>
                                    <div class="box-body">

                                        <span class="block-body">
                                            <a href="javscript:void(0)" id="add-jabatan" class="btn btn-info" data-remodal-target="FormFasilitas">
                                                <i class="fa fa-plus"></i> Tambah
                                            </a>
                                        </span>

                                        <span class="block-body">
                                            <table id="TKeluarga" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                                <thead>
                                                    <tr>
                                                        <th>NO</th>
                                                        <th>STATUS  </th>
                                                        <th>KODE JENIS</th>
                                                        <th>NAMA FASILITAS</th>
                                                        <th>NAMA PIHAK PEMBERI FASILITAS</th>
                                                        <th>KETERANGAN</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </span>


                                    </div>
                                    <div class="box-footer">
                                        <div>
                                            <ul class="wizard">
                                                <li class="previous">
                                                    <a href="#" class="btn btn-warning btn-prev-data-kontak">
                                                        <i class="fa fa-backward"></i>
                                                        Sebelumnya
                                                    </a>
                                                </li>
                                                <li class="next push-right">
                                                    <a href="#" class="btn btn-info">
                                                        Selanjutnya
                                                        <i class="fa fa-forward"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row tab-pane" id="tab8">
                            <div class="col-lg-10">
                                <!--Review Lampiran-->
                                <h2>Review Lampiran</h2>
                            </div>
                        </div>
                        <div class="row tab-pane" id="tab9">
                            <div class="col-lg-10">
                                <!--Review Harta-->
                                <h2>Review Harta</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--wapper-main-->
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function () {

        $('#wrapper-main').bootstrapWizard({'tabClass': 'nav nav-pills', 'debug': false, onShow: function (tab, navigation, index) {
            }, onTabShow: function (tab, navigation, index) {
                var $total = navigation.find('li').length;
                var $current = index + 1;
                var $percent = ($current / $total) * 100;
                $('#wrapper-main .progress-bar').css({width: $percent + '%'});
                $('#wrapper-main .progress-bar').text(Math.ceil($percent) + ' %');
            }});
        $("ul.nav-tabs a").click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        $('#tgl_lahir,#tgl_tm,.dates').datetimepicker({
            format: "DD/MM/YYYY",
        });

        $('.thn_awal,.thn_akhir').datetimepicker({
            format: "YYYY",
        });

        $("input[type=file]").fileinput({
            showUpload: false,
        });

        $('#cb_agama').select2();
        //$("#main-menu").sticky({topSpacing:107});


        // Data Pribadi
        $('.btn-next-data-pribadi').click(function () {
            $('#tab-data-pribadi .nav-tabs > .active').next('li').find('a').trigger('click');
        });
        $('.btn-prev-data-kontak').click(function () {
            $('#tab-data-pribadi .nav-tabs > .active').prev('li').find('a').trigger('click');
        });

        $('table').dataTable({
            "bPaginate": false,
            "bLengthChange": true,
            "bFilter": false,
            "bSort": true,
            "bInfo": false,
            "bAutoWidth": true,
        });


        $('#wizard-jabatan,#wizard-keluarga,#wizard-harta-tanah').bootstrapWizard();

    });


</script>


<div class="remodal" style="background:none" data-remodal-id="FormJabatan">
    <button data-remodal-action="close" class="remodal-close remodal-close-form"></button>
    <div class="container-fluid box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Tambah Jabatan</h3>
        </div>
        <div class="row">
            <form role="form">
                <div class="box-body" id="wizard-jabatan">
                    <div class="navbar">
                        <div class="navbar-inner">
                            <ul>
                                <li><a href="#jabatan-1" data-toggle="tab">1</a></li>
                                <li><a href="#jabatan-2" data-toggle="tab">2</a></li>
                                <li><a href="#jabatan-3" data-toggle="tab">3</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-12 tab-content">
                        <div class="tab-pane" id="jabatan-1">
                            <div class="form-group " style="text-align:left;">
                                <label for="exampleInputEmail1">Lembaga</label>
                                <select class="form-control" id="">
                                    <option></option>  
                                    <option></option>
                                    <option></option>
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Unit Kerja</label>
                                <select class="form-control" id="">
                                    <option></option>  
                                    <option></option>
                                    <option></option>
                                    <option></option>
                                </select> 
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Jabatan</label>
                                <select class="form-control" id="">
                                    <option></option>  
                                    <option></option>
                                    <option></option>
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Deskripsi Jabatan</label>
                                <textarea class="form-control" rows="4" placeholder="Enter ..."></textarea>
                            </div>
                        </div>
                        <div class="tab-pane" id="jabatan-2">
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">TMT</label>
                                <input type="text" id="tgl_tmt" class="form-control dates" > 
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Eselon</label>
                                <select class="form-control" id="">
                                    <option></option>  
                                    <option></option>
                                    <option></option>
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Alamat Kantor</label>
                                <textarea class="form-control" rows="4" placeholder="Enter ..."></textarea>
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Email Kantor</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="tab-pane" id="jabatan-3">
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">SK</label>
                                <input type="file" class="files"  id="" data-allowed-file-extensions='["jpg", "pdf"]'>
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <button  type="submit" class="btn btn-info">Update</button>
                                <button  style="margin-right:10px;" data-remodal-action="close" class="btn btn-warning">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    
                </div>
            </form>
        </div>
    </div>
</div>

<div class="remodal" style="background:none" data-remodal-id="FormKeluarga">
    <button data-remodal-action="close" class="remodal-close remodal-close-form"></button>
    <div class="container-fluid box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Tambah Keluarga</h3>
        </div>
        <div class="row">
            <form role="form">
                <div class="box-body" id="wizard-keluarga">
                    <div class="navbar">
                        <div class="navbar-inner">
                            <ul>
                                <li><a href="#keluarga-1" data-toggle="tab">1</a></li>
                                <li><a href="#keluarga-2" data-toggle="tab">2</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-12 tab-content">
                        <div class="tab-pane" id="keluarga-1">
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Nama</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" >
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Hubungan</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="hubungan" value="istri" checked="">
                                        Istri
                                    </label>
                                    <label>
                                        <input type="radio" name="hubungan" value="suami" checked="">
                                        Suami
                                    </label>
                                    <label>
                                        <input type="radio" name="hubungan"  value="anak_tanggungan" checked="">
                                        Anak Tanggungan
                                    </label>
                                    <label>
                                        <input type="radio" name="hubungan" value="anak_bukan_tanggungan" checked="">
                                        Anak Bukan Tanggungan
                                    </label>
                                </div>
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Tempat Lahir</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" >
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Tanggal Lahir</label>
                                <input type="text" class="form-control dates" id="exampleInputEmail1" >
                            </div>
                        </div>
                        <div class="tab-pane" id="keluarga-2">
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Jenis Kelamin</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="gender" id="gender" value="pria" checked="">
                                        Pria
                                    </label>
                                    <label>
                                        <input type="radio" name="gender" id="gender" value="wanita" checked="">
                                        Wanita
                                    </label>
                                </div>
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Pekerjaan</label>
                                <select class="form-control" id="">
                                    <option></option>  
                                    <option></option>
                                    <option></option>
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Alamat</label>
                                <textarea class="form-control" rows="4" placeholder="Enter ..."></textarea>
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Nomor Telepon</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" >
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <a href="javascript:void(0)" style="margin-right:5px;" class="btn btn-danger pull-left">Sama Dengan PN</a>
                                <button  type="submit" class="btn btn-info">Update</button>
                                <button  style="margin-right:5px;" data-remodal-action="close" class="btn btn-warning">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    
                </div>
            </form>
        </div>
    </div>
</div>


<div class="remodal" style="background:none" data-remodal-id="FormFasilitas">
    <button data-remodal-action="close" class="remodal-close remodal-close-form"></button>
    <div class="container-fluid box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Tambah Fasilitas</h3>
        </div>
        <div class="row">
            <form role="form">
                <div class="box-body">
                    <div class="col-lg-12">
                        <div class="form-group" style="text-align:left;">
                            <label for="exampleInputEmail1">Kode Jenis</label>
                            <input type="text" class="form-control" id="exampleInputEmail1" >
                        </div>
                        <div class="form-group" style="text-align:left;">
                            <label for="exampleInputEmail1">Nama Fasilitas</label>
                            <input type="text" class="form-control" id="exampleInputEmail1" >
                        </div>
                        <div class="form-group" style="text-align:left;">
                            <label for="exampleInputEmail1">Nama Pemberi Fasilitas</label>
                            <input type="text" class="form-control" id="exampleInputEmail1" >
                        </div>
                        <div class="form-group" style="text-align:left;">
                            <label for="exampleInputEmail1">Keterangan</label>
                            <textarea class="form-control" rows="4" placeholder="Enter ..."></textarea>
                        </div>
                    </div>  
                </div>
                <div class="box-footer">
                    <button  type="submit" class="btn btn-info">Update</button>
                    <button  style="margin-right:10px;" data-remodal-action="close" class="btn btn-warning">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="remodal" style="background:none" data-remodal-id="FormHartaTanah">
    <button data-remodal-action="close" class="remodal-close remodal-close-form"></button>
    <div class="container-fluid box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Tambah Harta Tidak Bergerak</h3>
        </div>
        <div class="row">
            <form role="form">
                <div class="box-body" id="wizard-harta-tanah">
                    <div class="navbar">
                        <div class="navbar-inner">
                            <ul>
                                <li><a href="#harta-tanah-1" data-toggle="tab">1</a></li>
                                <li><a href="#harta-tanah-2" data-toggle="tab">2</a></li>
                                <li><a href="#harta-tanah-3" data-toggle="tab">3</a></li>
                                <li><a href="#harta-tanah-4" data-toggle="tab">4</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-12 tab-content">
                        <div class="tab-pane" id="harta-tanah-1">
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Asal Negara</label>
                                 <div class="radio">
                                    <label>
                                        <input type="radio" name="negara" id="" value="Luar Negeri" checked="">
                                       Luar Negeri
                                    </label>
                                    <label>
                                        <input type="radio" name="negara" id="" value="Indonesia" checked="">
                                        Indonesia
                                    </label>
                                </div>
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Nama Negara</label>
                                 <select class="form-control" id="">
                                    <option></option>  
                                    <option></option>
                                    <option></option>
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Provinsi</label>
                                 <select class="form-control" id="">
                                    <option></option>  
                                    <option></option>
                                    <option></option>
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Kabupaten / Kota</label>
                                 <select class="form-control" id="">
                                    <option></option>  
                                    <option></option>
                                    <option></option>
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Kecamatan</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" >
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Kelurahan</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" >
                            </div>
                        </div>
                        <div class="tab-pane" id="harta-tanah-2">
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Jalan</label>
                                <textarea class="form-control" rows="4" placeholder="Enter ..."></textarea>
                            </div>
                             <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Luas Tanah / Bangunan</label>
                                <div class="input-wrapper">
                                    <input placeholder="Tanah" type="text" class="form-control text-mini" id="exampleInputEmail1" >
                                    <label>M<sup>2</sup></label>
                                    <input placeholder="Bangunan" type="text" class="form-control text-mini" id="exampleInputEmail1" >
                                    <label>M<sup>2</sup></label>
                                </div>
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Keterangan</label>
                                <textarea class="form-control" rows="4" placeholder="Enter ..."></textarea>
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Jenis Bukti</label>
                                 <select class="form-control" id="">
                                    <option></option>  
                                    <option></option>
                                    <option></option>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="tab-pane" id="harta-tanah-3">
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Nomor Bukti</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" >
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Atas Nama</label>
                                 <input type="text" class="form-control" id="exampleInputEmail1" >
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Tahun Perolehan</label>
                                 <div class="input-wrapper">
                                    <input placeholder="Tahun Awal" type="text" class="form-control text-mini thn_awal" id="exampleInputEmail1" >
                                    <label>s/d</label>
                                    <input placeholder="Tahun Akhir" type="text" class="form-control text-mini thn_akhir" id="exampleInputEmail1" >
                                </div>
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Asal Usul</label>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="" id="" value="" >
                                       Hasil Sendiri
                                    </label>
                                     <label>
                                        <input type="checkbox" name="" id="" value="" >
                                       Warisan
                                    </label>
                                    <label>
                                        <input type="checkbox" name="" id="" value="" >
                                       Hibah
                                    </label>
                                    <label>
                                        <input type="checkbox" name="" id="" value="" >
                                       Hadiah
                                    </label>
                                </div>
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Pemanfaatan</label>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="" id="" value="" >
                                        Tempat Tinggal
                                    </label>
                                     <label>
                                        <input type="checkbox" name="" id="" value="" >
                                       Disewakan
                                    </label>
                                    <label>
                                        <input type="checkbox" name="" id="" value="" >
                                       Pertanian
                                    </label>
                                    <label>
                                        <input type="checkbox" name="" id="" value="" >
                                       Perkebunan
                                    </label>
                                     <label>
                                        <input type="checkbox" name="" id="" value="" >
                                       Perikanan
                                    </label>
                                     <label>
                                        <input type="checkbox" name="" id="" value="" >
                                        Pertambangan
                                    </label>
                                     <label>
                                        <input type="checkbox" name="" id="" value="" >
                                       Lainnya
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="harta-tanah-4">
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Keterangan Lainnya</label>
                                <textarea class="form-control" rows="4" placeholder="Enter ..."></textarea>
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Nilai Perolehan</label>
                                <div class="input-wrapper">
                                    <select class="form-control text-mini" style="width:20%;">
                                        <option></option>
                                        <option></option>
                                    </select>
                                    <input style="width:70%;" placeholder="Nilai Perolehan" type="text" class="form-control " id="exampleInputEmail1" >
                                </div>
                            </div>
                            <div class="form-group" style="text-align:left;">
                                <label for="exampleInputEmail1">Nilai Pelaporan</label>
                                 <div class="radio">
                                    <label>
                                        <input type="radio" name="negara" id="" value="Luar Negeri" checked="">
                                       Appraisal
                                    </label>
                                    <label>
                                        <input type="radio" name="negara" id="" value="Indonesia" checked="">
                                       Perkiraan Pasar
                                    </label>
                                </div>
                                <div>
                                    <label for="exampleInputEmail1" style="display:inline;">Rp</label>
                                    <input style="display:inline; width:80%;" placeholder="Nilai Pelaporan" type="text" class="form-control" id="exampleInputEmail1" >
                                </div>
                            </div>
                             <div class="form-group" style="text-align:left;">
                                <button  type="submit" class="btn btn-info">Update</button>
                                <button  style="margin-right:10px;" data-remodal-action="close" class="btn btn-warning">Close</button>
                            </div>
                        </div>
                        
                        
                        
                    </div>  
                </div>
            </form>
        </div>
    </div>
</div>