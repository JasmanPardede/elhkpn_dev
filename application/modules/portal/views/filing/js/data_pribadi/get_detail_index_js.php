<script type="text/javascript">

    get_detail = function () {
        $.ajax({
            url: base_url + 'portal/data_pribadi/data/' + ID_LHKPN,
            dataType: 'json',
//            async: false,
            success: function (data) {
                if (!isEmpty(data)) {
                    GetKota(data.ID_PROV);
                }
                if (data) {
                    var rs = eval(data);
                    $('#ID').val(rs.ID);
                    $('#NIK').val(rs.NIK);
                    $('#NIP').val(rs.NIP);
                    $('#KK').val(rs.KK);
                    $('#NAMA_LENGKAP').val(rs.NAMA_LENGKAP);
                    $('#GELAR_DEPAN').val(rs.GELAR_DEPAN);
                    $('#GELAR_BELAKANG').val(rs.GELAR_BELAKANG);
                    $('#NPWP').val(rs.NPWP);
                    $('#KK').val(rs.NO_KK);
                    $('#JENIS_KELAMIN').select2('val', rs.JENIS_KELAMIN);
                    $('#TEMPAT_LAHIR').val(rs.TEMPAT_LAHIR);
                    $('#TANGGAL_LAHIR').val(dateConvert(rs.TANGGAL_LAHIR));
                    $('#STATUS_PERKAWINAN').select2('val', rs.STATUS_PERKAWINAN);
                    $('#AGAMA').select2('val', rs.AGAMA.toUpperCase());
                    $('#HP').val(rs.HP);
                    $('#EMAIL_PRIBADI').val(rs.EMAIL_PRIBADI);
                    $('#NEGARA').select2('val', rs.NEGARA);

                    if (rs.NEGARA == '1') {
                        $('#ID_PROPINSI').select2("data", {id: rs.ID_PROV, text: rs.NAME});
                        $('#ID_KOTA').select2("data", {id: rs.ID_KAB, text: rs.NAME_KAB});
                        $('#ALAMAT_RUMAH').val(rs.ALAMAT_RUMAH);
                        $('#KECAMATAN').val(rs.KECAMATAN);
                        $('#KELURAHAN').val(rs.KELURAHAN);
                        $('#ID_NEGARA').select2('data', null);
                        $('#ALAMAT_NEGARA').val('');

                    } else {
                        var nama_negara = rs.NAMA_NEGARA == null ? "" : rs.NAMA_NEGARA;
                        $('#ID_NEGARA').select2("data", {id: rs.KODE_ISO3, text: nama_negara});
                        $('#ALAMAT_NEGARA').val(rs.ALAMAT_NEGARA);
                        $('#ID_PROPINSI').select2('data', null);
                        $('#ID_KOTA').select2('data', null);
                        $('#ALAMAT_RUMAH').val('');
                        $('#KECAMATAN').val('');
                        $('#KELURAHAN').val('');
                    }
                }

            }
        });
        
        __reloadProfilPicture();
    }
//    $(document).ready(function() {
//        
//    });
</script>