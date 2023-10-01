<section id="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
                    <h2 class="section-heading">FAQ</h2>
                    <div class="accordion" id="accordion2">
                    <?php if(isset($faq)): ?>    
                        <?php foreach($faq as $f): ?>
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                     <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?php echo $f->ID_FAQ;?>">
                                       <strong><?php echo $f->PERTANYAAN; ?></strong>
                                     </a>
                                </div>
                                <div id="collapse<?php echo $f->ID_FAQ;?>" class="accordion-body collapse">
                                  <div class="accordion-inner">
                                    <?php echo $f->JAWABAN; ?>
                                  </div>
                                </div>
                            </div>
                        <?php EndForeach;?>    
                    <?php EndIf; ?>    
                    </div>  
                </div>
		</div>
	</div>
</section>