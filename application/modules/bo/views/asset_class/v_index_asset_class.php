<div id="page-wrapper">
       <div class="row">
          <div class="col-lg-12">
              <h2 class="page-header"><?php echo $page_title?></h2> 
              <?php $this->load->view('bo/v_alert_notif');?>             
        </div>
        <div class="row">
                <div class="col-lg-12">                    
                    <div class="panel panel-info">
                        <div class="panel-heading">
                             <a class="btn btn-success btn-sm" href="<?php echo base_url('bo/'.$class.'/form')?>" >Tambah</a>
                             
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                           
                                <table class="table table-striped table-bordered table-hover ss-tables">
                                    <thead>
                                        <tr><th>#</th>
                                        	<th>Asset Class</th>   
                                        	<th>Nama Class</th>                                          	                             	                                        	                                     	
                                        	<th class="no-sort">Actions</th>
                                        </tr>                                      	              	
                                    </thead>                                    
                                </table>
                            </div>                            
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
              
     </div>
</div>    
<input type="hidden" class="url-datatable" value="<?php echo base_url('bo/'.$class.'/get_records')?>">
<script type="text/javascript">	
	$(document).ready(function(){	
		/*digunakan jika hanya singgle table dalam 1 form*/	
		primari_table('.ss-tables');		
	});
</script>  