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
 * @package Views/efill/lhkpn_view
*/
?>
<div class="box-body" id="wrapperPengeluaran">
  <ul class="nav nav-tabs" role="tablist">
       <?php 
           $idtext3    = [];
           $tot3       = 0;
           $sub3       = '0';
           $label      = array('A', 'B', 'C');
           $golongan = array(
                               'PENGELUARAN UMUM',
                               'PENGELUARAN HARTA',
                               'PENGELUARAN LAINNYA',
                            );
           for ($i=0; $i < count($golongan); $i++) : 
       ?>
       <li class="<?=($i == 0) ? 'active' : ''; ?>">
         <a href="#tabs3<?=$label[$i]?>" data-toggle="tab"><?=$label[$i]?>. <?=$golongan[$i]?></a>
       </li>
       <?php endfor; ?>
   </ul>
   <?php 
       $jenis = array(
                       array(
                               'Biaya Rumah Tangga',
                               'Biaya Transportasi',
                               'Biaya Pendidikan',
                               'Biaya Kesehatan',
                               'Biaya Keagamaan/Adat',
                               'Biaya Rekreasi',
                               'Pembayaran Pajak'
                            ),
                       array(
                               'Pembelian atau Perolehan Harta Baru',
                               'Rehabilitasi / Renovasi / Modifikasi Harta'
                            ),
                       array(
                               'Biaya Pengurusan Waris/hibah/hadiah',
                               'Pelunasan/Angsuran Hutang'
                            )
                     ); 
       $where     = "WHERE IS_ACTIVE = '1' AND ID_LHKPN = '$id_lhkpn'";
       $getPeka   = $this->mlhkpn->getValue('T_LHKPN_PENGELUARAN_KAS', $where);
       $PN        = @json_decode($getPeka[0]->NILAI_PENGELUARAN_KAS);
       $lain      = @json_decode($getPeka[0]->LAINNYA);
   ?>
  <div class="tab-content">
     <?php 
        for ($i=0; $i < count($jenis); $i++) :
     ?>
        <div class="tab-pane <?=($i == 0) ? 'active' : '';?>" id="tabs3<?=$label[$i]?>">
        <?php 
            $getData = $this->mlhkpn->getInpekas($id_lhkpn, 'T_LHKPN_PENERIMAAN_KAS', 'M_JENIS_PENERIMAAN_KAS', 'M_GOLONGAN_PENERIMAAN_KAS', $golongan[$i], '1');
        ?>
            <table class="table table-bordered table-hover">
                <thead class="table-header">
                    <tr>
                        <th width="40px">NO</th>
                        <th>JENIS PENGELUARAN</th>
                        <th>TOTAL NILAI PENGELUARAN KAS</th>
                    </tr>
                </thead>
                <tbody class="tabs<?=$label[$i]?>">
                    <?php 
                        for ($j=0; $j < count($jenis[$i]); $j++) : 
                    ?>
                    <tr>
                        <td><center><?=($j+1)?></center></td>
                        <td><?=$jenis[$i][$j] ?></td>
                        <td align="right">
                            <?php $code = $label[$i].$j; ?>
                          Rp. <?=(@$PN->$label[$i]->$code != '') ? @$PN->$label[$i]->$code : ' -'?>
                        </td>
                    </tr>
                    <?php
                        endfor;
                        $label_text = $label[$i];
                        $idtext3[$label_text] = ($j+1);
                        $code = $label[$i].$j;
                    ?>
                    <tr>
                        <td><center><?=($j+1)?></center></td>
                        <td>PENGELUARAN dari pekerjaan lainnya : <?=(@$lain->$label[$i] != '') ? @$lain->$label[$i] : ' -'?>
                        <td align="right">Rp. <?=(@$PN->$label[$i]->$code != '') ? @$PN->$label[$i]->$code : ' -'?></td>
                    </tr>
                </tbody>
            </table>
        </div>
     <?php 
        endfor;
        $idtext3 = json_encode($idtext3);
        $cnt = count($PN);
        if($cnt != 0){
          $total    = [];
          $totalALL = 0;
          foreach ($PN as $key => $value) {
              $total[$key] = 0;
              foreach ($value as $hasil => $nilai) {
                  $total[$key] = $total[$key] + str_replace(".","",$nilai);
              }
              $totalALL = $totalALL + $total[$key];
          }
        }else{
          $total         = [];
          $totalALL      = 0;
        }
     ?>
     <div style="float: bottom;">
       <?php
           foreach ($total as $key => $value) {
               ?>
                   Total <?=$key?> : Rp. <?php echo number_format($value,0,",","."); ?><br>
               <?php
           }
       ?>
    </div>
    <b>TOTAL PENERIMAAN  (A + B + C)</b> <b>Rp. <span class="total_semua_penerimaan2"><?php echo number_format($totalALL,0,",","."); ?></span></b>
    <hr>
  </div>
  <div class="pull-right">
    <button type="button" class="btn btn-sm btn-warning btnPreviousPengeluaran"><i class="fa fa-backward"></i> Sebelumnya</button>
    <button type="button" class="btn btn-sm btn-warning btnNextPengeluaran">Selanjutnya <i class="fa fa-forward"></i></button>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('.btnNextPengeluaran').click(function(e){
      e.preventDefault();
      if($('#wrapperPengeluaran > .nav-tabs > .active').next('li').find('a').attr('href')==undefined){
        $('.nav-tabs > .active').next('li').find('a').trigger('click');
      }else{
        $('#wrapperPengeluaran > .nav-tabs > .active').next('li').find('a').trigger('click');
      }
    });
    $('.btnPreviousPengeluaran').click(function(e){
      e.preventDefault();
      if($('#wrapperPengeluaran > .nav-tabs > .active').prev('li').find('a').attr('href')==undefined){
        $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        $('#wrapperPenerimaan > .nav-tabs').find('a[href="#tabsC"]').trigger('click');
      }else{
        $('#wrapperPengeluaran > .nav-tabs > .active').prev('li').find('a').trigger('click');
      }
    });
  });
</script>
