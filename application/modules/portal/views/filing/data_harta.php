
<div id="form-content"></div>
<div class="box-body">
    <span class="block-body action-panel">
        <a href="javascript:void(0)" id="load-all-data-sebelumnya" class="btn btn-warning btn-sm update"  style="display:none;">
            <i class="fa fa-history" aria-hidden="true"></i>
            Load Semua Data Sebelumnya
        </a>
        <input type="hidden" class="inp-load-all-data-1 inp-load-all-data" value="0">
        <input type="hidden" class="inp-load-all-data-2 inp-load-all-data" value="0">
        <input type="hidden" class="inp-load-all-data-3 inp-load-all-data" value="0">
        <input type="hidden" class="inp-load-all-data-4 inp-load-all-data" value="0">
        <input type="hidden" class="inp-load-all-data-5 inp-load-all-data" value="0">
        <input type="hidden" class="inp-load-all-data-6 inp-load-all-data" value="0">
        <input type="hidden" class="inp-load-all-data-7 inp-load-all-data" value="0">
        <input type="hidden" id="ctr" value="0">
    </span>
</div>
<div class="box-body">
    <div class="tabbable block-body" id="tab-data-harta">
        <ul class="nav nav-tabs menu-filling">
            <li class="active"><a href="#data_tanah" id="harta_tidak_bergerak">Tanah / Bangunan</a></li>
            <li><a href="#data_mesin" id="harta_bergerak">Alat Transportasi / Mesin</a></li>
            <li><a href="#data_hbergerak" id="harta_lain">Harta Bergerak Lainnya</a></li>
            <li><a href="#data_sberharga" id="surat_berharga">Surat Berharga</a></li>
            <li><a href="#data_kas" id="kas">KAS / Setara KAS</a></li>
            <li><a href="#data_hlainnya" id="lain">Harta Lainnya</a></li>
            <li><a href="#data_hutang" id="hutang">Hutang</a></li>
        </ul>
    </div>
    <div class="tab-content block-body">
        <div class="tab-pane fade active in" id="data_tanah">
            <div class="box box-success">
                <?= FormHelpAccordionEfiling('harta_tidak_bergerak', 1); ?>
                <div class="box-body">
                    <span class="block-body action-panel">
                        <a href="javascript:void(0)" onclick="CallForm('harta_tidak_bergerak')" id="add-1" class="btn btn-info add-new" style="display:none;">
                            <i class="fa fa-plus"></i> Tambah
                        </a>
                        <a href="javascript:void(0)" onclick="UpdateData('1', 'harta_tidak_bergerak', '#TableTanah')" id="load-1" class="btn btn-warning btn-sm update"  style="display:none;">
                            <i class="fa fa-history" aria-hidden="true"></i>
                            Load Data Sebelumnya
                        </a>
                    </span>
                    <span class="block-body grid_harta_tidak_bergerak grid_wrapper">
                        </span>
                </div>
            </div>
        </div>
        <div class="tab-pane fade in" id="data_mesin">
            <div class="box box-success">
                <?= FormHelpAccordionEfiling('harta_bergerak', 2); ?>
                <div class="box-body">
                    <span class="block-body">
                        <a href="javascript:void(0)" onclick="CallForm('harta_mesin')" id="add-2" class="btn btn-info add-new" style="display:none;">
                            <i class="fa fa-plus"></i> Tambah
                        </a>
                        <a href="javascript:void(0)" onclick="UpdateData('2', 'harta_mesin', '#TableMesin')" id="load-2" class="btn btn-warning btn-sm update" style="display:none;">
                            <i class="fa fa-history" aria-hidden="true"></i>
                            Load Data Sebelumnya
                        </a>
                    </span>
                    <span class="block-body grid_harta_bergerak grid_wrapper">
                    </span>
                </div>
            </div>
        </div>
        <div class="tab-pane fade in" id="data_hbergerak">
            <div class="box box-success">
                <?= FormHelpAccordionEfiling('harta_bergerak_lainnya', 3); ?>
                <div class="box-body">
                    <span class="block-body">
                        <a href="javascript:void(0)" onclick="CallForm('harta_bergerak')" id="add-3" class="btn btn-info add-new" style="display:none;">
                            <i class="fa fa-plus"></i> Tambah
                        </a>
                        <a href="javascript:void(0)" onclick="UpdateData('3', 'harta_bergerak', '#TableHbergerak')" id="load-3"  class="btn btn-warning btn-sm update" style="display:none;">
                            <i class="fa fa-history" aria-hidden="true"></i>
                            Load Data Sebelumnya
                        </a>
                    </span>
                    <span class="block-body grid_harta_lain grid_wrapper">
                    </span>
                </div>
            </div>
        </div>
        <div class="tab-pane fade in" id="data_sberharga">
            <div class="box box-success">
                <?= FormHelpAccordionEfiling('surat_berharga', 4); ?>
                <div class="box-body">
                    <span class="block-body">
                        <a href="javascript:void(0)" onclick="CallForm('harta_surat_berharga')" id="add-4" class="btn btn-info add-new" style="display:none;">
                            <i class="fa fa-plus"></i> Tambah
                        </a>
                        <a href="javascript:void(0)" onclick="UpdateData('4', 'harta_surat_berharga', '#TableSurat')" id="load-4"  class="btn btn-warning btn-sm update" style="display:none;">
                            <i class="fa fa-history" aria-hidden="true"></i>
                            Load Data Sebelumnya
                        </a>
                    </span>
                    <span class="block-body grid_surat_berharga grid_wrapper">
                    </span>
                </div>
            </div>
        </div>
        <div class="tab-pane fade in" id="data_kas">
            <div class="box box-success">
                <?= FormHelpAccordionEfiling('kas', 5); ?>
                <div class="box-body">
                    <span class="block-body">
                        <a href="javascript:void(0)"  onclick="CallForm('harta_kas')" id="add-5" class="btn btn-info add-new" style="display:none;">
                            <i class="fa fa-plus"></i> Tambah
                        </a>
                        <a href="javascript:void(0)"  onclick="UpdateData('5', 'harta_kas', '#TableKas')" id="load-5" class="btn btn-warning btn-sm update" style="display:none;">
                            <i class="fa fa-history" aria-hidden="true"></i>
                            Load Data Sebelumnya
                        </a>
                    </span>
                    <span class="block-body grid_kas grid_wrapper">
                    </span>
                </div>
            </div>
        </div>
        <div class="tab-pane fade  in" id="data_hlainnya">
            <div class="box box-success">
                <?= FormHelpAccordionEfiling('harta_lainnya', 6); ?>
                <div class="box-body">
                    <span class="block-body">
                        <a href="javascript:void(0)" onclick="CallForm('harta_lain')" id="add-6" class="btn btn-info add-new" style="display:none;">
                            <i class="fa fa-plus"></i> Tambah
                        </a>
                        <a href="javascript:void(0)" onclick="UpdateData('6', 'harta_lain', '#TableLain')" id="load-6" class="btn btn-warning btn-sm update" style="display:none;">
                            <i class="fa fa-history" aria-hidden="true"></i>
                            Load Data Sebelumnya
                        </a>
                    </span>
                    <span class="block-body grid_lain grid_wrapper">
                    </span>
                </div>
            </div>
        </div>
        <div class="tab-pane fade in" id="data_hutang">
            <div class="box box-success">
                <?= FormHelpAccordionEfiling('hutang', 7); ?>
                <div class="box-body">
                    <span class="block-body">
                        <a href="javascript:void(0)" onclick="CallForm('hutang')" id="add-7" class="btn btn-info add-new" style="display:none;">
                            <i class="fa fa-plus"></i> Tambah
                        </a>
                        <a href="javascript:void(0)" onclick="UpdateData('7', 'hutang', '#TableHutang')" id="load-7"  class="btn btn-warning btn-sm update">
                            <i class="fa fa-history" aria-hidden="true"></i>
                            Load Data Sebelumnya
                        </a>
                    </span>
                    <span class="block-body grid_hutang grid_wrapper">
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <div class="box-footer"></div> -->

