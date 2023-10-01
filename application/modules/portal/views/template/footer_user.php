 <section id="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p>         
                        Gedung Merah Putih KPK - Jl. Kuningan Persada Kav. 4, Setiabudi, Jakarta 12950, Call Center:198, Fax:(021) 5292 1230, Email: <b>elhkpn@kpk.go.id</b>
                    </p>
                </div>
                <div class="col-lg-12">
                    <ul>
                        <li><a href="#"><img src="<?php echo base_url();?>portal-assets/img/integrito.png"/></a></li>
                        <li><a href="#"><img src="<?php echo base_url();?>portal-assets/img/acch.png"/></a></li>
                        <li><a href="#"><img src="<?php echo base_url();?>portal-assets/img/integritas.png"/></a></li>
                        <li><a href="#"><img src="<?php echo base_url();?>portal-assets/img/aclc.png"/></a></li>
                        <li><a href="#"><img src="<?php echo base_url();?>portal-assets/img/kanal_tv.png"/></a></li>
                        <li><a href="#"><img src="<?php echo base_url();?>portal-assets/img/wistlebower.png"/></a></li>
                    </ul>
                </div>
            </div>
            <div class="row copyright">
                <div class="col-lg-12">
                     <strong>Copyright 2017 <br> <?php echo $this->config->item('version') ?></strong>
                </div>
            </div>
        </div>
    </section>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-29889353-15"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-29889353-15');
</script>
<?php $this->load->view('template/footer'); ?>