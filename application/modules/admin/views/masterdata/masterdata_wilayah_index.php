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
 * @package Views/masterdata/wilayah
*/
?>
<div class="box box-primary">
<div class="box-header with-border">
    
        <button type="button" id="btnAdd" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah Data</button>
<!--        <button type="button" id="btnPrintPDF" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i></button>
        <button type="button" id="btnPrintEXCEL" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i></button>
        <button type="button" id="btnPrintWORD" class="btn btn-sm btn-default"><i class="fa fa-file-word-o"></i></button>    -->
   
    <div class="row">

    <!-- <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8"> -->
    <div class="col-md-12">
        <form method="post" class="form-horizontal" id="ajaxFormCari" action="<?php echo $thisPageUrl;?>">
            <div class="box-body">
                <div class="row">
                
                <div class="col-md-6">
                <div class="form-group">
                        <label class="col-sm-4 control-label">Provinsi :</label>
                        <div class="col-sm-5">
                            <input type='text' class="input-sm select2 form-control" name='CARI[PROV]' style="border:none;padding:6px 0px;" id='CARI_PROV' value='<?php echo @$CARI['PROV'];?>' placeholder="-- Pilih Provinsi --">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Kota / Kabupaten :</label>
                        <div class="col-sm-5">
                            <input type='text' class="input-sm select2 form-control" name='CARI[KOTA]' style="border:none;padding:6px 0px;" id='CARI_KOTA' value='<?php echo @$CARI['KOTA'];?>' placeholder="-- Pilih Kota --" disabled>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Kecamatan :</label>
                        <div class="col-sm-5">
                            <input type='text' class="input-sm select2 form-control" name='CARI[KEC]' style="border:none;padding:6px 0px;" id='CARI_KEC' value='<?php echo @$CARI['KEC'];?>' placeholder="-- Pilih Kecamatan --" disabled>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                <div class="form-group">
                        <label class="col-sm-4 control-label">Nama Wilayah :</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control input-sm" placeholder="Search Nama Wilayah" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT'];?>" id="CARI_TEXT"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Status :</label>
                        <div class="col-sm-5">
                            <select name="CARI[IS_ACTIVE]" id="IS_ACTIVE" class="select2 form-control">
                                <option>Status</option>
                                <option <?= (@$this->CARI['IS_ACTIVE'] == '1' ? 'selected' : ''); ?> value="1">Active</option>
                                <option <?= (@$this->CARI['IS_ACTIVE'] == '0' ? 'selected' : ''); ?> value="0">Non Active</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-3 col-sm-offset-4">
                            <button type="submit" id="btnCari" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                            <button type="button" id="btnClear" class="btn btn-sm btn-default" onclick="$('#CARI_PROV').select2('val', '');$('#CARI_KOTA').select2('val', ''); $('#CARI_KEC').select2('val', ''); $('#CARI_TEXT').val(''); $('#IS_ACTIVE').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
                        </div>
                    </div>
                </div>    

                    

                    
                </div>
            </div>
        </form>
    </div>

    </div>

</div><!-- /.box-header -->
<div class="box-body">
<?php
    if($total_rows){
?>
<!-- <table class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->
<table class="table table-striped">
    <thead>
        <tr>
            <th width="30">No.</th>
            <th>IDPROV</th>
            <th>IDKOT</th>
            <th>IDKEC</th>
            <th>IDKEL</th>
            <th>NAME</th>
            <th>LEVEL</th>
            <th>IS Active</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $i = 0 + $offset;
            $start = $i + 1;
            foreach ($items as $item) {
        ?>
        <tr>
            <td><?php echo ++$i; ?>.</td>
            <td><?php echo $item->IDPROV; ?></td>
            <td><?php echo $item->IDKOT; ?></td>
            <td><?php echo $item->IDKEC; ?></td>
            <td><?php echo $item->IDKEL; ?></td>
            <td><?php echo str_ireplace(@$CARI['TEXT'], '<span style="border-bottom: 1px dashed #999;text-decoration: none;">'.@$CARI['TEXT'].'</span>', $item->NAME);?></td>
            <td><?php echo $item->LEVEL; ?></td>
            <td><?= ($item->IS_ACTIVE == '1' ? 'Active' : 'Nonactive'); ?></td>
            <td width="120" nowrap="">
                <input type="hidden" class="key" value="<?php echo $item->$pk;?>">
                <button type="button" class="btn btn-sm btn-primary btnDetail" title="Preview"><i class="fa fa-search-plus"></i></button>
                <button type="button" class="btn btn-sm btn-default btnDelete" title="Delete"><i class="fa fa-trash" style="color:red;"></i></button>
                <button type="button" class="btn btn-sm btn-primary btnEdit" title="Edit"><i class="fa fa-pencil"></i></button>
            </td>
        </tr>
        <?php
                $end = $i;
            }
        ?>
    </tbody>
</table>
<?php
    }else{
        echo 'Data Not Found...';
    }
