/*  var ziarray=new Array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu")
    var lunaarray=new Array ("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember")

    function datacom() {
      var mydate=new Date()
      var year=mydate.getYear()
      if (year < 1000)
        year+=1900
      var day=mydate.getDay()
      var month=mydate.getMonth()
      var daym=mydate.getDate()
      if (daym<10)
        daym="0"+daym
      var hours=mydate.getHours()
      var minutes=mydate.getMinutes()
      var seconds=mydate.getSeconds()
      var dn="AM"
      if (hours>=12)
        dn="PM"
      if (hours>12) {
        hours=hours-12
      }
      if (hours==0)
        hours=12
      if (minutes<=9)
        minutes="0"+minutes
      if (seconds<=9)
        seconds="0"+seconds
    
     var cdate="<a href='#' class='navbar-brand'><small>"+ziarray[day]+", "+daym+" "+lunaarray[month]+" "+year+"</small> <br/><font style='font-size:30px'>"+hours+":"+minutes+":"+seconds+" "+dn
        +"</font>&nbsp; &nbsp; &nbsp; </a>"

 
      document.getElementById("data_jam").innerHTML=cdate
    }

    function arata() {
      if (document.getElementById("data_jam"))
        setInterval("datacom()",1000)
    }
    window.onload=arata;*/
    $(document).ready(function(){
      window.setTimeout("waktu()",1000);  
      Tanggal();     
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