<script type="text/javascript">
    var dtTanah = null, dtMesin = null, dtHartaBergerakLainnya = null, dtSuratBerharga = null, dtKasSetaraKas = null, dtHartaLainnya = null, dtHutang = null;
    var hideLoadAllDataSebelumnya = function () {

        var currentCondition = true;
        $(".inp-load-all-data").each(function () {
            if ($(this).val() == '0') {
                currentCondition = false;
            }
        });

        if (currentCondition) {
            $("#load-all-data-sebelumnya").hide();
        }
    };
    

    var loadAllDataSebelumnya = function () {
        $("#load-all-data-sebelumnya").on("click", function () {
//            Grid('harta_tidak_bergerak');
            var JUMLAH_DATA = $('#JUMLAH_DATA').val();
            $("#load-all-data-sebelumnya").hide();
//            if (JUMLAH_DATA != '0') {
                UpdateData('1', 'harta_tidak_bergerak', '#TableTanah', $('#TableTanah'), function (self) {
                    if (dtTanah != null) {
                        dtTanah.fnDraw();
                    }
                });
//            }
            hideLoadAllDataSebelumnya();
        });
        $("#load-all-data-sebelumnya").on("click", function () {
//            Grid('harta_mesin');
            var JUMLAH_DATA = $('#JUMLAH_DATA').val();
            $("#load-all-data-sebelumnya").hide();
//            if (JUMLAH_DATA != '0') {
                UpdateData('2', 'harta_mesin', '#TableMesin', $('#TableMesin'), function (self) {
                    if (dtMesin != null) {
                        dtMesin.fnDraw();
                    }
                });
//            }
            hideLoadAllDataSebelumnya();
        });
        $("#load-all-data-sebelumnya").on("click", function () {
//            Grid('harta_lain');
            var JUMLAH_DATA = $('#JUMLAH_DATA').val();
            $("#load-all-data-sebelumnya").hide();
//            if (JUMLAH_DATA != '0') {
                UpdateData('3', 'harta_bergerak', '#TableHbergerak', $('#TableHbergerak'), function (self) {
                    if (dtHartaBergerakLainnya != null) {
                        dtHartaBergerakLainnya.fnDraw();
                    }
                });
//            }
            hideLoadAllDataSebelumnya();
        });
        $("#load-all-data-sebelumnya").on("click", function () {
//            Grid('surat_berharga');
            var JUMLAH_DATA = $('#JUMLAH_DATA').val();
            $("#load-all-data-sebelumnya").hide();
//            if (JUMLAH_DATA != '0') {
                UpdateData('4', 'harta_surat_berharga', '#TableSurat', $('#TableSurat'), function (self) {
                    if (dtSuratBerharga != null) {
                        dtSuratBerharga.fnDraw();
                    }
                });
//            }
            hideLoadAllDataSebelumnya();
        });
        $("#load-all-data-sebelumnya").on("click", function () {
//            Grid('kas');
            var JUMLAH_DATA = $('#JUMLAH_DATA').val();
            $("#load-all-data-sebelumnya").hide();
//            if (JUMLAH_DATA != '0') {
                UpdateData('5', 'harta_kas', '#TableKas', $('#TableKas'), function (self) {
                    if (dtKasSetaraKas != null) {
                        dtKasSetaraKas.fnDraw();
                    }
                });
//            }
            hideLoadAllDataSebelumnya();
        });
        $("#load-all-data-sebelumnya").on("click", function () {
//            Grid('lain');
            var JUMLAH_DATA = $('#JUMLAH_DATA').val();
            $("#load-all-data-sebelumnya").hide();
//            if (JUMLAH_DATA != '0') {
                UpdateData('6', 'harta_lain', '#TableLain', $('#TableLain'), function (self) {
                    if (dtHartaLainnya != null) {
                        dtHartaLainnya.fnDraw();
                    }
                });
//            }
            hideLoadAllDataSebelumnya();
        });
        $("#load-all-data-sebelumnya").on("click", function () {
//            Grid('hutang');
            var JUMLAH_DATA = $('#JUMLAH_DATA').val();
            $("#load-all-data-sebelumnya").hide();
//            if (JUMLAH_DATA != '0') {
                UpdateData('7', 'hutang', '#TableHutang', $('#TableHutang'), function (self) {
                    if (dtHutang != null) {
                        dtHutang.fnDraw();
                    }
                });
//            }
            hideLoadAllDataSebelumnya();
        });
    };

    $(document).ready(function () {

        $('html, body').animate({
            scrollTop: 0
        }, 2000);


        $('.date').datetimepicker({
            format: "DD/MM/YYYY",
        });
        $('.nav-tabs a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
        $('select').select2();
        $('.date').datetimepicker({
            format: "DD/MM/YYYY",
        });
        $('#tab-data-harta ul li a').click(function () {
            var href = $(this).attr('href');
            var name = $(this).attr('id');
            createCookie('current-lhkpn-harta', href, 7);
            Grid(name);
//            setSettingHarta();
        });
        $('[data-toggle="popover"]').popover({
            placement: 'top',
        });
        $('a.over').css('cursor', 'pointer');
        $('a.over').on('click', function (e) {
            $('a.over').not(this).popover('hide');
        });
        $('[data-toggle="popover"]').popover({
            placement: 'right',
        });
        $("#tab-data-harta").sticky({topSpacing: 120});
        $("#tab-data-harta").css({
            'position': 'absolute',
            'z-index': '99',
            'background-color': '#fff',
            'border-bottom': '1px solid #ddd',
            'width': '98%'
        });
        $('.btn-info').addClass('btn-sm');

        if (STATUS == '1' || STATUS == '3' || STATUS == '4' || STATUS == '5' || STATUS == '6' || VIA_VIA == '1') {
            $('a.add-new').remove();
        }

        loadAllDataSebelumnya();


    });
</script>
