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
 * @package Views/pejabat
 */
?>
<style type="text/css">
    .form-select2 {
        padding: 6px 0px !important;
        margin: 0px !important;
    }
    .addon-custom {padding: 0px;}
    .addon-custom input {
        border: medium none;
        height: 32px;
        width: 60px;
        padding: 6px 12px;
    }
    div {
        padding: 4px 4px 4px 4px;
    }
</style>
<?php
echo isset($html_form_view) ? $html_form_view : "";
if ($form == 'add') {
    echo $form_add;
}
?>
<script type="text/javascript">
    function luar() {
        // alert('luar');
        $('.dalamnegeri').attr('required', false);
        $('.luarnegeri').attr("required", true);
        $(".luarlokasi").show();
        $(".lokasi").hide();
    }

    function dalam() {
        // alert('dalam');
        $('.dalamnegeri').attr("required", true);
        $('.luarnegeri').attr('required', false);
        $(".luarlokasi").hide();
        $(".lokasi").show();
    }
</script>
<?php
if (!isset($html_form_view)) {
    if ($form == 'edit') {
        echo $form_edit;
    }
    if ($form == 'delete') {
        echo $form_delete;
    }
    if ($form == 'update') {
        echo $form_update;
    }
    if ($form == 'delete_wl') {
        echo $form_delete_wl;
    }
    if ($form == 'delete_wlnon') {
        echo $form_delete_wlnon;
    }
    if ($form == 'detail') {
        echo $form_detail;
    }
    if ($form == 'detailpenambahan') {
        echo $form_detail_penambahan;
    }
    if ($form == 'detailperubahan') {
        echo $form_detailperubahan;
    }
    ?>
    <!-- Detail WL-->
    <?php
    if ($form == 'detailwl') {
        echo $form_detailwl;
    }
    ?>

    <!-- Detail Non WL -->

    <?php
    if ($form == 'detailnonwl') {
        echo $form_detailnonwl;
    }
    ?>

    <!-- Verifikasi Excel -->

    <?php
    if ($form == 'detail_vertambah') {
        echo $form_detail_vertambah;
    }
    if ($form == 'detail_verrubah') {
        echo $form_detail_verrubah;
    }
    if ($form == 'detail_vernonaktif') {
        echo $form_detail_vernonaktif;
    }
    if ($form == 'reset_password') {
        echo $form_reset_password;
    }
    if ($form == 'kirim_aktivasi') {
        echo $form_kirim_aktivasi;
    }
    if ($form == 'mutasi') {
        // display($jabatan);
        echo $form_mutasi;
    }
    if ($form == 'mutasi_calon') {
        echo $form_mutasi_calon;
    }
    if ($form == 'delete_calon_pn') {
        echo $form_delete_calon_pn;
    }
    if ($form == 'delete_verifikasi_data_individu') {
        echo $form_delete_verifikasi_data_individu;
    }
    if ($form == 'delete_daftar_pn_individual') {
        echo $form_delete_daftar_pn_individual;
    }
    if ($form == 'nonaktifkan') {
        echo $form_nonaktifkan;
    }
    if ($form == 'terpilih') {
        echo $form_terpilih;
    }
    if ($form == 'actMeninggal') {
        echo $form_actMeninggal;
    }
}
?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        $('.date-picker').datepicker({
            format: 'dd/mm/yyyy'
        });
    });

    function validate(evt) {
        var theEvent = evt || window.event;
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode(key);
        var regex = /[0-9\b]|\./;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault)
                theEvent.preventDefault();
        }
    }
</script>