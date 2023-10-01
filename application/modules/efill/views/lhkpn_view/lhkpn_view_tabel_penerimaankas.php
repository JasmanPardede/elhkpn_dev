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
<div class="box-body" id="wrapperPenerimaan">
    <ul class="nav nav-tabs" role="tablist">
         <?php
             $idtext2    = [];
             $tot2       = 0;
             $label      = array('A', 'B', 'C');
             $golongan = array(
                                 'PENERIMAAN PEKERJAAN',
                                 'PENERIMAAN USAHA / KEKAYAAN',
                                 'PENERIMAAN LAINNYA',
                              );
             for ($i=0; $i < count($golongan); $i++) : 
         ?>
         <li class="<?=($i == 0) ? 'active' : ''; ?>">
           <a href="#tabs<?=$label[$i]?>" data-toggle="tab"><?=$label[$i]?>. <?=$golongan[$i]?></a>
         </li>
         <?php endfor; ?>
     </ul>
     <?php 
         $jenis = array(
                         array(
                                 'Gaji dan tunjangan',
                                 'Penghasilan dari profesi / keahlian',
                                 'Honorarium',
                                 'Tantiem, bonus, jasa produksi, THR',
                              ),
                         array(
                                 'Hasil investasi dalam surat berharga',
                                 'Hasil usaha',
                                 'Bunga tabungan, bunga deposito, dan lainnya',
                                 'Hasil sewa',
                                 'Penjualan atau pelepasan harta',
                              ),
                         array(
                                 'Perolehan hutang',
                                 'Penerimaan warisan',
                                 'Penerimaan hibah / hadiah',
                              )
                       );
          $where     = "WHERE IS_ACTIVE = '1' AND ID_LHKPN = '$id_lhkpn'";
          $getPeka   = $this->mlhkpn->getValue('T_LHKPN_PENERIMAAN_KAS', $where);
          $PN        = @json_decode($getPeka[0]->NILAI_PENERIMAAN_KAS_PN);
          $PA        = @json_decode($getPeka[0]->NILAI_PENERIMAAN_KAS_PASANGAN);
          $lain      = @json_decode($getPeka[0]->LAINNYA);

     ?>
     <div class="tab-content">
         <?php 
             for ($i=0; $i < count($jenis); $i++) :
         ?>
             <div class="tab-pane <?=($i == 0) ? 'active' : '';?>" id="tabs<?=$label[$i]?>">
             <?php 
                 $getData = $this->mlhkpn->getInpekas($id_lhkpn, 'T_LHKPN_PENERIMAAN_KAS', 'M_JENIS_PENERIMAAN_KAS', 'M_GOLONGAN_PENERIMAAN_KAS', $golongan[$i], '1');
             ?>
                 <table class="table table-bordered table-hover">
                     <thead class="table-header">
                         <tr>
                             <th width="40px">NO</th>
                             <th>JENIS PENERIMAAN</th>
                             <?php echo ($i == 0) ? '<th>PENYELENGGARA NEGARA</th>' : '<th>TOTAL NILAI PENERIMAAN KAS</th>'; ?>
                             <?php echo ($i == 0) ? '<th>PASANGAN</th>' : ''; ?>
                         </tr>
                     </thead>
                     <tbody class="tabs<?=$label[$i]?>">
                         <?php 
                             for ($j=0; $j < count($jenis[$i]); $j++) : 
                             $PA_val = 'PA'.$j;
                         ?>
                         <tr>
                             <td><center><?=($j+1)?></center></td>
                             <td><?=$jenis[$i][$j] ?></td>
                             <td align="right">
                                 <?php $code = $label[$i].$j; ?>
                               Rp. <?=(@$PN->$label[$i]->$code != '') ? @$PN->$label[$i]->$code : ' -'?>
                             </td>
                             <?php if ($i == 0) : ?>
                             <td align="right">
                               Rp. <?=(@$PA->$PA_val != '') ? @$PA->$PA_val : ' -'?>
                             </td>
                             <?php 
                               endif;
                             ?>
                         </tr>
                         <?php
                             endfor;
                             $label_text = $label[$i];
                             $idtext2[$label_text] = ($j+1);
                             $PA_val = 'PA'.$j;
                             $code = $label[$i].$j;
                         ?>
                         <tr>
                             <td><center><?=($j+1)?></center></td>
                             <td>PENERIMAAN dari pekerjaan lainnya : <?=(@$lain->$label[$i] != '') ? @$lain->$label[$i] : ' -'?>
                             <td align="right">Rp. <?=(@$PN->$label[$i]->$code != '') ? @$PN->$label[$i]->$code : ' -'?></td>
                             <?php if ($i == 0) : ?><td align="right">Rp. <?=(@$PA->$PA_val != '') ? @$PA->$PA_val : ' -'?></td><?php endif; ?>
                         </tr>
                     </tbody>
                 </table>
             </div>
         <?php 
             endfor;
             $idtext2 = json_encode($idtext2);
             $cnt = count($PN);
             if($cnt != 0){
                $total         = [];
                $totalALL      = 0;
                foreach ($PN as $key => $value) {
                    $total[$key] = 0;
                    foreach ($value as $hasil => $nilai) {
                        $total[$key] = $total[$key] + str_replace(".","",$nilai);
                    }
                    if ($key == 'A') {
                        foreach ($PA as $hasilPA => $nilaiPA) {
                            $total[$key] = $total[$key] + str_replace(".","",$nilaiPA);
                        }
                    }
                    $totalALL = $totalALL + $total[$key];
                }
             }else{
                $total    = [];
                $totalALL = 0;
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
        <button type="button" class="btn btn-sm btn-warning btnPreviousPenerimaan"><i class="fa fa-backward"></i> Sebelumnya</button>
        <button type="button" class="btn btn-sm btn-warning btnNextPenerimaan">Selanjutnya <i class="fa fa-forward"></i></button>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.btnNextPenerimaan').click(function(e){
            e.preventDefault();
            if($('#wrapperPenerimaan > .nav-tabs > .active').next('li').find('a').attr('href')==undefined){
                $('.nav-tabs > .active').next('li').find('a').trigger('click');
                $('#wrapperPengeluaran > .nav-tabs').find('a[href="#tabs3A"]').trigger('click');
            }else{
                $('#wrapperPenerimaan > .nav-tabs > .active').next('li').find('a').trigger('click');
            }
        });
        $('.btnPreviousPenerimaan').click(function(e){
            e.preventDefault();
            if($('#wrapperPenerimaan > .nav-tabs > .active').prev('li').find('a').attr('href')==undefined){
                $('.nav-tabs > .active').prev('li').find('a').trigger('click');
                $('#harta > .nav-tabs').find('a[href="#hutang"]').trigger('click');
            }else{
                $('#wrapperPenerimaan > .nav-tabs > .active').prev('li').find('a').trigger('click');
            }
        });
    });
</script>