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
 * @package Views/menu
*/
?>
<link href="<?php echo base_url();?>plugins/colorpicker-master/css/evol.colorpicker.min.css" rel="stylesheet" /> 
<script src="<?php echo base_url();?>plugins/colorpicker-master/js/evol.colorpicker.min.js" type="text/javascript"></script>
<?php
/** View Tree Menu form
 * 
 * @todo Marge with other location different parent and subtree view
 * @package Views
 */
class mytreemenu
{
	
	function __construct()
	{
		$CI = get_instance();
		$cari = $CI->input->post('cari', TRUE)?$CI->input->post('cari', TRUE):'';
		$sql = "SELECT 0 AS parent, GROUP_CONCAT( c.ID_MENU ORDER BY c.WEIGHT) AS children
		 FROM T_MENU AS c 
		 WHERE c.PARENT = 0
		 GROUP BY c.PARENT 
		UNION
		SELECT p.ID_MENU AS parent, GROUP_CONCAT( c.ID_MENU ORDER BY c.WEIGHT) AS children
		 FROM T_MENU AS p
		 JOIN T_MENU AS c ON c.PARENT = p.ID_MENU
		 WHERE p.MENU LIKE '%$cari%' OR p.SYSNAME LIKE '%$cari%' OR c.MENU LIKE '%$cari%' OR c.SYSNAME LIKE '%$cari%'
		 GROUP BY p.ID_MENU
		";
		// echo $sql;
		$res = $CI->db->query($sql);
		$parents = Array();
		foreach ($res->result() as $row){
			$parents[$row->parent] = explode(',',$row->children);   
		}
		$this->parents = $parents;

		$sql = "SELECT * FROM T_MENU";
		$res2 = $CI->db->query($sql);
		$rows = Array();
		foreach ($res2->result() as $row){
			$rows[$row->ID_MENU] = $row;
		}  
		$this->rows = $rows;   
		$this->parent = 0;   
	}

	function setParent($parent){
		$this->parent = $parent;
	}

	function subtree($id, $n=0) { 
		$parents = $this->parents;
		// print_r($this->rows);
		$rows = $this->rows;
		$ulclass = $liclass = $aclass = '';
		if($id>0){
			$row = $rows[$id];
			if (isset($parents[$id])){$ulclass='dropdown-menu';$liclass='dropdown';$aclass='dropdown-toggle';}else{$aclass='ajax-link';}
			// echo '<li class="'.$liclass.'">';
			// echo '<a href="'.(isset($row->URL)?$row->URL:'#')
			// .'" class="'.$aclass.'"><i class="fa '
			// .(isset($row->ICON)?$row->ICON:'')
			// .'" style="color:'.$row->ICON_COLOR.'"></i> '
			// .$row->MENU.'</a>';
			$selected = $row->ID_MENU==$this->parent?'selected':'';
			?>
				<option value="<?php echo $row->ID_MENU;?>" <?php echo $selected;?>><?php echo str_repeat('--', $n);?> <i class="fa <?php echo $row->ICON; ?>" style="color:<?php echo $row->ICON_COLOR; ?>;"></i> <?php echo $row->MENU; ?></option>
			<?php

			if (isset($parents[$id])){
				$n++;
				// echo '<ul class="'.$ulclass.'">';
				foreach ($parents[$id] as $child) 
				{
					// echo str_repeat('--', $n-1);
					$this->subtree($child, $n);
				}
				// echo '</ul>';
			} 
			// echo '</li>';
		}else{
			foreach ($parents[$id] as $child) 
			{
				// echo str_repeat('--', $n-1);
				$this->subtree($child, $n);
			}   
		}
	}
}

$mytreemenu = new mytreemenu();

if($form=='add'){
?>
<div id="wrapperFormAdd" class="form-horizontal">
	<form method="post" id="ajaxFormAdd" action="index.php/admin/menu/savemenu">
		
			<div class="form-group">
				<label class="col-sm-4 control-label">Parent :</label>
				<div class="col-sm-5">
					<select class="form-control" name="PARENT">
					<option value="0">-- Root --</option>
					<?php 
					$mytreemenu->subtree(0); 
					?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Weight :</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" name="WEIGHT" value="">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4 control-label">Icon :</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" name="ICON" id="ICON" value="" placeholder="ICON">
				</div>
				<div class="col-sm-3">
					<input type="text" class="form-control" name="ICON_COLOR" id="ICON_COLOR" value="" placeholder="ICON COLOR">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4 control-label">Menu <font color='red'>*</font> :</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="MENU" value="" required>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4 control-label">Link :</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="URL" value="">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4 control-label">Is Active :</label>
				<div class="col-sm-8">
					<label><input type="radio" name="IS_ACTIVE" value="1"> Active</label>
					<label><input type="radio" name="IS_ACTIVE" value="0"> inActive</label>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4 control-label">Sysname :</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="SYSNAME" value="">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Module :</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="MODULE" value="">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Controller :</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="CONTROLLER" value="">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Method :</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="METHOD" value="">
				</div>
			</div>

		<div class="pull-right">
			<input type="hidden" name="act" value="doinsert">
			<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
			<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
		</div>
	</form>
</div>
<?php
}
?>
<?php
if($form=='edit'){
?>
<div id="wrapperFormEdit" class="form-horizontal">
	<form method="post" id="ajaxFormEdit" action="index.php/admin/menu/savemenu">
			<div class="form-group">
				<label class="col-sm-4 control-label">Parent :</label>
				<div class="col-sm-5">
					<select class="form-control" name="PARENT">
					<option value="0">-- Root --</option>
					<?php 
					$mytreemenu->setParent($item->PARENT); 
					$mytreemenu->subtree(0); 
					?>
					</select> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Weight :</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="WEIGHT" value="<?php echo $item->WEIGHT;?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Icon :</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" name="ICON" id="ICON" value="<?php echo $item->ICON;?>" placeholder="ICON">
				</div>
				<div class="col-sm-3">	
					<input type="text" class="form-control" name="ICON_COLOR" id="ICON_COLOR" value="<?php echo $item->ICON_COLOR;?>" placeholder="ICON COLOR">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Menu <font color='red'>*</font> :</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="MENU" value="<?php echo $item->MENU;?>" required>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Link :</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="URL" value="<?php echo $item->URL;?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Is Active :</label>
				<div class="col-sm-8">
					<label><input type="radio" name="IS_ACTIVE" value="1" <?php echo $item->IS_ACTIVE==1?'checked':'';?>> Active</label>
					<label><input type="radio" name="IS_ACTIVE" value="0" <?php echo $item->IS_ACTIVE==0?'checked':'';?>> inActive</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Sysname :</label>
				<div class="col-sm-5">
					<input type="text" name="SYSNAME" class="form-control" value="<?php echo $item->SYSNAME;?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Module :</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="MODULE" value="<?php echo $item->MODULE;?>">
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-4 control-label">Controller :</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="CONTROLLER" value="<?php echo $item->CONTROLLER;?>">
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-4 control-label">Method :</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="METHOD" value="<?php echo $item->METHOD;?>">
				</div>
			</div>	
		<div class="pull-right">
			<input type="hidden" name="act" value="doupdate">
			<input type="hidden" name="ID_MENU" value="<?php echo $item->ID_MENU;?>">
			<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
			<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
		</div>
	</form>
</div>
<?php
}
?>
<?php
if($form=='delete'){
?>
<div id="wrapperFormDelete">
	Benarkah Akan Menghapus menu dibawah ini ?
	<form method="post" id="ajaxFormDelete" action="index.php/admin/menu/savemenu">
		<table>
			<tr>
				<td>Parent</td>
				<td>:</td>
				<td><?php echo $item->PARENT;?></td>
			</tr>
			<tr>
				<td>Menu</td>
				<td>:</td>
				<td><i class="fa <?php echo $item->ICON; ?>" style="color:<?php echo $item->ICON_COLOR; ?>;"></i> <?php echo $item->MENU;?></td>
			</tr>
			<tr>
				<td>Link</td>
				<td>:</td>
				<td><?php echo $item->URL;?></td>
			</tr>
			<tr>
				<td>Is Active</td>
				<td>:</td>
				<td>
				<?php echo $item->IS_ACTIVE;?>
				</td>
			</tr>
			<tr>
				<td>Sysname</td>
				<td>:</td>
				<td><?php echo $item->SYSNAME;?></td>
			</tr>
			<tr>
				<td>Module</td>
				<td>:</td>
				<td><?php echo $item->MODULE;?></td>
			</tr>
			<tr>
				<td>Controller</td>
				<td>:</td>
				<td><?php echo $item->CONTROLLER;?></td>
			</tr>
			<tr>
				<td>Method</td>
				<td>:</td>
				<td><?php echo $item->METHOD;?></td>
			</tr>
		</table>
		<div class="pull-right">
			<input type="hidden" name="act" value="dodelete">
			<input type="hidden" name="ID_MENU" value="<?php echo $item->ID_MENU;?>">
			<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Hapus</button>
			<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
		</div>
	</form>
</div>
<?php
}
?>

