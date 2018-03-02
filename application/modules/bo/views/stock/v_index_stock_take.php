<div id="page-wrapper">
       <div class="row">
          <div class="col-lg-12">
              <h2 class="page-header"><?php echo $page_title?></h2> 
              <?php $this->load->view('bo/v_alert_notif');?>             
        </div>
        <div class="row">
                <div class="col-lg-12">                                     
                   <?php $this->load->view('bo/stock/v_complete_opname')?>
                </div>                 
        </div>
         <div class="row">                
                 <div class="col-lg-12">                                     
                    <?php $this->load->view('bo/stock/v_outstanding_opname')?>
                </div>
        </div>  
         <div class="row">                
                 <div class="col-lg-12">                                     
                    <?php $this->load->view('bo/stock/v_notfound_opname')?>
                </div>
        </div>      
     </div>
</div>    
