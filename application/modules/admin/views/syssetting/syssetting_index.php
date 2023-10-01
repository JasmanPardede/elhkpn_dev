<div id="linkSysSetting">	
	 <div class="box box-primary">
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>Print</th>
			</tr>
		</thead>
		<tbody>
			<tr><td><a href="index.php/admin/syssetting/index/tts">Setting Logo KPK</a></td></tr>
			<tr><td><a href="index.php/admin/syssetting/index/direkturpp">Setting Direktur PP LHKPN</a></td></tr>
			<tr><td><a href="index.php/admin/syssetting/index/direkturpencegahan">Setting Direktur Bidang Pencegahan</a></td></tr>
		</tbody>
	</table>
</div>

<script type="text/javascript">
	$(document).ready(function() {
        $("#linkSysSetting").find("a").click(function () {
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContent(url);
            return false;
        });  		
	});
</script>

<style type="text/css">
	#linkSysSetting a{
		display: block;
	}
</style>