<div id="popupicon" class="evo-pop ui-widget ui-widget-content ui-corner-all"
	style="position: absolute;height:200px;padding-left:20px;overflow:auto;display:none;">
	
	<h4>11 New Icons in 4.0</h4>
	<div class="row">
		<div class="optionIcon"><i class="fa fa-rub"></i> fa-rub</div>
		<div class="optionIcon"><i class="fa fa-ruble"></i> fa-ruble <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-rouble"></i> fa-rouble <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-pagelines"></i> fa-pagelines</div>
		<div class="optionIcon"><i class="fa fa-stack-exchange"></i> fa-stack-exchange</div>
		<div class="optionIcon"><i class="fa fa-arrow-circle-o-right"></i> fa-arrow-circle-o-right</div>
		<div class="optionIcon"><i class="fa fa-arrow-circle-o-left"></i> fa-arrow-circle-o-left</div>
		<div class="optionIcon"><i class="fa fa-caret-square-o-left"></i> fa-caret-square-o-left</div>
		<div class="optionIcon"><i class="fa fa-toggle-left"></i> fa-toggle-left <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-dot-circle-o"></i> fa-dot-circle-o</div>
		<div class="optionIcon"><i class="fa fa-wheelchair"></i> fa-wheelchair</div>
		<div class="optionIcon"><i class="fa fa-vimeo-square"></i> fa-vimeo-square</div>
		<div class="optionIcon"><i class="fa fa-try"></i> fa-try</div>
		<div class="optionIcon"><i class="fa fa-turkish-lira"></i> fa-turkish-lira <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-plus-square-o"></i> fa-plus-square-o</div>
	</div>
	<h4>Web Application Icons</h4>
	<div class="row">
		<div class="optionIcon"><i class="fa fa-adjust"></i> fa-adjust</div>
		<div class="optionIcon"><i class="fa fa-anchor"></i> fa-anchor</div>
		<div class="optionIcon"><i class="fa fa-archive"></i> fa-archive</div>
		<div class="optionIcon"><i class="fa fa-arrows"></i> fa-arrows</div>
		<div class="optionIcon"><i class="fa fa-arrows-h"></i> fa-arrows-h</div>
		<div class="optionIcon"><i class="fa fa-arrows-v"></i> fa-arrows-v</div>
		<div class="optionIcon"><i class="fa fa-asterisk"></i> fa-asterisk</div>
		<div class="optionIcon"><i class="fa fa-ban"></i> fa-ban</div>
		<div class="optionIcon"><i class="fa fa-bar-chart-o"></i> fa-bar-chart-o</div>
		<div class="optionIcon"><i class="fa fa-barcode"></i> fa-barcode</div>
		<div class="optionIcon"><i class="fa fa-bars"></i> fa-bars</div>
		<div class="optionIcon"><i class="fa fa-beer"></i> fa-beer</div>
		<div class="optionIcon"><i class="fa fa-bell"></i> fa-bell</div>
		<div class="optionIcon"><i class="fa fa-bell-o"></i> fa-bell-o</div>
		<div class="optionIcon"><i class="fa fa-bolt"></i> fa-bolt</div>
		<div class="optionIcon"><i class="fa fa-book"></i> fa-book</div>
		<div class="optionIcon"><i class="fa fa-bookmark"></i> fa-bookmark</div>
		<div class="optionIcon"><i class="fa fa-bookmark-o"></i> fa-bookmark-o</div>
		<div class="optionIcon"><i class="fa fa-briefcase"></i> fa-briefcase</div>
		<div class="optionIcon"><i class="fa fa-bug"></i> fa-bug</div>
		<div class="optionIcon"><i class="fa fa-building-o"></i> fa-building-o</div>
		<div class="optionIcon"><i class="fa fa-bullhorn"></i> fa-bullhorn</div>
		<div class="optionIcon"><i class="fa fa-bullseye"></i> fa-bullseye</div>
		<div class="optionIcon"><i class="fa fa-calendar"></i> fa-calendar</div>
		<div class="optionIcon"><i class="fa fa-calendar-o"></i> fa-calendar-o</div>
		<div class="optionIcon"><i class="fa fa-camera"></i> fa-camera</div>
		<div class="optionIcon"><i class="fa fa-camera-retro"></i> fa-camera-retro</div>
		<div class="optionIcon"><i class="fa fa-caret-square-o-down"></i> fa-caret-square-o-down</div>
		<div class="optionIcon"><i class="fa fa-caret-square-o-left"></i> fa-caret-square-o-left</div>
		<div class="optionIcon"><i class="fa fa-caret-square-o-right"></i> fa-caret-square-o-right</div>
		<div class="optionIcon"><i class="fa fa-caret-square-o-up"></i> fa-caret-square-o-up</div>
		<div class="optionIcon"><i class="fa fa-certificate"></i> fa-certificate</div>
		<div class="optionIcon"><i class="fa fa-check"></i> fa-check</div>
		<div class="optionIcon"><i class="fa fa-check-circle"></i> fa-check-circle</div>
		<div class="optionIcon"><i class="fa fa-check-circle-o"></i> fa-check-circle-o</div>
		<div class="optionIcon"><i class="fa fa-check-square"></i> fa-check-square</div>
		<div class="optionIcon"><i class="fa fa-check-square-o"></i> fa-check-square-o</div>
		<div class="optionIcon"><i class="fa fa-circle"></i> fa-circle</div>
		<div class="optionIcon"><i class="fa fa-circle-o"></i> fa-circle-o</div>
		<div class="optionIcon"><i class="fa fa-clock-o"></i> fa-clock-o</div>
		<div class="optionIcon"><i class="fa fa-cloud"></i> fa-cloud</div>
		<div class="optionIcon"><i class="fa fa-cloud-download"></i> fa-cloud-download</div>
		<div class="optionIcon"><i class="fa fa-cloud-upload"></i> fa-cloud-upload</div>
		<div class="optionIcon"><i class="fa fa-code"></i> fa-code</div>
		<div class="optionIcon"><i class="fa fa-code-fork"></i> fa-code-fork</div>
		<div class="optionIcon"><i class="fa fa-coffee"></i> fa-coffee</div>
		<div class="optionIcon"><i class="fa fa-cog"></i> fa-cog</div>
		<div class="optionIcon"><i class="fa fa-cogs"></i> fa-cogs</div>
		<div class="optionIcon"><i class="fa fa-comment"></i> fa-comment</div>
		<div class="optionIcon"><i class="fa fa-comment-o"></i> fa-comment-o</div>
		<div class="optionIcon"><i class="fa fa-comments"></i> fa-comments</div>
		<div class="optionIcon"><i class="fa fa-comments-o"></i> fa-comments-o</div>
		<div class="optionIcon"><i class="fa fa-compass"></i> fa-compass</div>
		<div class="optionIcon"><i class="fa fa-credit-card"></i> fa-credit-card</div>
		<div class="optionIcon"><i class="fa fa-crop"></i> fa-crop</div>
		<div class="optionIcon"><i class="fa fa-crosshairs"></i> fa-crosshairs</div>
		<div class="optionIcon"><i class="fa fa-cutlery"></i> fa-cutlery</div>
		<div class="optionIcon"><i class="fa fa-dashboard"></i> fa-dashboard <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-desktop"></i> fa-desktop</div>
		<div class="optionIcon"><i class="fa fa-dot-circle-o"></i> fa-dot-circle-o</div>
		<div class="optionIcon"><i class="fa fa-download"></i> fa-download</div>
		<div class="optionIcon"><i class="fa fa-edit"></i> fa-edit <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-ellipsis-h"></i> fa-ellipsis-h</div>
		<div class="optionIcon"><i class="fa fa-ellipsis-v"></i> fa-ellipsis-v</div>
		<div class="optionIcon"><i class="fa fa-envelope"></i> fa-envelope</div>
		<div class="optionIcon"><i class="fa fa-envelope-o"></i> fa-envelope-o</div>
		<div class="optionIcon"><i class="fa fa-eraser"></i> fa-eraser</div>
		<div class="optionIcon"><i class="fa fa-exchange"></i> fa-exchange</div>
		<div class="optionIcon"><i class="fa fa-exclamation"></i> fa-exclamation</div>
		<div class="optionIcon"><i class="fa fa-exclamation-circle"></i> fa-exclamation-circle</div>
		<div class="optionIcon"><i class="fa fa-exclamation-triangle"></i> fa-exclamation-triangle</div>
		<div class="optionIcon"><i class="fa fa-external-link"></i> fa-external-link</div>
		<div class="optionIcon"><i class="fa fa-external-link-square"></i> fa-external-link-square</div>
		<div class="optionIcon"><i class="fa fa-eye"></i> fa-eye</div>
		<div class="optionIcon"><i class="fa fa-eye-slash"></i> fa-eye-slash</div>
		<div class="optionIcon"><i class="fa fa-female"></i> fa-female</div>
		<div class="optionIcon"><i class="fa fa-fighter-jet"></i> fa-fighter-jet</div>
		<div class="optionIcon"><i class="fa fa-film"></i> fa-film</div>
		<div class="optionIcon"><i class="fa fa-filter"></i> fa-filter</div>
		<div class="optionIcon"><i class="fa fa-fire"></i> fa-fire</div>
		<div class="optionIcon"><i class="fa fa-fire-extinguisher"></i> fa-fire-extinguisher</div>
		<div class="optionIcon"><i class="fa fa-flag"></i> fa-flag</div>
		<div class="optionIcon"><i class="fa fa-flag-checkered"></i> fa-flag-checkered</div>
		<div class="optionIcon"><i class="fa fa-flag-o"></i> fa-flag-o</div>
		<div class="optionIcon"><i class="fa fa-flash"></i> fa-flash <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-flask"></i> fa-flask</div>
		<div class="optionIcon"><i class="fa fa-folder"></i> fa-folder</div>
		<div class="optionIcon"><i class="fa fa-folder-o"></i> fa-folder-o</div>
		<div class="optionIcon"><i class="fa fa-folder-open"></i> fa-folder-open</div>
		<div class="optionIcon"><i class="fa fa-folder-open-o"></i> fa-folder-open-o</div>
		<div class="optionIcon"><i class="fa fa-frown-o"></i> fa-frown-o</div>
		<div class="optionIcon"><i class="fa fa-gamepad"></i> fa-gamepad</div>
		<div class="optionIcon"><i class="fa fa-gavel"></i> fa-gavel</div>
		<div class="optionIcon"><i class="fa fa-gear"></i> fa-gear <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-gears"></i> fa-gears <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-gift"></i> fa-gift</div>
		<div class="optionIcon"><i class="fa fa-glass"></i> fa-glass</div>
		<div class="optionIcon"><i class="fa fa-globe"></i> fa-globe</div>
		<div class="optionIcon"><i class="fa fa-group"></i> fa-group <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-hdd-o"></i> fa-hdd-o</div>
		<div class="optionIcon"><i class="fa fa-headphones"></i> fa-headphones</div>
		<div class="optionIcon"><i class="fa fa-heart"></i> fa-heart</div>
		<div class="optionIcon"><i class="fa fa-heart-o"></i> fa-heart-o</div>
		<div class="optionIcon"><i class="fa fa-home"></i> fa-home</div>
		<div class="optionIcon"><i class="fa fa-inbox"></i> fa-inbox</div>
		<div class="optionIcon"><i class="fa fa-info"></i> fa-info</div>
		<div class="optionIcon"><i class="fa fa-info-circle"></i> fa-info-circle</div>
		<div class="optionIcon"><i class="fa fa-key"></i> fa-key</div>
		<div class="optionIcon"><i class="fa fa-keyboard-o"></i> fa-keyboard-o</div>
		<div class="optionIcon"><i class="fa fa-laptop"></i> fa-laptop</div>
		<div class="optionIcon"><i class="fa fa-leaf"></i> fa-leaf</div>
		<div class="optionIcon"><i class="fa fa-legal"></i> fa-legal <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-lemon-o"></i> fa-lemon-o</div>
		<div class="optionIcon"><i class="fa fa-level-down"></i> fa-level-down</div>
		<div class="optionIcon"><i class="fa fa-level-up"></i> fa-level-up</div>
		<div class="optionIcon"><i class="fa fa-lightbulb-o"></i> fa-lightbulb-o</div>
		<div class="optionIcon"><i class="fa fa-location-arrow"></i> fa-location-arrow</div>
		<div class="optionIcon"><i class="fa fa-lock"></i> fa-lock</div>
		<div class="optionIcon"><i class="fa fa-magic"></i> fa-magic</div>
		<div class="optionIcon"><i class="fa fa-magnet"></i> fa-magnet</div>
		<div class="optionIcon"><i class="fa fa-mail-forward"></i> fa-mail-forward <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-mail-reply"></i> fa-mail-reply <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-mail-reply-all"></i> fa-mail-reply-all</div>
		<div class="optionIcon"><i class="fa fa-male"></i> fa-male</div>
		<div class="optionIcon"><i class="fa fa-map-marker"></i> fa-map-marker</div>
		<div class="optionIcon"><i class="fa fa-meh-o"></i> fa-meh-o</div>
		<div class="optionIcon"><i class="fa fa-microphone"></i> fa-microphone</div>
		<div class="optionIcon"><i class="fa fa-microphone-slash"></i> fa-microphone-slash</div>
		<div class="optionIcon"><i class="fa fa-minus"></i> fa-minus</div>
		<div class="optionIcon"><i class="fa fa-minus-circle"></i> fa-minus-circle</div>
		<div class="optionIcon"><i class="fa fa-minus-square"></i> fa-minus-square</div>
		<div class="optionIcon"><i class="fa fa-minus-square-o"></i> fa-minus-square-o</div>
		<div class="optionIcon"><i class="fa fa-mobile"></i> fa-mobile</div>
		<div class="optionIcon"><i class="fa fa-mobile-phone"></i> fa-mobile-phone <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-money"></i> fa-money</div>
		<div class="optionIcon"><i class="fa fa-moon-o"></i> fa-moon-o</div>
		<div class="optionIcon"><i class="fa fa-music"></i> fa-music</div>
		<div class="optionIcon"><i class="fa fa-pencil"></i> fa-pencil</div>
		<div class="optionIcon"><i class="fa fa-pencil-square"></i> fa-pencil-square</div>
		<div class="optionIcon"><i class="fa fa-pencil-square-o"></i> fa-pencil-square-o</div>
		<div class="optionIcon"><i class="fa fa-phone"></i> fa-phone</div>
		<div class="optionIcon"><i class="fa fa-phone-square"></i> fa-phone-square</div>
		<div class="optionIcon"><i class="fa fa-picture-o"></i> fa-picture-o</div>
		<div class="optionIcon"><i class="fa fa-plane"></i> fa-plane</div>
		<div class="optionIcon"><i class="fa fa-plus"></i> fa-plus</div>
		<div class="optionIcon"><i class="fa fa-plus-circle"></i> fa-plus-circle</div>
		<div class="optionIcon"><i class="fa fa-plus-square"></i> fa-plus-square</div>
		<div class="optionIcon"><i class="fa fa-plus-square-o"></i> fa-plus-square-o</div>
		<div class="optionIcon"><i class="fa fa-power-off"></i> fa-power-off</div>
		<div class="optionIcon"><i class="fa fa-print"></i> fa-print</div>
		<div class="optionIcon"><i class="fa fa-puzzle-piece"></i> fa-puzzle-piece</div>
		<div class="optionIcon"><i class="fa fa-qrcode"></i> fa-qrcode</div>
		<div class="optionIcon"><i class="fa fa-question"></i> fa-question</div>
		<div class="optionIcon"><i class="fa fa-question-circle"></i> fa-question-circle</div>
		<div class="optionIcon"><i class="fa fa-quote-left"></i> fa-quote-left</div>
		<div class="optionIcon"><i class="fa fa-quote-right"></i> fa-quote-right</div>
		<div class="optionIcon"><i class="fa fa-random"></i> fa-random</div>
		<div class="optionIcon"><i class="fa fa-refresh"></i> fa-refresh</div>
		<div class="optionIcon"><i class="fa fa-reply"></i> fa-reply</div>
		<div class="optionIcon"><i class="fa fa-reply-all"></i> fa-reply-all</div>
		<div class="optionIcon"><i class="fa fa-retweet"></i> fa-retweet</div>
		<div class="optionIcon"><i class="fa fa-road"></i> fa-road</div>
		<div class="optionIcon"><i class="fa fa-rocket"></i> fa-rocket</div>
		<div class="optionIcon"><i class="fa fa-rss"></i> fa-rss</div>
		<div class="optionIcon"><i class="fa fa-rss-square"></i> fa-rss-square</div>
		<div class="optionIcon"><i class="fa fa-search"></i> fa-search</div>
		<div class="optionIcon"><i class="fa fa-search-minus"></i> fa-search-minus</div>
		<div class="optionIcon"><i class="fa fa-search-plus"></i> fa-search-plus</div>
		<div class="optionIcon"><i class="fa fa-share"></i> fa-share</div>
		<div class="optionIcon"><i class="fa fa-share-square"></i> fa-share-square</div>
		<div class="optionIcon"><i class="fa fa-share-square-o"></i> fa-share-square-o</div>
		<div class="optionIcon"><i class="fa fa-shield"></i> fa-shield</div>
		<div class="optionIcon"><i class="fa fa-shopping-cart"></i> fa-shopping-cart</div>
		<div class="optionIcon"><i class="fa fa-sign-in"></i> fa-sign-in</div>
		<div class="optionIcon"><i class="fa fa-sign-out"></i> fa-sign-out</div>
		<div class="optionIcon"><i class="fa fa-signal"></i> fa-signal</div>
		<div class="optionIcon"><i class="fa fa-sitemap"></i> fa-sitemap</div>
		<div class="optionIcon"><i class="fa fa-smile-o"></i> fa-smile-o</div>
		<div class="optionIcon"><i class="fa fa-sort"></i> fa-sort</div>
		<div class="optionIcon"><i class="fa fa-sort-alpha-asc"></i> fa-sort-alpha-asc</div>
		<div class="optionIcon"><i class="fa fa-sort-alpha-desc"></i> fa-sort-alpha-desc</div>
		<div class="optionIcon"><i class="fa fa-sort-amount-asc"></i> fa-sort-amount-asc</div>
		<div class="optionIcon"><i class="fa fa-sort-amount-desc"></i> fa-sort-amount-desc</div>
		<div class="optionIcon"><i class="fa fa-sort-asc"></i> fa-sort-asc</div>
		<div class="optionIcon"><i class="fa fa-sort-desc"></i> fa-sort-desc</div>
		<div class="optionIcon"><i class="fa fa-sort-down"></i> fa-sort-down <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-sort-numeric-asc"></i> fa-sort-numeric-asc</div>
		<div class="optionIcon"><i class="fa fa-sort-numeric-desc"></i> fa-sort-numeric-desc</div>
		<div class="optionIcon"><i class="fa fa-sort-up"></i> fa-sort-up <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-spinner"></i> fa-spinner</div>
		<div class="optionIcon"><i class="fa fa-square"></i> fa-square</div>
		<div class="optionIcon"><i class="fa fa-square-o"></i> fa-square-o</div>
		<div class="optionIcon"><i class="fa fa-star"></i> fa-star</div>
		<div class="optionIcon"><i class="fa fa-star-half"></i> fa-star-half</div>
		<div class="optionIcon"><i class="fa fa-star-half-empty"></i> fa-star-half-empty <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-star-half-full"></i> fa-star-half-full <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-star-half-o"></i> fa-star-half-o</div>
		<div class="optionIcon"><i class="fa fa-star-o"></i> fa-star-o</div>
		<div class="optionIcon"><i class="fa fa-subscript"></i> fa-subscript</div>
		<div class="optionIcon"><i class="fa fa-suitcase"></i> fa-suitcase</div>
		<div class="optionIcon"><i class="fa fa-sun-o"></i> fa-sun-o</div>
		<div class="optionIcon"><i class="fa fa-superscript"></i> fa-superscript</div>
		<div class="optionIcon"><i class="fa fa-tablet"></i> fa-tablet</div>
		<div class="optionIcon"><i class="fa fa-tachometer"></i> fa-tachometer</div>
		<div class="optionIcon"><i class="fa fa-tag"></i> fa-tag</div>
		<div class="optionIcon"><i class="fa fa-tags"></i> fa-tags</div>
		<div class="optionIcon"><i class="fa fa-tasks"></i> fa-tasks</div>
		<div class="optionIcon"><i class="fa fa-terminal"></i> fa-terminal</div>
		<div class="optionIcon"><i class="fa fa-thumb-tack"></i> fa-thumb-tack</div>
		<div class="optionIcon"><i class="fa fa-thumbs-down"></i> fa-thumbs-down</div>
		<div class="optionIcon"><i class="fa fa-thumbs-o-down"></i> fa-thumbs-o-down</div>
		<div class="optionIcon"><i class="fa fa-thumbs-o-up"></i> fa-thumbs-o-up</div>
		<div class="optionIcon"><i class="fa fa-thumbs-up"></i> fa-thumbs-up</div>
		<div class="optionIcon"><i class="fa fa-ticket"></i> fa-ticket</div>
		<div class="optionIcon"><i class="fa fa-trash"></i> fa-trash</div>
		<div class="optionIcon"><i class="fa fa-trash-circle"></i> fa-trash-circle</div>
		<div class="optionIcon"><i class="fa fa-trash-circle-o"></i> fa-trash-circle-o</div>
		<div class="optionIcon"><i class="fa fa-tint"></i> fa-tint</div>
		<div class="optionIcon"><i class="fa fa-toggle-down"></i> fa-toggle-down <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-toggle-left"></i> fa-toggle-left <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-toggle-right"></i> fa-toggle-right <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-toggle-up"></i> fa-toggle-up <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-trash-o"></i> fa-trash-o</div>
		<div class="optionIcon"><i class="fa fa-trophy"></i> fa-trophy</div>
		<div class="optionIcon"><i class="fa fa-truck"></i> fa-truck</div>
		<div class="optionIcon"><i class="fa fa-umbrella"></i> fa-umbrella</div>
		<div class="optionIcon"><i class="fa fa-unlock"></i> fa-unlock</div>
		<div class="optionIcon"><i class="fa fa-unlock-alt"></i> fa-unlock-alt</div>
		<div class="optionIcon"><i class="fa fa-unsorted"></i> fa-unsorted <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-upload"></i> fa-upload</div>
		<div class="optionIcon"><i class="fa fa-user"></i> fa-user</div>
		<div class="optionIcon"><i class="fa fa-users"></i> fa-users</div>
		<div class="optionIcon"><i class="fa fa-video-camera"></i> fa-video-camera</div>
		<div class="optionIcon"><i class="fa fa-volume-down"></i> fa-volume-down</div>
		<div class="optionIcon"><i class="fa fa-volume-off"></i> fa-volume-off</div>
		<div class="optionIcon"><i class="fa fa-volume-up"></i> fa-volume-up</div>
		<div class="optionIcon"><i class="fa fa-warning"></i> fa-warning <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-wheelchair"></i> fa-wheelchair</div>
		<div class="optionIcon"><i class="fa fa-wrench"></i> fa-wrench</div>
	</div>
	<h4>Form Control Icons</h4>
	<div class="row">
		<div class="optionIcon"><i class="fa fa-check-square"></i> fa-check-square</div>
		<div class="optionIcon"><i class="fa fa-check-square-o"></i> fa-check-square-o</div>
		<div class="optionIcon"><i class="fa fa-circle"></i> fa-circle</div>
		<div class="optionIcon"><i class="fa fa-circle-o"></i> fa-circle-o</div>
		<div class="optionIcon"><i class="fa fa-dot-circle-o"></i> fa-dot-circle-o</div>
		<div class="optionIcon"><i class="fa fa-minus-square"></i> fa-minus-square</div>
		<div class="optionIcon"><i class="fa fa-minus-square-o"></i> fa-minus-square-o</div>
		<div class="optionIcon"><i class="fa fa-plus-square"></i> fa-plus-square</div>
		<div class="optionIcon"><i class="fa fa-plus-square-o"></i> fa-plus-square-o</div>
		<div class="optionIcon"><i class="fa fa-square"></i> fa-square</div>
		<div class="optionIcon"><i class="fa fa-square-o"></i> fa-square-o</div>
	</div>
	<h4>Currency Icons</h4>
	<div class="row">
		<div class="optionIcon"><i class="fa fa-bitcoin"></i> fa-bitcoin <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-btc"></i> fa-btc</div>
		<div class="optionIcon"><i class="fa fa-cny"></i> fa-cny <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-dollar"></i> fa-dollar <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-eur"></i> fa-eur</div>
		<div class="optionIcon"><i class="fa fa-euro"></i> fa-euro <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-gbp"></i> fa-gbp</div>
		<div class="optionIcon"><i class="fa fa-inr"></i> fa-inr</div>
		<div class="optionIcon"><i class="fa fa-jpy"></i> fa-jpy</div>
		<div class="optionIcon"><i class="fa fa-krw"></i> fa-krw</div>
		<div class="optionIcon"><i class="fa fa-money"></i> fa-money</div>
		<div class="optionIcon"><i class="fa fa-rmb"></i> fa-rmb <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-rouble"></i> fa-rouble <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-rub"></i> fa-rub</div>
		<div class="optionIcon"><i class="fa fa-ruble"></i> fa-ruble <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-rupee"></i> fa-rupee <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-try"></i> fa-try</div>
		<div class="optionIcon"><i class="fa fa-turkish-lira"></i> fa-turkish-lira <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-usd"></i> fa-usd</div>
		<div class="optionIcon"><i class="fa fa-won"></i> fa-won <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-yen"></i> fa-yen <span class="text-muted">(alias)</span></div>
	</div>
	<h4>Text Editor Icons</h4>
	<div class="row">
		<div class="optionIcon"><i class="fa fa-align-center"></i> fa-align-center</div>
		<div class="optionIcon"><i class="fa fa-align-justify"></i> fa-align-justify</div>
		<div class="optionIcon"><i class="fa fa-align-left"></i> fa-align-left</div>
		<div class="optionIcon"><i class="fa fa-align-right"></i> fa-align-right</div>
		<div class="optionIcon"><i class="fa fa-bold"></i> fa-bold</div>
		<div class="optionIcon"><i class="fa fa-chain"></i> fa-chain <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-chain-broken"></i> fa-chain-broken</div>
		<div class="optionIcon"><i class="fa fa-clipboard"></i> fa-clipboard</div>
		<div class="optionIcon"><i class="fa fa-columns"></i> fa-columns</div>
		<div class="optionIcon"><i class="fa fa-copy"></i> fa-copy <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-cut"></i> fa-cut <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-dedent"></i> fa-dedent <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-eraser"></i> fa-eraser</div>
		<div class="optionIcon"><i class="fa fa-file"></i> fa-file</div>
		<div class="optionIcon"><i class="fa fa-file-o"></i> fa-file-o</div>
		<div class="optionIcon"><i class="fa fa-file-text"></i> fa-file-text</div>
		<div class="optionIcon"><i class="fa fa-file-text-o"></i> fa-file-text-o</div>
		<div class="optionIcon"><i class="fa fa-files-o"></i> fa-files-o</div>
		<div class="optionIcon"><i class="fa fa-floppy-o"></i> fa-floppy-o</div>
		<div class="optionIcon"><i class="fa fa-font"></i> fa-font</div>
		<div class="optionIcon"><i class="fa fa-indent"></i> fa-indent</div>
		<div class="optionIcon"><i class="fa fa-italic"></i> fa-italic</div>
		<div class="optionIcon"><i class="fa fa-link"></i> fa-link</div>
		<div class="optionIcon"><i class="fa fa-list"></i> fa-list</div>
		<div class="optionIcon"><i class="fa fa-list-alt"></i> fa-list-alt</div>
		<div class="optionIcon"><i class="fa fa-list-ol"></i> fa-list-ol</div>
		<div class="optionIcon"><i class="fa fa-list-ul"></i> fa-list-ul</div>
		<div class="optionIcon"><i class="fa fa-outdent"></i> fa-outdent</div>
		<div class="optionIcon"><i class="fa fa-paperclip"></i> fa-paperclip</div>
		<div class="optionIcon"><i class="fa fa-paste"></i> fa-paste <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-repeat"></i> fa-repeat</div>
		<div class="optionIcon"><i class="fa fa-rotate-left"></i> fa-rotate-left <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-rotate-right"></i> fa-rotate-right <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-save"></i> fa-save <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-scissors"></i> fa-scissors</div>
		<div class="optionIcon"><i class="fa fa-strikethrough"></i> fa-strikethrough</div>
		<div class="optionIcon"><i class="fa fa-table"></i> fa-table</div>
		<div class="optionIcon"><i class="fa fa-text-height"></i> fa-text-height</div>
		<div class="optionIcon"><i class="fa fa-text-width"></i> fa-text-width</div>
		<div class="optionIcon"><i class="fa fa-th"></i> fa-th</div>
		<div class="optionIcon"><i class="fa fa-th-large"></i> fa-th-large</div>
		<div class="optionIcon"><i class="fa fa-th-list"></i> fa-th-list</div>
		<div class="optionIcon"><i class="fa fa-underline"></i> fa-underline</div>
		<div class="optionIcon"><i class="fa fa-undo"></i> fa-undo</div>
		<div class="optionIcon"><i class="fa fa-unlink"></i> fa-unlink <span class="text-muted">(alias)</span></div>
	</div>
	<h4>Directional Icons</h4>
	<div class="row">
		<div class="optionIcon"><i class="fa fa-angle-double-down"></i> fa-angle-double-down</div>
		<div class="optionIcon"><i class="fa fa-angle-double-left"></i> fa-angle-double-left</div>
		<div class="optionIcon"><i class="fa fa-angle-double-right"></i> fa-angle-double-right</div>
		<div class="optionIcon"><i class="fa fa-angle-double-up"></i> fa-angle-double-up</div>
		<div class="optionIcon"><i class="fa fa-angle-down"></i> fa-angle-down</div>
		<div class="optionIcon"><i class="fa fa-angle-left"></i> fa-angle-left</div>
		<div class="optionIcon"><i class="fa fa-angle-right"></i> fa-angle-right</div>
		<div class="optionIcon"><i class="fa fa-angle-up"></i> fa-angle-up</div>
		<div class="optionIcon"><i class="fa fa-arrow-circle-down"></i> fa-arrow-circle-down</div>
		<div class="optionIcon"><i class="fa fa-arrow-circle-left"></i> fa-arrow-circle-left</div>
		<div class="optionIcon"><i class="fa fa-arrow-circle-o-down"></i> fa-arrow-circle-o-down</div>
		<div class="optionIcon"><i class="fa fa-arrow-circle-o-left"></i> fa-arrow-circle-o-left</div>
		<div class="optionIcon"><i class="fa fa-arrow-circle-o-right"></i> fa-arrow-circle-o-right</div>
		<div class="optionIcon"><i class="fa fa-arrow-circle-o-up"></i> fa-arrow-circle-o-up</div>
		<div class="optionIcon"><i class="fa fa-arrow-circle-right"></i> fa-arrow-circle-right</div>
		<div class="optionIcon"><i class="fa fa-arrow-circle-up"></i> fa-arrow-circle-up</div>
		<div class="optionIcon"><i class="fa fa-arrow-down"></i> fa-arrow-down</div>
		<div class="optionIcon"><i class="fa fa-arrow-left"></i> fa-arrow-left</div>
		<div class="optionIcon"><i class="fa fa-arrow-right"></i> fa-arrow-right</div>
		<div class="optionIcon"><i class="fa fa-arrow-up"></i> fa-arrow-up</div>
		<div class="optionIcon"><i class="fa fa-arrows"></i> fa-arrows</div>
		<div class="optionIcon"><i class="fa fa-arrows-alt"></i> fa-arrows-alt</div>
		<div class="optionIcon"><i class="fa fa-arrows-h"></i> fa-arrows-h</div>
		<div class="optionIcon"><i class="fa fa-arrows-v"></i> fa-arrows-v</div>
		<div class="optionIcon"><i class="fa fa-caret-down"></i> fa-caret-down</div>
		<div class="optionIcon"><i class="fa fa-caret-left"></i> fa-caret-left</div>
		<div class="optionIcon"><i class="fa fa-caret-right"></i> fa-caret-right</div>
		<div class="optionIcon"><i class="fa fa-caret-square-o-down"></i> fa-caret-square-o-down</div>
		<div class="optionIcon"><i class="fa fa-caret-square-o-left"></i> fa-caret-square-o-left</div>
		<div class="optionIcon"><i class="fa fa-caret-square-o-right"></i> fa-caret-square-o-right</div>
		<div class="optionIcon"><i class="fa fa-caret-square-o-up"></i> fa-caret-square-o-up</div>
		<div class="optionIcon"><i class="fa fa-caret-up"></i> fa-caret-up</div>
		<div class="optionIcon"><i class="fa fa-chevron-circle-down"></i> fa-chevron-circle-down</div>
		<div class="optionIcon"><i class="fa fa-chevron-circle-left"></i> fa-chevron-circle-left</div>
		<div class="optionIcon"><i class="fa fa-chevron-circle-right"></i> fa-chevron-circle-right</div>
		<div class="optionIcon"><i class="fa fa-chevron-circle-up"></i> fa-chevron-circle-up</div>
		<div class="optionIcon"><i class="fa fa-chevron-down"></i> fa-chevron-down</div>
		<div class="optionIcon"><i class="fa fa-chevron-left"></i> fa-chevron-left</div>
		<div class="optionIcon"><i class="fa fa-chevron-right"></i> fa-chevron-right</div>
		<div class="optionIcon"><i class="fa fa-chevron-up"></i> fa-chevron-up</div>
		<div class="optionIcon"><i class="fa fa-hand-o-down"></i> fa-hand-o-down</div>
		<div class="optionIcon"><i class="fa fa-hand-o-left"></i> fa-hand-o-left</div>
		<div class="optionIcon"><i class="fa fa-hand-o-right"></i> fa-hand-o-right</div>
		<div class="optionIcon"><i class="fa fa-hand-o-up"></i> fa-hand-o-up</div>
		<div class="optionIcon"><i class="fa fa-long-arrow-down"></i> fa-long-arrow-down</div>
		<div class="optionIcon"><i class="fa fa-long-arrow-left"></i> fa-long-arrow-left</div>
		<div class="optionIcon"><i class="fa fa-long-arrow-right"></i> fa-long-arrow-right</div>
		<div class="optionIcon"><i class="fa fa-long-arrow-up"></i> fa-long-arrow-up</div>
		<div class="optionIcon"><i class="fa fa-toggle-down"></i> fa-toggle-down <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-toggle-left"></i> fa-toggle-left <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-toggle-right"></i> fa-toggle-right <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-toggle-up"></i> fa-toggle-up <span class="text-muted">(alias)</span></div>
	</div>
	<h4>Video Player Icons</h4>
	<div class="row">
		<div class="optionIcon"><i class="fa fa-arrows-alt"></i> fa-arrows-alt</div>
		<div class="optionIcon"><i class="fa fa-backward"></i> fa-backward</div>
		<div class="optionIcon"><i class="fa fa-compress"></i> fa-compress</div>
		<div class="optionIcon"><i class="fa fa-eject"></i> fa-eject</div>
		<div class="optionIcon"><i class="fa fa-expand"></i> fa-expand</div>
		<div class="optionIcon"><i class="fa fa-fast-backward"></i> fa-fast-backward</div>
		<div class="optionIcon"><i class="fa fa-fast-forward"></i> fa-fast-forward</div>
		<div class="optionIcon"><i class="fa fa-forward"></i> fa-forward</div>
		<div class="optionIcon"><i class="fa fa-pause"></i> fa-pause</div>
		<div class="optionIcon"><i class="fa fa-play"></i> fa-play</div>
		<div class="optionIcon"><i class="fa fa-play-circle"></i> fa-play-circle</div>
		<div class="optionIcon"><i class="fa fa-play-circle-o"></i> fa-play-circle-o</div>
		<div class="optionIcon"><i class="fa fa-step-backward"></i> fa-step-backward</div>
		<div class="optionIcon"><i class="fa fa-step-forward"></i> fa-step-forward</div>
		<div class="optionIcon"><i class="fa fa-stop"></i> fa-stop</div>
		<div class="optionIcon"><i class="fa fa-youtube-play"></i> fa-youtube-play</div>
	</div>
	<h4>Brand Icons</h4>
	<div class="row">
		<div class="optionIcon"><i class="fa fa-adn"></i> fa-adn</div>
		<div class="optionIcon"><i class="fa fa-android"></i> fa-android</div>
		<div class="optionIcon"><i class="fa fa-apple"></i> fa-apple</div>
		<div class="optionIcon"><i class="fa fa-bitbucket"></i> fa-bitbucket</div>
		<div class="optionIcon"><i class="fa fa-bitbucket-square"></i> fa-bitbucket-square</div>
		<div class="optionIcon"><i class="fa fa-bitcoin"></i> fa-bitcoin <span class="text-muted">(alias)</span></div>
		<div class="optionIcon"><i class="fa fa-btc"></i> fa-btc</div>
		<div class="optionIcon"><i class="fa fa-css3"></i> fa-css3</div>
		<div class="optionIcon"><i class="fa fa-dribbble"></i> fa-dribbble</div>
		<div class="optionIcon"><i class="fa fa-dropbox"></i> fa-dropbox</div>
		<div class="optionIcon"><i class="fa fa-facebook"></i> fa-facebook</div>
		<div class="optionIcon"><i class="fa fa-facebook-square"></i> fa-facebook-square</div>
		<div class="optionIcon"><i class="fa fa-flickr"></i> fa-flickr</div>
		<div class="optionIcon"><i class="fa fa-foursquare"></i> fa-foursquare</div>
		<div class="optionIcon"><i class="fa fa-github"></i> fa-github</div>
		<div class="optionIcon"><i class="fa fa-github-alt"></i> fa-github-alt</div>
		<div class="optionIcon"><i class="fa fa-github-square"></i> fa-github-square</div>
		<div class="optionIcon"><i class="fa fa-gittip"></i> fa-gittip</div>
		<div class="optionIcon"><i class="fa fa-google-plus"></i> fa-google-plus</div>
		<div class="optionIcon"><i class="fa fa-google-plus-square"></i> fa-google-plus-square</div>
		<div class="optionIcon"><i class="fa fa-html5"></i> fa-html5</div>
		<div class="optionIcon"><i class="fa fa-instagram"></i> fa-instagram</div>
		<div class="optionIcon"><i class="fa fa-linkedin"></i> fa-linkedin</div>
		<div class="optionIcon"><i class="fa fa-linkedin-square"></i> fa-linkedin-square</div>
		<div class="optionIcon"><i class="fa fa-linux"></i> fa-linux</div>
		<div class="optionIcon"><i class="fa fa-maxcdn"></i> fa-maxcdn</div>
		<div class="optionIcon"><i class="fa fa-pagelines"></i> fa-pagelines</div>
		<div class="optionIcon"><i class="fa fa-pinterest"></i> fa-pinterest</div>
		<div class="optionIcon"><i class="fa fa-pinterest-square"></i> fa-pinterest-square</div>
		<div class="optionIcon"><i class="fa fa-renren"></i> fa-renren</div>
		<div class="optionIcon"><i class="fa fa-skype"></i> fa-skype</div>
		<div class="optionIcon"><i class="fa fa-stack-exchange"></i> fa-stack-exchange</div>
		<div class="optionIcon"><i class="fa fa-stack-overflow"></i> fa-stack-overflow</div>
		<div class="optionIcon"><i class="fa fa-trello"></i> fa-trello</div>
		<div class="optionIcon"><i class="fa fa-tumblr"></i> fa-tumblr</div>
		<div class="optionIcon"><i class="fa fa-tumblr-square"></i> fa-tumblr-square</div>
		<div class="optionIcon"><i class="fa fa-twitter"></i> fa-twitter</div>
		<div class="optionIcon"><i class="fa fa-twitter-square"></i> fa-twitter-square</div>
		<div class="optionIcon"><i class="fa fa-vimeo-square"></i> fa-vimeo-square</div>
		<div class="optionIcon"><i class="fa fa-vk"></i> fa-vk</div>
		<div class="optionIcon"><i class="fa fa-weibo"></i> fa-weibo</div>
		<div class="optionIcon"><i class="fa fa-windows"></i> fa-windows</div>
		<div class="optionIcon"><i class="fa fa-xing"></i> fa-xing</div>
		<div class="optionIcon"><i class="fa fa-xing-square"></i> fa-xing-square</div>
		<div class="optionIcon"><i class="fa fa-youtube"></i> fa-youtube</div>
		<div class="optionIcon"><i class="fa fa-youtube-play"></i> fa-youtube-play</div>
		<div class="optionIcon"><i class="fa fa-youtube-square"></i> fa-youtube-square</div>
	</div>
	<h4>Medical Icons</h4>
	<div class="row">
		<div class="optionIcon"><i class="fa fa-ambulance"></i> fa-ambulance</div>
		<div class="optionIcon"><i class="fa fa-h-square"></i> fa-h-square</div>
		<div class="optionIcon"><i class="fa fa-hospital-o"></i> fa-hospital-o</div>
		<div class="optionIcon"><i class="fa fa-medkit"></i> fa-medkit</div>
		<div class="optionIcon"><i class="fa fa-plus-square"></i> fa-plus-square</div>
		<div class="optionIcon"><i class="fa fa-stethoscope"></i> fa-stethoscope</div>
		<div class="optionIcon"><i class="fa fa-user-md"></i> fa-user-md</div>
		<div class="optionIcon"><i class="fa fa-wheelchair"></i> fa-wheelchair</div>
	</div>
</div>

<script language="javascript" type="text/javascript">
	$(document).ready(function () {
		// $('#ICON_COLOR').colorpicker();
		
		// $('#ICON').after($('#popupicon'));

		// $('#ICON').focus(function(){
		// 	$('#popupicon').show();
		// });
		// // $('#ICON').focusout(function(){
		// //    $('#popupicon').hide();
		// // });

		// $('.optionIcon').css('cursor','pointer');

		// i = $('i');
		// $('.optionIcon').click(function(){
		// 	a = $(this).find('i').attr('class').split(" ");
		// 	$('#ICON').val(a[1]);
		// 	$('#popupicon').hide();
		// });

	}); 
</script>