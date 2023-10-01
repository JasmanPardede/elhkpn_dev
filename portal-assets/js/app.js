$(document).ready(function(){
	window.setTimeout("waktu()",1000);  
    Tanggal();
    var url = window.location;
    // Will only work if string in href matches with location
    $('ul.first a[href="'+ url +'"]').addClass('active');
    // Will also work for relative and absolute hrefs
    $('ul.first a').filter(function() {
        return this.href == url;
    }).addClass('active');
   
});

function waktu() {   
    var tanggal = new Date();  
    setTimeout("waktu()",1000);  
    var detik = tanggal.getSeconds();
    var second;
    if(detik<10){
         second = "0"+detik;
    }else{
        second = detik;
     }
    var menit = tanggal.getMinutes();
    var mnt;
    if(menit<10){
        mnt = "0"+menit;
    }else{
         mnt = menit;
    }
    var jam = tanggal.getHours();
    var jm;
    if(jam<10){
       jm = "0"+jam;
    }else{
        jm = jam;
    }
    document.getElementById("output").innerHTML = jm+":"+mnt+":"+second;
}

function Tanggal(){
    var date = new Date();
    var tanggal = date.getDate();
    var hari = date.getDay();
    var bulan = date.getMonth();
    var tahun = date.getFullYear();
	
    var arr_hari = new Array();
    arr_hari[0] = "Minggu";
    arr_hari[1] = "Senin";
    arr_hari[2] = "Selasa";
    arr_hari[3] = "Rabu";
    arr_hari[4] = "Kamis";
    arr_hari[5] = "Jum'at";
    arr_hari[6] = "Sabtu";
           
    var arr_bulan = new Array();
    arr_bulan[0] = "Januari";
    arr_bulan[1] = "Februari";
    arr_bulan[2] = "Maret";
    arr_bulan[3] = "April";
    arr_bulan[4] = "Mei";
    arr_bulan[5] = "Juni";
    arr_bulan[6] = "Juli";
    arr_bulan[7] = "Agustus";
    arr_bulan[8] = "September";
    arr_bulan[9] = "Oktober";
    arr_bulan[10] = "November";
    arr_bulan[11] = "Desember";

    var sekarang = arr_hari[hari]+" , "+tanggal+" "+arr_bulan[bulan]+" "+tahun;
    document.getElementById("tanggal").innerHTML = sekarang;
}