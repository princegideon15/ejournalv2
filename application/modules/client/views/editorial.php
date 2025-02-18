<?php error_reporting(0);?>
<div class="container-fluid mt-2 p-4">
	<div class="row">
		<!-- SIDE NAVIGATION -->
        <div class="col col-3 p-3">
            <a class="btn btn-link main-link" href="<?=base_url('/client/ejournal/articles')?>">View all articles</a>
		</div>
			<!-- <div class="list-group sticky" id="list_group_menu">
				<li class="list-group-item font-weight-bold bg-light"><span class="oi oi-book"></span> JOURNALS</li>
				<?php if ($journals != null) {
				$c = 0;foreach ($journals as $row): ?>
				<?php $c++;?>
				<?php echo '<a href="'.base_url('/client/ejournal/get_issues/'.$row->jor_volume.'').'" class="list-group-item  list-group-item-action">Volume ' . $row->jor_volume . ', ' . $row->jor_year . '<span class="oi oi-chevron-right float-right mt-1" style="font-size:10px"></span></a>'; ?>
				<?php endforeach;}?>
			</div> -->
		<div class="col col-7 p-3">
			<div class="tab-content bg-white rounded border p-5" id="nav-tabContent">
				<div class="tab-pane fade show active " id="home" role="tabpanel" aria-labelledby="home">
					
						<!-- <div class="row">
							<div class="col">
								<div class="btn-group" role="group" aria-label="Basic example">
								<a type="button" class="btn btn-dark mr-1 text-white disabled">ARTICLES:</a>
									<a href="<?php echo base_url('/client/ejournal/get_index');?>" type="button" class="btn btn-outline-primary mr-1">INDEX</a>
									 <?php foreach (range('A', 'Z') as $char) {
										echo '<a href="'.base_url('/client/ejournal/get_index/'.$char.'').'" type="button" class="btn btn-outline-primary mr-1">'. $char .'</a>';
										}
									 ?>
								</div>
							</div>
						</div> -->
						<h3>Editorial Board and Staff</h3>

						
						<div class="accordion" id="accordionEditorial">
							<?php $flag=0; foreach($editorials_vol_year as $row):?>
							<?php    
								$issue = (
										($row->edt_issue == 5) ? 'Special Issue No. 1, ' :
											(($row->edt_issue == 6) ? 'Special Issue No. 2, ' :
												(($row->edt_issue == 7) ? 'Special Issue No. 3, ' :
													(($row->edt_issue == 8) ? ',Special Issue No. 4, ' :
														(($row->edt_issue == 0) ? '' : 'Issue ' . $row->edt_issue . ', '))))
									);

									
								$flag++;
								$show = ($flag == 1) ? 'show' : '';
							?>
							<div class="accordion-item border-0 mb-2">
								<a href="javascript:void(0);" class="main-link text-decoration-underline" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $flag;?>">
									Editorial Board and Staff - <?php echo $issue . $row->volume;?>
								</a>
								<div id="collapse<?php echo $flag;?>" class="accordion-collapse collapse <?php echo $show;?>"
									aria-labelledby="headingOne" data-bs-parent="#accordionEditorial">
									<div class="accordion-body p-0">
										<?php $editorials = $this->Client_journal_model->get_editorials_by_volume_year($row->edt_volume, $row->edt_year, $row->edt_issue); ?>
										<?php foreach($editorials as $row): ?>
										<?php $expertise = ($row->edt_specialization == NULL) ? '' : 'Expertise: ' . $row->edt_specialization . '<br/>'; ?>

										<div class="d-flex mt-3">
											<div class="flex-shrink-0">
												<img class="me-3 border" src="<?php echo base_url("assets/uploads/editorial/" . $row->edt_photo);?>" height="180" width="150">
											</div>
											<div class="flex-grow-1 ms-2">
												<h6 class="mt-0 fw-bold"><?php echo $row->edt_name;?></h6>
												<?php echo $row->edt_position;?><br/>
												<?php echo $row->edt_position_affiliation;?><br/>
												<?php echo $row->edt_affiliation;?><br/>
												<?php echo $row->edt_address;?><br/>
												<?php echo $expertise;?>
												<?php echo $row->edt_email;?>
											</div>
										</div>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
							<?php endforeach; ?>
						</div>

                        <div class="accordion bg-white" id="accordionExample">
							<?php $body;$flag = 0; foreach($editorials_vol_year as $row):
                            
                                
                                $issue = (
                                    ($row->edt_issue == 5) ? 'Special Issue No. 1, ' :
                                    (($row->edt_issue == 6) ? 'Special Issue No. 2, ' :
                                        (($row->edt_issue == 7) ? 'Special Issue No. 3, ' :
                                            (($row->edt_issue == 8) ? ',Special Issue No. 4, ' :
                                              (($row->edt_issue == 0) ? '' : 'Issue ' . $row->edt_issue . ', '))))
                                );
                                
                                $flag++;
                                $show = ($flag == 1) ? 'show' : '';
                                // $top = ($flag == 1) ? '<h2 class="text-dark">Editorial Board and Staff - '. $issue . $row->volume .'</h2>' :  'Editorial Board and Staff - '. $issue . $row->volume; 
                                
                                $body .= '<div class="card">
                                    <div class="card-header " id="headingOne">
                                        <a href="" class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapseOne'. $flag .'" aria-expanded="true" aria-controls="collapseOne">
                                        Editorial Board and Staff - '. $issue . $row->volume .'
                                        </a>
                                    </div>

                                    <div id="collapseOne'. $flag .'" class="collapse '. $show .'" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <div class="card-body p-0">';
                                        $editorials = $this->Client_journal_model->get_editorials_by_volume_year($row->edt_volume, $row->edt_year, $row->edt_issue);
                                        
                                        $body .= '<ul class="list-group">';

                                        foreach($editorials as $row):
                                                $expertise = ($row->edt_specialization == NULL) ? '' : 'Expertise: ' . $row->edt_specialization . '<br/>';
                                                $body .= '<li class="list-group-item ">
                                                            <div class="media">
                                                                <img class="mr-3 img-thumbnail" src="'. base_url("assets/uploads/editorial/" . $row->edt_photo . "") .'"  alt="..." style="width:150px; height:180px;">
                                                                <div class="media-body">
                                                                <h5 class="mt-0">' . $row->edt_name . '</h5>
                                                                ' . $row->edt_position . '<br/>
                                                                ' . $row->edt_position_affiliation . '<br/>
                                                                ' . $row->edt_affiliation . '<br/>
                                                                ' . $row->edt_address . '<br/>
                                                                ' . $expertise . '
                                                                ' . $row->edt_email . '<br/>
                                                                </div>
                                                            </div>
                                                        </li>';
                                        endforeach;
                                            
                                        $body .= '</ul>';
                                        $body .= '</div>
                                    </div>
                                </div>';

                            endforeach; ?>


						</div>
                        
						<div class="mt-5">
						<h6 class="fw-bold">Open Access</h6>   
							<a rel="license" href="http://creativecommons.org/licenses/by/4.0/" target="_blank"><img
									alt="Creative Commons License" style="border-width:0"
									src="https://i.creativecommons.org/l/by/4.0/88x31.png" /></a><br />This work is
							licensed
							under a <a rel="license" href="http://creativecommons.org/licenses/by/4.0/"
								target="_blank">Creative Commons Attribution 4.0 International License</a>.
						</div>
					
				</div>
			</div>
		</div>
		<div class="col col-2 p-3">
			<?php $this->load->view('common/side_panel');?>
		</div>
	</div>
    </div>
</div>


<!-- Submit Feedback Floater -->
<!-- <div class="fixed-bottom text-right pr-5">
	<button type="button" class="btn btn-sm btn-warning font-weight-bold px-3 py-2" data-target="#feedbackModal"
		data-toggle="modal">
		<span class="oi oi-comment-square mr-1"></span>
		Submit Feedback
	</button>
</div> -->
