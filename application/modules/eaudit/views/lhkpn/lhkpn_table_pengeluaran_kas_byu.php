<div class="box-header with-border portlet-header">
   <h3 class="box-title">VI. INFORMASI PENGELUARAN KAS</h3>
</div><!-- /.box-header -->
<div class="box-body" id="wrapperPengeluaran">

   <ul class="nav nav-tabs">
   <!-- Untuk Semua Tab.. pastikan a href=”#nama_id” sama dengan nama id di “Tap Pane” dibawah-->
     <?php
      $alfamenu  = 'A';
      $tabdefult = '';
      foreach ($golongan as $key):
         if($alfamenu == 'A')
         {
            $tabdefult = 'active';
         }
         else
         {
            $tabdefult = '';
         }
     ?>
     <li class="<?=$tabdefult?>"><a href="#tab<?=$alfamenu?>" data-toggle="tab"><?=$alfamenu?></a></li> <!-- Untuk Tab pertama berikan li class=”active” agar pertama kali halaman di load tab langsung active-->
     <?php
      $alfamenu++;
      endforeach;
     ?>
   </ul>
   <div class="tab-content">

      <?php
         $alfa = 'A'; 
         $idtext = [];
         $tot  = 0;
         $tabdefult = '';
         // $tot  = 0;
         foreach ($golongan as $key): 
            $alfadalam = $alfa;
            if($alfadalam == 'A')
            {
               $tabdefult = 'active';
            }
            else
            {
               $tabdefult = '';
            }
      ?>
      <div class="tab-pane <?=$tabdefult?>" id="tab<?=$alfadalam?>">
         <table class="table table-bordered table-hover">
            <thead class="table-header">
               <tr>
                  <th>NO</th>
                  <th>JENIS PENGELUARAN</th>
                  <th>TOTAL NILAI PENGELUARAN KAS</th>
               </tr>
            </thead>
            <tbody>
                  <tr>
                     <td colspan="3">
                        <h3><b><?= $alfa++.". ".@$key->NAMA_GOLONGAN; ?></b></h3>
                     </td>
                  </tr>

                  <?php 
                     $getData = $this->mlhkpn->getInpekas($ID_LHKPN,'T_LHKPN_PENGELUARAN_KAS','M_JENIS_PENGELUARAN_KAS','M_GOLONGAN_PENGELUARAN_KAS',$key->NAMA_GOLONGAN,'2');
                     $no  = '0';
                     $sub = '0';
                     foreach ($getData as $yek) :
                        $where = "WHERE IS_ACTIVE = '1' AND ID_JENIS_PENGELUARAN_KAS = '$yek->ID_JENIS_PENGELUARAN_KAS' ";
                        $getPeka = $this->mlhkpn->getValue('T_LHKPN_PENGELUARAN_KAS', $where);
                        if($yek->ID_JENIS_PENGELUARAN_KAS === @$getPeka[0]->ID_JENIS_PENGELUARAN_KAS){
                           $nil = @$getPeka[0]->NILAI_PENGELUARAN_KAS;
                        }else{
                           $nil = "";
                        }
                  ?>
                     <tr>
                        <td><?= 
                                 @$no++;
                                 $idtext[$alfadalam] = $no; 
                        ?></td>
                        <td><?= @$yek->NAMA; ?></td>
                        <td align="right">Rp. <input type="text" id="text<?=$alfadalam.$no?>" onchange="Calculate();" class="int PENGELUARAN_pekerjaan" value="<?= @$nil; ?>"></td>
                     </tr>
                  <?php endforeach; ?>
                  <tr>
                     <td><?= @$no++; ?></td>
                     <td>PENGELUARAN dari pekerjaan lainnya <input type="text" class="">.

                        <button type="button" class="btn btn-sm btn-default add-pekerjaan" href="index.php/efill/lhkpn/add_PENGELUARAN_pekerjaan">Tambah</button>
                     </td>
                     <td align="right">Rp. <input type="text" class="int PENGELUARAN_pekerjaan"></td>
                  </tr>
                  <tr>
                     <td colspan="2" align="right"><b>Sub Total</b></td>
                     <td align="right"><b>Rp. <span id="subtext<?=$alfadalam?>"><?= @$sub; ?></span></b></td>
                  </tr>
               <tr>
                  <td colspan="2" align="right"><b>TOTAL PENGELUARAN  (A + B + C)</b></td>
                  <td align="right"><b>Rp. <span class="total_semua_penerimaan"><?= @$tot; ?></span></b></td>
               </tr>
            </tbody>
         </table>
      </div>
      <?php 
            $tot += $sub;
            endforeach; 
            $idtext = json_encode($idtext);
      ?>
   </div>

