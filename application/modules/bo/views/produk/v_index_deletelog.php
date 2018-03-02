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
                             <a class="btn btn-primary btn-sm" href="<?php echo base_url('bo/produk/export/csv/0')?>">Export CSV</a>
                             <a class="btn btn-info btn-sm" href="<?php echo base_url('bo/produk/export/excel/0')?>">Export Excel</a>
                             <a class="btn btn-warning btn-sm" href="<?php echo base_url('bo/produk/export/pdf/0')?>" target="_blank">Export PDF</a>                        
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                            <?php $this->load->view('bo/v_date_range')?>
                                <table class="table table-striped table-bordered table-hover ss-tables" value="<?php echo base_url('bo/produk/get_records/0')?>">
                                    <thead>
                                        <tr><th>#</th>
                                        	<th>Sub Number</th>   
                                        	<th>Description</th>  
                                        	<th>Asset Class</th>       
                                        	<th>Jenis</th>
                                        	<th>Company Code</th>
                                        	<th>Bisnis Area</th>
                                        	<th>Sub Location</th>                            	                                      	     
                                        	<th>Date Add</th>
                                        	<th>Add By</th>  
                                        	<th>Status</th>                                	                                        	                                     	
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
<script type="text/javascript">	
	$(document).ready(function(){	
		/*digunakan jika hanya singgle table dalam 1 form*/	
		primari_table('.ss-tables');		
	});
</script>  