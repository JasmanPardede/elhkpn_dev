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
 * @author Gunaones - SKELETON-2015 - PT.Mitreka Solusi Indonesia 
 * @package Views/template
*/

    $urlAdd = $urlAdd ?? null;
    $urlEdit= $urlEdit ?? null;
    $urlDetail = $urlDetail ?? null;
    $urlDelete = $urlDelete ?? null;
    $linkCetak = $linkCetak ?? null;

?>
<script language="javascript">
    function sumbitAjaxFormCari(target){
        url = location.href.split('#')[1];
        ng.LoadAjaxContentPost(url, $('#ajaxFormCari'), target);
    }
    $(document).ready(function () {
        urlPaging = '';
        $(".pagination").find("a").click(function () {
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#ajaxFormCari'));
            return false;
        }); 

		$(".breadcrumb").find("a").click(function () {
			var url = $(this).attr('href');
			window.location.hash = url;
			ng.LoadAjaxContent(url);
			return false;
		});
		
		$("#ajaxFormCari").submit(function (e) {
			var url = $(this).attr('action');
            window.location.hash = url;
			ng.LoadAjaxContentPost(url, $(this));
			return false;
		});

        $("#btnAdd").click(function () {
            url = '<?php echo $urlAdd;?>';
            ng.postOpenModalBox(url, 'Tambah <?php echo $title;?>', '', 'standart');
            return false;              
        });
        $("#btnAddpetunjuk").click(function () {
            url = '<?php echo $urlAdd;?>';
            ng.postOpenModalBox(url, 'Tambah <?php echo $title;?>','', 'large');
            return false;              
        });
        // ctrl + a
        $(document).on('keydown', function(e){
            if(e.ctrlKey && e.which === 65 || e.which === 97){
                e.preventDefault();
                $('#btnAdd').trigger('click');
                return false;
            }
        });

        $(".btnDetail").click(function () {
            key = $(this).parents('td').children('.key').val();
            url = '<?php echo $urlDetail; ?>'+key;
            ng.postOpenModalBox(url, 'Detail <?php echo $title;?>', '');
            return false;
        });

        $('.btnEdit').click(function (e) {
            key = $(this).parents('td').children('.key').val();
            url = '<?php echo $urlEdit; ?>'+key;
            ng.postOpenModalBox(url, 'Edit <?php echo $title;?>', '', 'standart');
            return false;
        });
         $('.btnEditPetunjuk').click(function (e) {
            key = $(this).parents('td').children('.key').val();
            url = '<?php echo $urlEdit; ?>'+key;
            ng.postOpenModalBox(url, 'Edit <?php echo $title;?>', '', 'large');
            return false;
        });

        $('.btnDelete').click(function (e) {
            key = $(this).parents('td').children('.key').val();
            url = '<?php echo $urlDelete; ?>'+key;
            ng.postOpenModalBox(url, 'Delete <?php echo $title;?>', '', 'standart');
            return false;
        });
        
        $('#btnPrintPDF').click(function () {
            var url = '<?php echo $linkCetak;?>/pdf';
            ng.exportTo('pdf', url, 'Cetak <?php echo @$titleCetak;?>');
        });

        $('#btnPrintEXCEL').click(function () {
            var url = '<?php echo $linkCetak;?>/excel';
            ng.exportTo('excel', url);
        });

        $('#btnPrintWORD').click(function () {
            var url = '<?php echo $linkCetak;?>/word';
            ng.exportTo('word', url);
        });  
        // $(".select2").select2(); 
    });
</script>

<style>
    td .btn {
        margin: 0px;
    }
</style>
