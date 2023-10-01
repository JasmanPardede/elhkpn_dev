<div id="FillingEditLaporan" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" id="myform" method="POST" action="<?php echo base_url(); ?>portal/filing/add">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <!--<h4 class="modal-title">BUAT LHKPN BARU</h4>-->
                    <h4 class="modal-title"><label id="title-label" style="font-size: 20px;"></label></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="is_update" id="is_update" class="form-control"/>
                        <input type="hidden" name="id_lhkpn" id="id_lhkpn" class="form-control"/>
                        <label>Jenis Laporan <span class="red-label">*</span> </label> <?= FormHelpPopOver('jenis_laporan_create'); ?>
                        <select name="jenis_laporan" id="jenis_laporan" class="form-control" required>
                            <option></option>
                            <?php if($count_elhkpn==1){  ?>
                                <option value="2">Khusus</option>
                            <?php }else if ($cek_wl_now != date('Y')-1){ ?>
                                <option value="2">Khusus</option>
                            <?php }else{ ?>
                                <?php if ($HISTORY) { ?>
                                <option value="4">Periodik</option>
                                <?php } ?>
                                <option value="2">Khusus</option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group group-0">
                        <label>Tahun Pelaporan <span class="red-label">*</span> </label> <?= FormHelpPopOver('tahun_laporan_create'); ?>
                        <input type="text" name="tahun_pelaporan" id="tahun_pelaporan" class="form-control" disabled=""/>
                    </div>
                    <div class="form-group group-1">
                        <label>Status <span class="red-label">*</span> </label> <?= FormHelpPopOver('status_laporan_create'); ?>
                        <select name="status" id="status" class="form-control" required>
                            <option></option>
                            <option value="1">Calon Penyelenggara Negara</option>
                            <option value="2">Awal Menjabat</option>
                            <option value="3">Akhir Menjabat</option>
                        </select>
                    </div>
                    <div class="form-group group-1">
                        <label>Tanggal Pelaporan <span class="red-label">*</span> </label> <?= FormHelpPopOver('tanggal_laporan_create'); ?>
                        <input type="text" name="tgl_pelaporan" id="tgl_pelaporan" class="form-control date" autocomplete="off" onkeydown="return false" required/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm" >
                        <i class="fa fa-share"></i> Lanjut
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                        <i class="fa fa-remove"></i> Batal
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>