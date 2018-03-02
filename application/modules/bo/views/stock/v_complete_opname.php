<div class="panel panel-info">
                        <div class="panel-heading">
                             <?php if ($this->m_master->group_akses() == false){?>
                             <a class="btn btn-success btn-sm" href="<?php echo base_url('bo/'.$class.'/form')?>" >Tambah</a>
                              <a class="btn btn-danger btn-sm" href="<?php echo base_url('bo/'.$class.'/export_master/csv')?>">Export CSV to Master</a> 
                             <?php }?> 
                              <span class="pull-right">                        
                             <a class="btn btn-primary btn-sm" href="<?php echo base_url('bo/'.$class.'/export/complete/csv')?>">Export CSV</a>
                             <a class="btn btn-info btn-sm" href="<?php echo base_url('bo/'.$class.'/export/complete/excel')?>">Export Excel</a>
                             <a class="btn btn-warning btn-sm" href="<?php echo base_url('bo/'.$class.'/export/complete/pdf')?>" target="_blank">Export PDF</a>
                             </span>
                              <span class="center"><h4>Complete Opname</h4></span>	
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                             <?php $this->load->view('bo/v_date_range')?>
                                <table class="table table-striped table-bordered table-hover ss-tables" value="<?php echo base_url('bo/'.$class.'/get_records/1')?>">
                                    <thead>
                                        <tr><th>#</th>
                                        	<th>Sub Number</th>   
                                        	<th>Description</th>  
                                        	<th>Asset Class</th>       
                                        	<th>Jenis</th>
                                        	<th>Company Code</th>
                                        	<th>Bisnis Area</th>
                                        	<th>Sub Location</th>                            	                                      	     
                                        	<th>Scan Date</th>
                                        	<th>Scan By</th>  
                                        	<th>Status</th>                                      	                              	                                        	                                     	                                      	
                                        </tr>                                      	              	
                                    </thead>                                    
                                </table>
                            </div>       
                            <div class="cleaner_h20"></div>
                            * Only view asset data active                     
                        </div>
                    </div> 
<script type="text/javascript">	
$(document).ready(function(){		
	primari_table('.ss-tables');		
});
</script>    