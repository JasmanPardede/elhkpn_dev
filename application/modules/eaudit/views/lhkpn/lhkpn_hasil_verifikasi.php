<div class="box-header with-border portlet-header title-alat">
    <h5 class="">"Hasil Verifikasi"</h5>
</div>
<div class="box-body" id="wrapperFinal">
    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
    <thead>
        <tr>
            <th width="250">Item</th>
            <th  width="150">Hasil Verifikasi</th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>

            <tr id="datapribadiFinal">
                <td><span class="headerVerFinal">Data Pribadi</span></td>
                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($hasilVerifikasi->VAL->DATAPRIBADI)) ? ((@$hasilVerifikasi->VAL->DATAPRIBADI == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$hasilVerifikasi->VAL->DATAPRIBADI == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                <td><span class="msgVerFinalTXT"><?php echo @$hasilVerifikasi->MSG->DATAPRIBADI ?></span>
                    <input type="hidden" name="VER[VAL][DATAPRIBADI]" class="valVerFinal" value="<?php echo @$hasilVerifikasi->VAL->DATAPRIBADI; ?>">
                    <textarea style="display: none;" name="VER[MSG][DATAPRIBADI]" class="msgVerFinal"><?php echo @$hasilVerifikasi->MSG->DATAPRIBADI ?></textarea>
                </td>
            </tr>

            <tr id="jabatanFinal">
                <td><span class="headerVerFinal">Jabatan</span></td>
                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($hasilVerifikasi->VAL->JABATAN)) ? ((@$hasilVerifikasi->VAL->JABATAN == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$hasilVerifikasi->VAL->JABATAN == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                <td><span class="msgVerFinalTXT"><?php echo @$hasilVerifikasi->MSG->JABATAN ?></span>
                    <input type="hidden" name="VER[VAL][JABATAN]" class="valVerFinal" value="<?php echo @$hasilVerifikasi->VAL->JABATAN; ?>">
                    <textarea style="display: none;" name="VER[MSG][JABATAN]" class="msgVerFinal"><?php echo @$hasilVerifikasi->MSG->JABATAN ?></textarea>
                </td>
            </tr>

            <tr id="keluargaFinal">
                <td><span class="headerVerFinal">Keluarga</span></td>
                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($hasilVerifikasi->VAL->KELUARGA)) ? ((@$hasilVerifikasi->VAL->KELUARGA == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$hasilVerifikasi->VAL->KELUARGA == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                <td><span class="msgVerFinalTXT"><?php echo @$hasilVerifikasi->MSG->KELUARGA ?></span>
                    <input type="hidden" name="VER[VAL][KELUARGA]" class="valVerFinal" value="<?php echo @$hasilVerifikasi->VAL->KELUARGA; ?>">
                    <textarea style="display: none;" name="VER[MSG][KELUARGA]" class="msgVerFinal"><?php echo @$hasilVerifikasi->MSG->KELUARGA ?></textarea></td>
            </tr>

            <tr id="hartatidakbergerakFinal">
                <td><span class="headerVerFinal">Tanah / Bangunan</span></td>
                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($hasilVerifikasi->VAL->HARTATIDAKBERGERAK)) ? ((@$hasilVerifikasi->VAL->HARTATIDAKBERGERAK == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$hasilVerifikasi->VAL->HARTATIDAKBERGERAK == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                <td><span class="msgVerFinalTXT"><?php echo @$hasilVerifikasi->MSG->HARTATIDAKBERGERAK ?></span>
                    <input type="hidden" name="VER[VAL][HARTATIDAKBERGERAK]" class="valVerFinal" value="<?php echo @$hasilVerifikasi->VAL->HARTATIDAKBERGERAK; ?>">
                <textarea style="display: none;" name="VER[MSG][HARTATIDAKBERGERAK]" class="msgVerFinal"><?php echo @$hasilVerifikasi->MSG->HARTATIDAKBERGERAK ?></textarea></td>
            </tr>

            <tr id="hartabergerakFinal">
                <td><span class="headerVerFinal">Alat Transportasi / Mesin</span></td>
                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($hasilVerifikasi->VAL->HARTABERGERAK)) ? ((@$hasilVerifikasi->VAL->HARTABERGERAK == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$hasilVerifikasi->VAL->HARTABERGERAK == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                <td><span class="msgVerFinalTXT"><?php echo @$hasilVerifikasi->MSG->HARTABERGERAK ?></span>
                    <input type="hidden" name="VER[VAL][HARTABERGERAK]" class="valVerFinal" value="<?php echo @$hasilVerifikasi->VAL->HARTABERGERAK; ?>">
                    <textarea style="display: none;" name="VER[MSG][HARTABERGERAK]" class="msgVerFinal"><?php echo @$hasilVerifikasi->MSG->HARTABERGERAK ?></textarea>
                </td>
            </tr>

            <tr id="hartabergerakperabotFinal">
                <td><span class="headerVerFinal">Bergerak Lain</span></td>
                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($hasilVerifikasi->VAL->HARTABERGERAK2)) ? ((@$hasilVerifikasi->VAL->HARTABERGERAK2 == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$hasilVerifikasi->VAL->HARTABERGERAK2 == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                <td><span class="msgVerFinalTXT"><?php echo @$hasilVerifikasi->MSG->HARTABERGERAK2 ?></span>
                    <input type="hidden" name="VER[VAL][HARTABERGERAK2]" class="valVerFinal" value="<?php echo @$hasilVerifikasi->VAL->HARTABERGERAK2; ?>">
                    <textarea style="display: none;" name="VER[MSG][HARTABERGERAK2]" class="msgVerFinal"><?php echo @$hasilVerifikasi->MSG->HARTABERGERAK2 ?></textarea>
                </td>
            </tr>

            <tr id="suratberhargaFinal">
                <td><span class="headerVerFinal">Surat Berharga</span></td>
                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($hasilVerifikasi->VAL->SURATBERHARGA)) ? ((@$hasilVerifikasi->VAL->SURATBERHARGA == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$hasilVerifikasi->VAL->SURATBERHARGA == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                <td><span class="msgVerFinalTXT"><?php echo @$hasilVerifikasi->MSG->SURATBERHARGA ?></span>
                    <input type="hidden" name="VER[VAL][SURATBERHARGA]" class="valVerFinal" value="<?php echo @$hasilVerifikasi->VAL->SURATBERHARGA; ?>">
                    <textarea style="display: none;" name="VER[MSG][SURATBERHARGA]" class="msgVerFinal"><?php echo @$hasilVerifikasi->MSG->SURATBERHARGA ?></textarea>
                </td>
            </tr>

            <tr id="kasFinal">
                <td><span class="headerVerFinal">Kas / Setara Kas</span></td>
                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($hasilVerifikasi->VAL->KAS)) ? ((@$hasilVerifikasi->VAL->KAS == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$hasilVerifikasi->VAL->KAS == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                <td><span class="msgVerFinalTXT"><?php echo @$hasilVerifikasi->MSG->KAS ?></span>
                    <input type="hidden" name="VER[VAL][KAS]" class="valVerFinal" value="<?php echo @$hasilVerifikasi->VAL->KAS; ?>">
                    <textarea style="display: none;" name="VER[MSG][KAS]" class="msgVerFinal"><?php echo @$hasilVerifikasi->MSG->KAS ?></textarea>
                </td>
            </tr>

            <tr id="hartalainnyaFinal">
                <td><span class="headerVerFinal">Harta Lainnya</span></td>
                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($hasilVerifikasi->VAL->HARTALAINNYA)) ? ((@$hasilVerifikasi->VAL->HARTALAINNYA == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$hasilVerifikasi->VAL->HARTALAINNYA == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                <td><span class="msgVerFinalTXT"><?php echo @$hasilVerifikasi->MSG->HARTALAINNYA ?></span>
                    <input type="hidden" name="VER[VAL][HARTALAINNYA]" class="valVerFinal" value="<?php echo @$hasilVerifikasi->VAL->HARTALAINNYA; ?>">
                    <textarea style="display: none;" name="VER[MSG][HARTALAINNYA]" class="msgVerFinal"><?php echo @$hasilVerifikasi->MSG->HARTALAINNYA ?></textarea>
                </td>
            </tr>

            <tr id="hutangFinal">
                <td><span class="headerVerFinal">Hutang</span></td>
                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($hasilVerifikasi->VAL->HUTANG)) ? ((@$hasilVerifikasi->VAL->HUTANG == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$hasilVerifikasi->VAL->HUTANG == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                <td><span class="msgVerFinalTXT"><?php echo @$hasilVerifikasi->MSG->HUTANG ?></span>
                    <input type="hidden" name="VER[VAL][HUTANG]" class="valVerFinal" value="<?php echo @$hasilVerifikasi->VAL->HUTANG; ?>">
                    <textarea style="display: none;" name="VER[MSG][HUTANG]" class="msgVerFinal"><?php echo @$hasilVerifikasi->MSG->HUTANG ?></textarea>
                </td>
            </tr> 

            <tr id="penerimaankasFinal">
                <td><span class="headerVerFinal">Penerimaan Kas</span></td>
                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($hasilVerifikasi->VAL->PENERIMAANKAS)) ? ((@$hasilVerifikasi->VAL->PENERIMAANKAS == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$hasilVerifikasi->VAL->PENERIMAANKAS == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                <td><span class="msgVerFinalTXT"><?php echo @$hasilVerifikasi->MSG->PENERIMAANKAS ?></span>
                    <input type="hidden" name="VER[VAL][PENERIMAANKAS]" class="valVerFinal" value="<?php echo @$hasilVerifikasi->VAL->PENERIMAANKAS; ?>">
                    <textarea style="display: none;" name="VER[MSG][PENERIMAANKAS]" class="msgVerFinal"><?php echo @$hasilVerifikasi->MSG->PENERIMAANKAS ?></textarea>
                </td>
            </tr>

            <tr id="pengeluarankasFinal">
                <td><span class="headerVerFinal">Pengeluaran Kas</span></td>
                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($hasilVerifikasi->VAL->PENGELUARANKAS)) ? ((@$hasilVerifikasi->VAL->PENGELUARANKAS == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$hasilVerifikasi->VAL->PENGELUARANKAS == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                <td><span class="msgVerFinalTXT"><?php echo @$hasilVerifikasi->MSG->PENGELUARANKAS ?></span>
                    <input type="hidden" name="VER[VAL][PENGELUARANKAS]" class="valVerFinal" value="<?php echo @$hasilVerifikasi->VAL->PENGELUARANKAS; ?>">
                    <textarea style="display: none;" name="VER[MSG][PENGELUARANKAS]" class="msgVerFinal"><?php echo @$hasilVerifikasi->MSG->PENGELUARANKAS ?></textarea>
                </td>
            </tr>

<!--                                         <tr id="lampiranFinal">
                <td><span class="headerVerFinal">Lampiran</span></td>
                <td align="center"><span class="checkboxVerFinal"></span></td>
                <td><span class="msgVerFinalTXT"></span>
                    <input type="hidden" name="VER[VAL][LAMPIRAN]" class="valVerFinal" value="">
                    <textarea style="display: none;" name="VER[MSG][LAMPIRAN]" class="msgVerFinal"></textarea>
                </td>
            </tr> -->

            <tr id="pelepasanhartaFinal">
                <td><span class="headerVerFinal">Pelepasan Harta</span></td>
                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($hasilVerifikasi->VAL->PELEPASANHARTA)) ? ((@$hasilVerifikasi->VAL->PELEPASANHARTA == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$hasilVerifikasi->VAL->PELEPASANHARTA == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                <td><span class="msgVerFinalTXT"><?php echo @$hasilVerifikasi->MSG->PELEPASANHARTA ?></span>
                    <input type="hidden" name="VER[VAL][PELEPASANHARTA]" class="valVerFinal" value="<?php echo @$hasilVerifikasi->VAL->PELEPASANHARTA; ?>">
                    <textarea style="display: none;" name="VER[MSG][PELEPASANHARTA]" class="msgVerFinal"><?php echo @$hasilVerifikasi->MSG->PELEPASANHARTA ?></textarea>
                </td>
            </tr>

            <tr id="penerimaanhibahFinal">
                <td><span class="headerVerFinal">Penerimaan Hibah</span></td>
                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($hasilVerifikasi->VAL->PENERIMAANHIBAH)) ? ((@$hasilVerifikasi->VAL->PENERIMAANHIBAH == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$hasilVerifikasi->VAL->PENERIMAANHIBAH == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                <td><span class="msgVerFinalTXT"><?php echo @$hasilVerifikasi->MSG->PENERIMAANHIBAH ?></span>
                    <input type="hidden" name="VER[VAL][PENERIMAANHIBAH]" class="valVerFinal" value="<?php echo @$hasilVerifikasi->VAL->PENERIMAANHIBAH; ?>">
                    <textarea style="display: none;" name="VER[MSG][PENERIMAANHIBAH]" class="msgVerFinal"><?php echo @$hasilVerifikasi->MSG->PENERIMAANHIBAH ?></textarea>
                </td>
            </tr>

            <tr id="penerimaanfasilitasFinal">
                <td><span class="headerVerFinal">Penerimaan Fasilitas</span></td>
                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($hasilVerifikasi->VAL->PENERIMAANFASILITAS)) ? ((@$hasilVerifikasi->VAL->PENERIMAANFASILITAS == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$hasilVerifikasi->VAL->PENERIMAANFASILITAS == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                <td><span class="msgVerFinalTXT"><?php echo @$hasilVerifikasi->MSG->PENERIMAANFASILITAS ?></span>
                    <input type="hidden" name="VER[VAL][PENERIMAANFASILITAS]" class="valVerFinal" value="<?php echo @$hasilVerifikasi->VAL->PENERIMAANFASILITAS; ?>">
                    <textarea style="display: none;" name="VER[MSG][PENERIMAANFASILITAS]" class="msgVerFinal"><?php echo @$hasilVerifikasi->MSG->PENERIMAANFASILITAS ?></textarea>
                </td>
            </tr>

            <!-- <tr id="suratkuasamengumumkanFinal">
                <td><span class="headerVerFinal">Suratkuasa Mengumumkan</span></td>
                <td align="center"><span class="checkboxVerFinal"></span></td>
                <td><span class="msgVerFinalTXT"></span>
                    <input type="hidden" name="VER[VAL][SURATKUASAMENGUMUMKAN]" class="valVerFinal" value="">
                    <textarea style="display: none;" name="VER[MSG][SURATKUASAMENGUMUMKAN]" class="msgVerFinal"></textarea>
                </td>
            </tr> -->

            <tr id="dokumenpendukungFinal">
                <td><span class="headerVerFinal">Dokumen Pendukung</span></td>
                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($hasilVerifikasi->VAL->DOKUMENPENDUKUNG)) ? ((@$hasilVerifikasi->VAL->DOKUMENPENDUKUNG == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$hasilVerifikasi->VAL->DOKUMENPENDUKUNG == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                <td><span class="msgVerFinalTXT"><?php echo @$hasilVerifikasi->MSG->DOKUMENPENDUKUNG ?></span>
                    <input type="hidden" name="VER[VAL][DOKUMENPENDUKUNG]" class="valVerFinal" value="<?php echo @$hasilVerifikasi->VAL->DOKUMENPENDUKUNG; ?>">
                    <textarea style="display: none;" name="VER[MSG][DOKUMENPENDUKUNG]" class="msgVerFinal"><?php echo @$hasilVerifikasi->MSG->DOKUMENPENDUKUNG ?></textarea>
                </td>
            </tr>
    </tbody>
</table>
</div>