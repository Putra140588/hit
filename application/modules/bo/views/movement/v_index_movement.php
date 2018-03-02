<div id="page-wrapper">
       <div class="row">
          <div class="col-lg-12">
              <h2 class="page-header"><?php echo $page_title?></h2> 
              <?php $this->load->view('bo/v_alert_notif');?>             
          </div>
        <div class="row">
                <div class="col-lg-12">                                     
                   <div class="panel panel-default">
                        <div class="panel-heading">
                           	Select user for move assets
                        </div>
                        <div class="panel-body">
                            <ul class="nav nav-tabs">
                            <?php foreach ($getuser as $row){
                            	$min = min($getuser[0]->company_code,0);
                            	$classtab = ($row->company_code == $min) ? 'active' : '';
                            	echo '<li class="'.$classtab.'"><a href="#'.$row->company_code.'" value="" data-toggle="tab" onclick="ajaxcall(\''.base_url('bo/'.$class.'/tab_onclick').'\',\''.$row->company_code.'#'.$row->id_user.'\',\''.$row->company_code.'\')">'.$row->nama_depan.' ('.$row->company_code.')</a></li>';
                            }?>                                                           
                            </ul>
                            <div class="tab-content">
                            <div class="cleaner_h20"></div>
                             <?php foreach ($getuser as $row){                             	
                            	$min = min($getuser[0]->company_code,0);
                            	$classtab = ($row->company_code == $min) ? 'active' : '';
                            	$dt_class= $row->company_code.$row->id_user;
                            	echo '<div class="tab-pane fade in '.$classtab.'" id="'.$row->company_code.'">	                  							                           
	                                    	'.$this->m_master->table_move($row->company_code,$row->id_user).'    		            		
                              		  </div> ';      
                            	                      	
                            }?>
                                                             
                            </div>
                        </div>
                    </div>
                   
                </div>                 
        </div>            
     </div>
</div>    