?>

<script language="javascript">
    $(document).ready(function () {

        if($('#CARI_KOTA').val() != ''){
            $('#CARI_KOTA').removeAttr("disabled");
        }

        if($('#CARI_KEC').val() != ''){
            $('#CARI_KEC').removeAttr("disabled");
        }
            
        $('input[name="CARI[PROV]"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/share/reff/getProvinsi')?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) {
                    return { results: data.item };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?=base_url('index.php/share/reff/getProvinsi')?>/"+id, {
                        dataType: "json"
                    }).done(function(data) { callback(data[0]); });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection:  function (state) {
                return state.name;
            }
        }).on("change", function(e) {
            $('#CARI_KOTA').removeAttr("disabled");
            idprov = e.added.id;

            $('input[name="CARI[KOTA]"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?=base_url('index.php/share/reff/getKota')?>/"+idprov,
                    dataType: 'json',
                    quietMillis: 250,
                    data: function (term, page) {
                        return {
                            q: term
                        };
                    },
                    results: function (data, page) {
                        return { results: data.item };
                    },
                    cache: true
                },
                initSelection: function(element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                        $.ajax("<?=base_url('index.php/share/reff/getKota')?>/"+idprov+"/"+id, {
                            dataType: "json"
                        }).done(function(data) { callback(data[0]); });
                    }
                },
                formatResult: function (state) {
                    return state.name;
                },
                formatSelection:  function (state) {
                    return state.name;
                }
            }).on("change", function(e) {
                $('#CARI_KEC').removeAttr("disabled");
                idkabkot = e.added.id;

                $('input[name="CARI[KEC]"]').select2({
                    minimumInputLength: 0,
                    ajax: {
                        url: "<?=base_url('index.php/share/reff/getKec')?>/"+idprov+"/"+idkabkot,
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (term, page) {
                            return {
                                q: term
                            };
                        },
                        results: function (data, page) {
                            return { results: data.item };
                        },
                        cache: true
                    },
                    initSelection: function(element, callback) {
                        var id = $(element).val();
                        if (id !== "") {
                            $.ajax("<?=base_url('index.php/share/reff/getKec')?>/"+idprov+"/"+idkabkot+"/"+id, {
                                dataType: "json"
                            }).done(function(data) { callback(data[0]); });
                        }
                    },
                    formatResult: function (state) {
                        return state.name;
                    },
                    formatSelection:  function (state) {
                        return state.name;
                    }
                });
            });
        });
    
        idprov = $('#CARI_PROV').val();

        $('input[name="CARI[KOTA]"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/share/reff/getKota')?>/"+idprov,
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) {
                    return { results: data.item };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?=base_url('index.php/share/reff/getKota')?>/"+idprov+"/"+id, {
                        dataType: "json"
                    }).done(function(data) { callback(data[0]); });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection:  function (state) {
                return state.name;
            }
        });

        idkabkot = $('#CARI_KOTA').val();

        $('input[name="CARI[KEC]"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/share/reff/getKec')?>/"+idprov+"/"+idkabkot,
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) {
                    return { results: data.item };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?=base_url('index.php/share/reff/getKec')?>/"+idprov+"/"+idkabkot+"/"+id, {
                        dataType: "json"
                    }).done(function(data) { callback(data[0]); });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection:  function (state) {
                return state.name;
            }
        });
    });
</script>