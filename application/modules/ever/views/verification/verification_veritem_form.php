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
 * @package Views/ever/verification
 */
?>
<?php

	$hasil = [];
	foreach ($hasilVerifikasi as $ver) {
		$hasil[$ver->ID] = $ver->HASIL;
	}

?>
<div class="box">
<!-- <div class="box box-primary"> -->
<!--     <div class="box-header with-border">
        <h3 class="box-title">History</h3>
    </div>

	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th width="10">No</th>
				<th width="170">Tanggal</th>
				<th width="170">Oleh</th>
				<th width="10">Hasil</th>
				<th>Catatan</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			foreach ($hasilVerifikasi as $ver) {
				if($ver->ID!=$ID){
					continue;
				}
				?>
			<tr>
				<td><?=++$i;?>.</td>
				<td><?=@$ver->CREATED_TIME?date('d-m-Y H:i:s', $ver->CREATED_TIME):'';?>
				</td>
				<td><?=@$ver->CREATED_BY;?></td>
				<td align="center"><?php
				if(@$ver->HASIL==1){
					echo '<i class="fa fa-check-square" style="cursor: pointer; color: blue;"></i>';
				}else{
					echo '<i class="fa fa-minus-square" style="cursor: pointer; color: red;"></i>';
				}
				?>
				</td>
				<td><?=$ver->CATATAN;?></td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table> -->

	<div class="box-header with-border">
        <h3 class="box-title">Verifikasi</h3>
    </div>

	<form class="form-horizontal" method="post" action="index.php/ever/verification/save/veritem" id="ajaxFormVeritem">
		<div class="form-group">
			<label class="col-sm-3 control-label">Hasil Verifikasi <span
				class="red-label">*</span>:
			</label>
			<div class="col-sm-5">
				<label>
					<input type="radio" name="HASIL" value="1" required <?=@$veritem->HASIL == 1 ? 'checked' : 'checked' ?>>
					<!-- <input type="radio" name="HASIL" value="1" required <?=@$hasil[$ID]==1?'checked':''?>> -->
					<i class="fa fa-check-square" style="cursor: pointer; color: blue;"></i> Diterima
				</label>
				<br>
				<label>
					<input type="radio" name="HASIL" value="-1" required <?=@$veritem->HASIL == -1 ? 'checked' : '' ?>>
					<!-- <input type="radio" name="HASIL" value="-1" required <?=@$hasil[$ID]==-1?'checked':''?>> -->
					<i class="fa fa-minus-square" style="cursor: pointer; color: red;"></i> Ditolak
				</label>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Catatan :</label>
			<div class="col-sm-5">
                            <textarea cols="40" rows="4" class="input-txt" name="CATATAN"><?=@$veritem->CATATAN?></textarea>
			</div>
		</div>
		<div class="pull-right">
			<input type="hidden" name="ID_LHKPN" value="<?php echo $item->ID_LHKPN;?>" required>
			<input type="hidden" name="ITEMVER" value="<?php echo $ITEMVER;?>" required>
			<input type="hidden" name="ID" value="<?php echo $ID;?>" required>
			<input type="hidden" name="act" value="doverify">
			<button type="submit" class="btn btn-sm btn-primary">Simpan</button>
			<button type="button" class="btn btn-sm btn-default" onclick="CloseModalBox();">Batal</button>
		</div>
	</form>

</div>

<!-- Nav tabs -->
<!-- <ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#veritem"
		aria-controls="veritem" role="tab" data-toggle="tab" class="navTab"
		title="verifikasi item"><span>Verifikasi Item</span> </a></li>
	<li role="presentation"><a href="#historyveritem"
		aria-controls="historyveritem" role="tab" data-toggle="tab"
		class="navTab" title="history verifikasi item"><span>History</span> </a>
	</li>
</ul> -->
<!-- Tab panes -->
<!-- <div class="tab-content"
	style="padding: 5px; border: 1px solid #cfcfcf; margin-top: -1px;"> -->
	<!--  -->
	<!-- <div role="tabpanel" class="tab-pane active" id="veritem">
		<div id="wrapperFormVeritem" class="form-horizontal">
			<form class="form-horizontal" method="post"
				action="index.php/ever/verification/save/veritem"
				id="ajaxFormVeritem">
				<div class="form-group">
					<label class="col-sm-3 control-label">Hasil Verifikasi <span
						class="red-label">*</span>:
					</label>
					<div class="col-sm-5">
						<label><input type="radio" name="HASIL" value="1" required
						<?=@$hasil[$ID]==1?'checked':''?>> <i class="fa fa-check-square"
							style="cursor: pointer; color: blue;"></i> Diterima</label><br> <label><input
							type="radio" name="HASIL" value="-1" required
							<?=@$hasil[$ID]==-1?'checked':''?>> <i class="fa fa-minus-square"
							style="cursor: pointer; color: red;"></i> Ditolak</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Catatan :</label>
					<div class="col-sm-5">
						<textarea name="CATATAN"></textarea>
					</div>
				</div>
				<div class="pull-right">
					<input type="hidden" name="ID_LHKPN"
						value="<?php echo $item->ID_LHKPN;?>" required> <input
						type="hidden" name="ITEMVER" value="<?php echo $ITEMVER;?>"
						required> <input type="hidden" name="ID" value="<?php echo $ID;?>"
						required> <input type="hidden" name="act" value="doverify">
					<button type="submit" class="btn btn-sm btn-primary">Simpan</button>
					<button type="button" class="btn btn-sm btn-default"
						onclick="CloseModalBox();">Batal</button>
				</div>
			</form>
		</div>
		<div class="clearfix"></div>
	</div> -->
	<!--  -->
	<!-- <div role="tabpanel" class="tab-pane" id="historyveritem">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th width="10">No</th>
					<th width="170">Tanggal</th>
					<th width="170">Oleh</th>
					<th width="10">Hasil</th>
					<th>Catatan</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i=0;
				foreach ($hasilVerifikasi as $ver) {
					if($ver->ID!=$ID){
						continue;
					}
					?>
				<tr>
					<td><?=++$i;?>.</td>
					<td><?=@$ver->CREATED_TIME?date('d-m-Y H:i:s', $ver->CREATED_TIME):'';?>
					</td>
					<td><?=@$ver->CREATED_BY;?></td>
					<td align="center"><?php
					if(@$ver->HASIL==1){
						echo '<i class="fa fa-check-square" style="cursor: pointer; color: blue;"></i>';
					}else{
						echo '<i class="fa fa-minus-square" style="cursor: pointer; color: red;"></i>';
					}
					?>
					</td>
					<td><?=$ver->CATATAN;?></td>
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>
</div> -->
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormVeritem"), 'insert','', ng.LoadAjaxTabContent, {url:'index.php/ever/verification/vertable/<?php echo $ITEMVER;?>/<?php echo substr(md5($item->ID_LHKPN),5,8);?>', block:'#block', container:$('<?=@$thisTab;?>').find('.contentTab')});
    });
</script>