</div><!-- /.box-body -->
<div class="box-footer">
<!--                   <div class="pull-right">
<button class="btn btn-default"><i class="fa fa-pencil"></i> Draft</button>
<button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
</div>
<button class="btn btn-default"><i class="fa fa-trash"></i> Discard</button> -->
</div><!-- /.box-footer -->
</div><!-- /. box -->
<div class="box-footer">
<!--                   <div class="pull-right">
<button class="btn btn-default"><i class="fa fa-pencil"></i> Draft</button>
<button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
</div>
<button class="btn btn-default"><i class="fa fa-trash"></i> Discard</button> -->
</div><!-- /.box-footer -->


<script type="text/javascript">
   $(document).ready(function () {
      $("#wrapperPengeluaran .add-umum").click(function () {
         url = $(this).attr('href');
         $.post(url, function (html) {
            OpenModalBox('Tambah PENGELUARAN Dari Pekerjaan Lainnya', html, '', 'large');
         });
         return false;
      });
      $("#wrapperPengeluaran .add-harta").click(function () {
         url = $(this).attr('href');
         $.post(url, function (html) {
            OpenModalBox('Tambah PENGELUARAN Dari Usaha Dan Kekayaan Lainnya', html, '', 'large');
         });
         return false;
      });
      $("#wrapperPengeluaran .add-lainnya").click(function () {
         url = $(this).attr('href');
         $.post(url, function (html) {
            OpenModalBox('Tambah PENGELUARAN Lainnya', html, '', 'large');
         });
         return false;
      });
      $(".int").inputmask("integer", {
         groupSeparator : '.',
         'autoGroup': true,
         'removeMaskOnSubmit': false,
         'digits': 0
      });
      // $(".pengeluaran_umum").change(function(){
      //    Calculate(".pengeluaran_umum", "#total_pengeluaran_umum");
      // });

      // $(".pengeluaran_harta").change(function(){
      //    Calculate(".pengeluaran_harta", "#total_pengeluaran_harta");
      // });

      // $(".pengeluaran_lainnya").change(function(){
      //    Calculate(".pengeluaran_lainnya", "#total_pengeluaran_lainnya");
      // });
      Calculate()
   });

   // function total(inpObj){
   //    var tot = 0;
   //    $(inpObj).each(function(){
   //       var val = parseInt($(this).val().replace(/\./g, ''));
   //       if(isNaN(val)){
   //          val = 0;
   //       }
   //       tot += val;
   //    });
   //    return tot;
   // }

   // function Calculate(inpObj,totObj){
   //    var tot = 0;
   //    $(inpObj).each(function(){
   //       var val = parseInt($(this).val().replace(/\./g, ''));
   //       if(isNaN(val)){
   //          val = 0;
   //       }
   //       tot += val;
   //    });
   //    $(totObj).html(formatAngka(tot));
   //    a = total(".pengeluaran_umum");
   //    b = total(".pengeluaran_harta");
   //    c = total(".pengeluaran_lainnya");
   //    $("#semua_total_pengeluaran").html(formatAngka(a+b+c));
   // }
   // 
   function Calculate(){
      var tot = 0;
      var jumlah = '<?=$no?>';
      var idtext = '<?=$idtext?>';
      var tot    = 0;
      var hasil  = 0;
      $.each(JSON.parse(idtext), function(index, value){
         subtot = 0;
         for (var i = 1 ; i <= value; i++) {
            if ($('#text'+index+i).val().replace(/\./g, '') > 0)
               {
                  nilai = parseInt($('#text'+index+i).val().replace(/\./g, ''));
               }
            else
               {
                  nilai = 0;
               };
            subtot = parseInt(subtot)+nilai;
         };
         hasil = hasil+subtot;
         $('#subtext'+index).html(formatAngka(subtot));
         $(".total_semua_penerimaan").html(formatAngka(hasil));
      });
   }

   function formatAngka(angka) {
      if (typeof(angka) != 'string') angka = angka.toString();
      var reg = new RegExp('([0-9]+)([0-9]{3})');
      while(reg.test(angka)) angka = angka.replace(reg, '$1.$2');
      return angka;
   }
</script>