<div class="panel panel-info">
                        <div class="panel-heading">        
                                           
                         	<span class="pull-right">
                         	 <a class="btn btn-primary btn-sm" href="<?php echo base_url('bo/'.$class.'/export_notfound/csv')?>">Export CSV</a>
                             <a class="btn btn-info btn-sm" href="<?php echo base_url('bo/'.$class.'/export_notfound/excel')?>">Export Excel</a>
                             <a class="btn btn-warning btn-sm" href="<?php echo base_url('bo/'.$class.'/export_notfound/pdf')?>" target="_blank">Export PDF</a>
                         	</span>
                            <span class="center"><h4>Not Found Opname</h4></span> 
                               	
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                             <?php $this->load->view('bo/v_date_range')?>
                                <table class="table table-striped table-bordered table-hover third-tables" value="<?php echo base_url('bo/'.$class.'/get_notfound')?>">
                                    <thead>
                                        <tr><th>#</th>
                                        	<th>Sub Number</th>                                          	                                       	                           	                                      	                                           	
                                        	<th>Scan By</th> 
                                        	<th>Company By</th>
                                        	<th>Scan Date</th>  
                                        	<th>Status</th>                                     	                              	                                        	                                     	                                      	
                                        </tr>                                      	              	
                                    </thead>                                    
                                </table>
                            </div>       
                            <div class="cleaner_h20"></div>
                            * Only view asset data not found                  
                        </div>
                    </div> 
<input type="hidden" class="url-datatable" value="<?php echo base_url('bo/'.$class.'/get_notfound')?>">
<script type="text/javascript">	
	$(document).ready(function(){		
		primari_table('.third-tables');		
	});
</script>    