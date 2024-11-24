<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-VDLLX3HKBL"></script>
<script>
window.dataLayer = window.dataLayer || [];

function gtag() {
    dataLayer.push(arguments);
}
gtag('js', new Date());

gtag('config', 'G-VDLLX3HKBL');
</script>

<?php error_reporting(0);?>

<?php $logged_in = $this->session->userdata('user_id'); ?>

<div class="container-fluid mt-2 p-4">
    <div class="row">
        <div class="col col-lg-5 p-3">
            <div class="d-flex flex-column mb-3 w-75">
                <?=form_open('client/ejournal/articles', ['method' => 'get', 'id' => 'searchForm'])?>
                <div class="input-group">
                    <input type="text" class="form-control form-control-lg custom-input-search" id="searchArticlesInput"
                        name="search" placeholder="Search articles">
                    <button class="btn custom-button-search" type="submit" id="searchArticlesBtn"><i
                            class="oi oi-magnifying-glass"></i></button>
                </div>
                <?=form_close()?>
                <p class="text-end fst-italic pr-3 me-3"><a class="main-link"
                        href="<?=base_url('/client/ejournal/advanced')?>">Advanced search</a></p>
            </div>

            <h3>Aim and Scope</h3>
            <p class="aim-scope">
                The NRCP Research Journal publishes articles on topics across the thirteen (13) divisions of the
                Council.
                <br><br>
                The journal aspires to become a valuable platform that nurtures cross-disciplinary research and
                collaboration which may lead to understanding and solving of complex challenges society faces. The NRCP
                Research Journal envisions itself to become a top-tier peer-reviewed open access multi-disciplinary
                journal that publishes rigorous and valuable research that broadly spans the entire spectrum of life,
                physical, earth, engineering, humanities, social and medical science, which contribute to basic,
                conceptual and practical scientific advancements including the translation of research to public policy.
            </p>

            <p class="aim-scope"><?php echo file_get_contents('./assets/uploads/DO_NOT_DELETE_description.txt'); ?></p>

            
            <div>
                <a href="<?php echo base_url('/client/ejournal/policy');?>" class="text-dark cursor-pointer">See more<span class="fa fa-chevron-circle-right main-link ms-1"></span></a>
            </div>

            <h3 class="mt-3">Volumes</h3>

            <div>
                <ul class="list-unstyled overflow-hidden" id="volume_list"  style="height:200px; min-height:200px">
                    <?php foreach($volumes as $row):?>
                        <?php if(strpos($row->jor_volume, 'Adv. Publication') === false){ ?>
                            <!-- <li><span class="fw-bold h6 text-decoration-underline">Volume <?=$key?></span> -->
                            <li class="mb-1"><a href="<?=base_url('/client/ejournal/volume/'.$row->jor_volume.'/'.$row->jor_issue)?>" class="fs-6 main-link cursor-pointer">Volume <?= $row->jor_volume ?>, <?= $row->jor_year ?></a>
                            
                                <!-- <ul class="list-unstyled mb-3">
                                    <?php foreach($row as $val):?>
                                        <?php   $issue = (
                                                    ($val[0] == 5) ? 'Special Issue No. 1' :
                                                    (($val[0] == 6) ? 'Special Issue No. 2' :
                                                        (($val[0] == 7) ? 'Special Issue No. 3' :
                                                            (($val[0] == 8) ? 'Special Issue No. 4' : 'Issue ' . $val[0])))
                                                );
                                        ?>
                                    <li><a href="<?=base_url('/client/ejournal/get_issues/'.$key.'/'.$val[1])?>" class="main-link"><?=$issue?></a></li>
                                    <?php endforeach; ?>
                                </ul> -->
                                            
                            </li>
                        <?php } ?>
                    <?php endforeach; ?>
                </ul>
                <div>
                    <a href="javascript:void(0);" class="text-dark cursor-pointer" id="see_more_volumes">See more<span class="fa fa-chevron-circle-right main-link ms-1"></span></a>
                </div>
                
            </div>
            
            <!-- <div class="accordion" id="accordionDivisions">
                <?php foreach($divisions as $row):?>
                <div class="accordion-item border-0 mb-2">
                    <a href="javascript:void(0);" class="main-link text-decoration-underline" data-bs-toggle="collapse"
                        data-bs-target="#collapse<?=$row->id;?>"><?=$row->title;?></a>
                    <div id="collapse<?=$row->id;?>" class="accordion-collapse collapse" aria-labelledby="headingOne"
                        data-bs-parent="#accordionDivisions">
                        <div class="accordion-body">
                            <?=$row->content;?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div> -->
        </div>
        <div class="col col-lg-5 p-3">
            <h3>Articles</h3>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="recent-tab" data-bs-toggle="tab"
                        data-bs-target="#recent-tab-pane" type="button" role="tab" aria-controls="recent-tab-pane"
                        aria-selected="true">Recent</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="most-access-tab" data-bs-toggle="tab"
                        data-bs-target="#most-access-tab-pane" type="button" role="tab"
                        aria-controls="most-access-tab-pane" aria-selected="false">Most Accessed</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="advance-publication-tab" data-bs-toggle="tab"
                        data-bs-target="#advance-publication-tab-pane" type="button" role="tab"
                        aria-controls="advance-publication-tab-pane" aria-selected="false">Advance Publication</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Recent -->
                <div class="tab-pane fade show active" id="recent-tab-pane" role="tabpanel" aria-labelledby="home-tab"
                    tabindex="0">

                    <?php $c = 0;foreach ($latest as $row):

                                    $c++;
                        
                                    $lat[$c]['title'] = $row->art_title;
                                    $lat[$c]['id'] = $row->art_id;
                                    $lat[$c]['id_jor'] = $row->art_jor_id;
                                    $lat[$c]['file'] = $row->art_abstract_file;
                                    $lat[$c]['keyw'] = $row->art_keywords;
                                    $lat[$c]['coa'] = $this->Client_journal_model->get_author_coauthors($row->art_id);
                                    $issue = (
                                        ($row->jor_issue == 5) ? 'Special Issue No. 1' :
                                        (($row->jor_issue == 6) ? 'Special Issue No. 2' :
                                            (($row->jor_issue == 7) ? 'Special Issue No. 3' :
                                                (($row->jor_issue == 8) ? 'Special Issue No. 4' : 'Issue ' . $row->jor_issue)))
                                    );
                                    $lat[$c]['cite'] =  $this->Client_journal_model->get_citation($row->art_id) . '('. $row->art_year .'). ' . ucfirst(strtolower($row->art_title)) . '. NRCP Research Journal, Volume ' . $row->jor_volume . ', ' . $issue . ', ' . $row->art_page;

                                    $server_dir = '/var/www/html/ejournal/assets/uploads/pdf/';
                                    $get_file = filesize($server_dir . $row->art_full_text_pdf);

                                    // $get_file = filesize($_SERVER['DOCUMENT_ROOT'].'/ejournal/assets/uploads/pdf/'.$row->art_full_text_pdf);

                                    if ($get_file >= 1048576) {
                                        $lat[$c]['fsize'] = round($get_file / 1024 / 1024, 1) . ' MB';
                                    } elseif ($get_file >= 1024) {
                                        $lat[$c]['fsize'] = round($get_file / 1024, 1) . ' KB';
                                    } else {
                                        $lat[$c]['fsize'] = round($get_file, 1) . ' bytes';
                                    }

                                    $lat[$c]['citations'] = $this->Client_journal_model->count_citation($row->art_id);
                                    $lat[$c]['count'] = $this->Client_journal_model->count_pdf($row->art_id);
                                    $lat[$c]['abs'] = $this->Client_journal_model->count_abstract($row->art_id);
                                    $lat[$c]['cover'] = $this->Client_journal_model->get_cover($row->art_jor_id);

                                    endforeach;?>
                    <?php $c = 1;foreach ($lat as $row): ?>
                    <?php $coa_arr = (explode(",& ", $row['coa']));?>
                    <div class="media mb-3 mt-3">
                        <!-- <img class="mr-2 img-thumbnail" height="20%" width="20%"
										src="<?=base_url('assets/uploads/cover/' . $row['cover'] . '')?>"> -->
                        <div class="media-body">
                            <div class="mt-0">
                                <!-- <a href="javascript:void(0);" class="main-link h6" onclick="top_article('<?=$row['id']?>','top','<?=$row['file']?>','Recent')"><?=$row['title']?></a> -->
                                <a href="<?= base_url() . 'client/ejournal/article/' . $row['id'] ?>" class="main-link h6"><?=$row['title']?></a>
                            </div>

                            <div class="mt-2">
                                <?php $i = 0; foreach ($coa_arr as $c): ?>
                                    <a href="<?= base_url() . 'client/ejournal/articles?search=' . str_replace(' ', '+', $c) ?>" class="text-muted"><?=$c;?></a>
                                <?php if($i < (count($coa_arr) - 1)) echo '<span class="text-muted">|</span>'?>
                                <?php $i++?>
                                <?php endforeach;?>

                            </div>


                            <div class="mt-2">
                                <span class="badge bg-light text-dark" data-toggle="tooltip" data-placement="top"
                                    title="Full Text Downloads"><i class="oi oi-data-transfer-download"></i>
                                    <?=$row['count']?></span>
                                <span class="badge bg-light text-dark" data-toggle="tooltip" data-placement="top"
                                    title="Abstract Hits"><i class="oi oi-eye"></i> <?=$row['abs']?></span>
                                <span class="badge bg-light text-dark" data-toggle="tooltip" data-placement="top"
                                    title="Cited"><i class="oi oi-document"></i>
                                    <?=$row['citations']?></span>

                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php endforeach;?>

                    <div class="d-flex gap-1 align-items-center justify-content-end">
                        <a href="<?=base_url('/client/ejournal/articles')?>" class="text-dark">View all articles<span class="fa fa-chevron-circle-right main-link ms-1"></span></a>
                    </div>
                </div>
                <!-- Most Accessed -->
                <div class="tab-pane fade" id="most-access-tab-pane" role="tabpanel" aria-labelledby="most-access-tab"
                    tabindex="0">
                    <?php if ($client_count != '' && $hits_count != '') { ?>
                    <?php $c = 0;foreach ($popular as $row):
					$c++;
					$pop[$c]['title'] = $row->art_title;
					$pop[$c]['id'] = $row->art_id;
					$pop[$c]['id_jor'] = $row->art_jor_id;
					$pop[$c]['file'] = $row->art_abstract_file;
					$pop[$c]['keyw'] = $row->art_keywords;
					$pop[$c]['coa'] = $this->Client_journal_model->get_author_coauthors($row->art_id);
					$issue = (
						($row->jor_issue == 5) ? 'Special Issue No. 1' :
						(($row->jor_issue == 6) ? 'Special Issue No. 2' :
							(($row->jor_issue == 7) ? 'Special Issue No. 3' :
								(($row->jor_issue == 8) ? 'Special Issue No. 4' : 'Issue ' . $row->jor_issue)))
					);
					$pop[$c]['cite'] =  $this->Client_journal_model->get_citation($row->art_id) . '('. $row->art_year .'). ' . ucfirst(strtolower($row->art_title)) . '. NRCP Research Journal, Volume ' . $row->jor_volume . ', ' . $issue . ', ' . $row->art_page;

					$server_dir = '/var/www/html/ejournal/assets/uploads/pdf/';
					$get_file = filesize($server_dir . $row->art_full_text_pdf);

					// $get_file = filesize($_SERVER['DOCUMENT_ROOT'].'/ejournal/assets/uploads/pdf/'.$row->art_full_text_pdf);

					if ($get_file >= 1048576) {
						$pop[$c]['fsize'] = round($get_file / 1024 / 1024, 1) . ' MB';
					} elseif ($get_file >= 1024) {
						$pop[$c]['fsize'] = round($get_file / 1024, 1) . ' KB';
					} else {
						$pop[$c]['fsize'] = round($get_file, 1) . ' bytes';
					}

				$pop[$c]['citations'] = $this->Client_journal_model->count_citation($row->art_id);
				$pop[$c]['count'] = $this->Client_journal_model->count_pdf($row->art_id);
				$pop[$c]['abs'] = $this->Client_journal_model->count_abstract($row->art_id);
				$pop[$c]['cover'] = $this->Client_journal_model->get_cover($row->art_jor_id);

				endforeach;?>

                    <?php $c = 1;foreach ($pop as $row): ?>
                    <?php $coa_arr = (explode(",& ", $row['coa']));?>
                    <div class="media mb-3 mt-3">
                        <!-- <img class="mr-2 img-thumbnail" height="20%" width="20%"
							src="<?=base_url('assets/uploads/cover/' . $row['cover'] . '')?>"> -->
                        <div class="media-body">
                            <div class="mt-0">
                                <!-- <a href="javascript:void(0);" class="main-link h6" onclick="top_article('<?=$row['id']?>','top','<?=$row['file']?>','Most Accessed')"><?=$row['title']?></a> -->
                                <!-- <a href="<?= base_url() . 'client/ejournal/articles?search=' . str_replace(' ', '+', $row['title']) ?>" class="main-link h6"><?=$row['title']?></a> -->
                                <a href="<?= base_url() . 'client/ejournal/article/' . $row['id'] ?>" class="main-link h6"><?=$row['title']?></a>
                            </div>


                            <div class="mt-2">
                                <?php $i = 0; foreach ($coa_arr as $c): ?>
                                    <a href="<?= base_url() . 'client/ejournal/articles?search=' . str_replace(' ', '+', $c) ?>" class="text-muted"><?=$c;?></a>
                                <?php if($i < (count($coa_arr) - 1)) echo '<span class="text-muted">|</span>'; ?>
                                <?php $i++; ?>
                                <?php endforeach;?>
                            </div>

                            <div class="mt-2">
                                <span class="badge bg-light text-dark" data-toggle="tooltip" data-placement="top"
                                    title="Full Text Downloads"><span class="oi oi-data-transfer-download"></span>
                                    <?=$row['count']?></span>
                                <span class="badge bg-light text-dark" data-toggle="tooltip" data-placement="top"
                                    title="Abstract Hits"><span class="oi oi-eye"></span> <?=$row['abs']?></span>
                                <span class="badge bg-light text-dark" data-toggle="tooltip" data-placement="top"
                                    title="Cited"><span class="oi oi-document"></span>
                                    <?=$row['citations']?></span>

                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php endforeach;}?>

                    <div class="d-flex gap-1 align-items-center justify-content-end">
                    <a href="<?=base_url('/client/ejournal/articles')?>" class="text-dark">View all articles<span class="fa fa-chevron-circle-right main-link ms-1"></span></a>
                    </div>
                </div>
                <!-- Advance Publication -->
                <div class="tab-pane fade" id="advance-publication-tab-pane" role="tabpanel"
                    aria-labelledby="advance-publication-tab" tabindex="0">


                    <?php $c = 0;foreach ($adv_publication as $row):

                    $c++;

                    $lat[$c]['title'] = $row->art_title;
                    $lat[$c]['id'] = $row->art_id;
                    $lat[$c]['id_jor'] = $row->art_jor_id;
                    $lat[$c]['file'] = $row->art_abstract_file;
                    $lat[$c]['keyw'] = $row->art_keywords;
                    $lat[$c]['coa'] = $this->Client_journal_model->get_author_coauthors($row->art_id);
                    $issue = (
                        ($row->jor_issue == 5) ? 'Special Issue No. 1' :
                        (($row->jor_issue == 6) ? 'Special Issue No. 2' :
                            (($row->jor_issue == 7) ? 'Special Issue No. 3' :
                                (($row->jor_issue == 8) ? 'Special Issue No. 4' : 'Issue ' . $row->jor_issue)))
                    );
                    $lat[$c]['cite'] =  $this->Client_journal_model->get_citation($row->art_id) . '('. $row->art_year .'). ' . ucfirst(strtolower($row->art_title)) . '. NRCP Research Journal, Volume ' . $row->jor_volume . ', ' . $issue . ', ' . $row->art_page;

                    $server_dir = '/var/www/html/ejournal/assets/uploads/pdf/';
                    $get_file = filesize($server_dir . $row->art_full_text_pdf);

                    // $get_file = filesize($_SERVER['DOCUMENT_ROOT'].'/ejournal/assets/uploads/pdf/'.$row->art_full_text_pdf);

                    if ($get_file >= 1048576) {
                        $lat[$c]['fsize'] = round($get_file / 1024 / 1024, 1) . ' MB';
                    } elseif ($get_file >= 1024) {
                        $lat[$c]['fsize'] = round($get_file / 1024, 1) . ' KB';
                    } else {
                        $lat[$c]['fsize'] = round($get_file, 1) . ' bytes';
                    }

                    $lat[$c]['citations'] = $this->Client_journal_model->count_citation($row->art_id);
                    $lat[$c]['count'] = $this->Client_journal_model->count_pdf($row->art_id);
                    $lat[$c]['abs'] = $this->Client_journal_model->count_abstract($row->art_id);
                    $lat[$c]['cover'] = $this->Client_journal_model->get_cover($row->art_jor_id);

                    endforeach;?>

                    <?php $c = 1;foreach ($lat as $row): ?>
                    <?php $coa_arr = (explode(",& ", $row['coa']));?>
                    <div class="media mb-3 mt-3">
                        <!-- <img class="mr-2 img-thumbnail" height="20%" width="20%"
                        src="<?=base_url('assets/uploads/cover/' . $row['cover'] . '')?>"> -->
                        <div class="media-body">
                            <div class="mt-0">
                                <!-- <a href="javascript:void(0);" class="main-link h6" onclick="top_article('<?=$row['id']?>','top','<?=$row['file']?>','Advance Publication')"><?=$row['title']?></a> -->
                                <!-- <a href="<?= base_url() . 'client/ejournal/articles?search=' . str_replace(' ', '+', $row['title']) ?>" class="main-link h6"><?=$row['title']?></a> -->
                                <a href="<?= base_url() . 'client/ejournal/article/' . $row['id'] ?>" class="main-link h6"><?=$row['title']?></a>
                            </div>

                            <div class="mt-2">
                                <?php $i = 0; foreach ($coa_arr as $c): ?>
                                    <a href="<?= base_url() . 'client/ejournal/articles?search=' . str_replace(' ', '+', $c) ?>" class="text-muted"><?=$c;?></a>
                                <?php if($i < (count($coa_arr) - 1)) echo '<span class="text-muted">|</span>'?>
                                <?php $i++?>
                                <?php endforeach;?>

                            </div>

                            <div class="mt-2">
                                <span class="badge bg-light text-dark" data-toggle="tooltip" data-placement="top"
                                    title="Full Text Downloads"><i class="oi oi-data-transfer-download"></i>
                                    <?=number_format($row['count'], 0, '', ',')?></span>
                                <span class="badge bg-light text-dark" data-toggle="tooltip" data-placement="top"
                                    title="Abstract Hits"><i class="oi oi-eye"></i>
                                    <?=number_format($row['abs'], 0, '', ',')?></span>
                                <span class="badge bg-light text-dark" data-toggle="tooltip" data-placement="top"
                                    title="Cited"><i class="oi oi-document"></i>
                                    <?=number_format($row['citations'], 0, '', ',')?></span>
                            </div>

                        </div>
                    </div>
                    <hr>
                    <?php endforeach;?>

                    <div class="d-flex gap-1 align-items-center justify-content-end">
                    <a href="<?=base_url('/client/ejournal/articles')?>" class="text-dark">View all articles<span class="fa fa-chevron-circle-right main-link ms-1"></span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-lg-2 p-3">
            <?php $this->load->view('common/side_panel');?>
        </div>
    </div>
</div>


<!-- ABSTRACT MODAL -->
<div class="modal fade" id="abstract_modal" role="dialog" aria-labelledby="abstract_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Abstract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <embed id="abstract_view" WMODE="transparent" width="100%" height="700px" type="application/pdf">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn main-btn " id="download_pdf">Request Full Text PDF <span
                class="oi oi-data-transfer-download ms-2" style="font-size:.8rem"></span></button>
                <button type="button" class="btn main-btn " id="download_pdf"><span
                        class="oi oi-locks"></span> Login to Get Access</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="enlargeImageModal" tabindex="-1" role="dialog" aria-labelledby="enlargeImageModal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <img src="" class="enlargeImageModalSource" style="height:50%;width: 100%;">
            </div>
        </div>
    </div>
</div>

<!-- TOP ARTICLE MODAL -->
<div class="modal fade" id="top_modal" tabindex="-1" role="dialog" style="z-index: 1051 !important;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <embed id="top_abstract_view" WMODE="transparent" width="100%" height="700px" type="application/pdf">
            </div>
            <div class="modal-footer">


                <!-- <button type="button" class="btn main-btn" onclick="download_full_paper('<?= $logged_in ?>')">
                    Download Full Paper <span class="oi oi-data-transfer-download ms-2" style="font-size:.8rem"></span></button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button> -->

                <?php if ($logged_in) {
                    echo '<button type="button" class="btn main-btn" id="top_download_pdf">
                    Download Full Paper <span class="oi oi-data-transfer-download ms-2" style="font-size:.8rem"></span></button>';
                } else {
                    echo '<a type="button" class="btn main-btn" href="'.base_url('client/login').'">
                    Login to Get Access <span class="oi oi-account-login ms-2"></span></a>';
                }?>
            </div>
        </div>
    </div>
</div>

<!-- PDF MODAL -->
<div class="modal fade" id="client_modal" role="dialog" aria-labelledby="client_modal" aria-hidden="true"
    data-backdrop="static" style="z-index: 1052 !important;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="media">
                    <div class="media-body">
                        <h5 class="mt-0">Please provide your information</h5>
                        <small>This file will be sent to your email</small>
                    </div>
                </div>
                <!-- <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
            </div>
            <div class="modal-body">
                <form id="form-client" action="<?php //echo base_url('client/ejournal/download_pdf');?>" method="post"
                    autocomplete="off">
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_title">Title<span
                                class="text-danger font-weight-bold">*</span></label>
                        <input type="text" class="form-control" id="clt_title" name="clt_title"
                            placeholder="Mr. / Ms. / Dr.">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_name">Full name<span
                                class="text-danger font-weight-bold">*</span></label>
                        <input type="text" class="form-control" id="clt_name" name="clt_name"
                            placeholder="Juan Dela Cruz">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_age">Age <sup
                                class="text-info font-weight-bold small">(<i class="text-danger">*</i> Must be 20 years
                                old and above)</sup></label>
                        <input type="number" class="form-control" id="clt_age" name="clt_age" min="20" max="100">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_sex">Sex<span
                                class="text-danger font-weight-bold">*</span></label>
                        <select class="form-control" id="clt_sex" name="clt_sex">
                            <option value="">Select Sex</option>
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_affiliation">Affiliation<span
                                class="text-danger font-weight-bold">*</span></label>
                        <input type="text" class="form-control" id="clt_affiliation" name="clt_affiliation">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_country">Country<span
                                class="text-danger font-weight-bold">*</span></label>
                        <select class="form-control" id="clt_country" name="clt_country" placeholder="Select Country"
                            style="background-color: white">
                            <!-- foreach of country -->
                            <?php foreach ($country as $c): ?>
                            <?php $selected = ($c->country_id == '175') ? 'selected' : '';
                            echo '<option value=' . $c->country_id . '>' . $c->country_name . '</option>';?>
                            <?php endforeach;?>
                            <!-- /.end of foreach-->
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_purpose">Purpose<span
                                class="text-danger font-weight-bold">*</span></label>
                        <textarea class="form-control" id="clt_purpose" name="clt_purpose"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_email">Email<span
                                class="text-danger font-weight-bold">*</span></label>
                        <input type="email" class="form-control" id="clt_email" name="clt_email"
                            placeholder="Valid email is required">
                        <div id="verification_code_div" class="mt-1">
                            <div class="btn btn-warning btn-block small font-weight-bold" id="send_verification_code"
                                onclick="send_verification_code()" style="font-size:0.9em; width:100%;"
                                title="Click this button to get the verification code emailed to you."><sup
                                    class="text-danger font-weight-bold">*</sup>Click this button to get the
                                verification code emailed to you.</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_vcode">Verification Code<span
                                class="text-danger font-weight-bold">*</span></label>
                        <input type="text" class="form-control font-weight-bold text-center" id="clt_vcode"
                            name="clt_vcode" placeholder="Verification code is required">
                    </div>
                    <div class="form-group text-left">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" name="clt_member"
                                id="clt_member">
                            <label class="custom-control-label" for="clt_member">Please check the box if you are an
                                <strong>NRCP member</strong>.</label>
                        </div>
                    </div>
                    <input type="hidden" id="clt_journal_downloaded_id" name="clt_journal_downloaded_id">
                    <?php /*
						<div class="bg-danger px-2">
							<label class="text-white font-weight-bold">"Sorry, our request form is currently undergoing development. Please check back later. Thank you for your understanding!" </label>
						</div>
						*/;?>
                    <div id="message_notif"></div>
            </div>
            <div class="modal-footer">
                <div id="alert-processing" class="alert alert-primary text-center h6 w-100 invisible" role="alert">
                    <span class="oi oi-clock oi-spin "></span> Sending Full Text PDF...
                    <!-- <span class="font-weight-bold" id="pdf_mail"></span> -->
                </div>

                <button type="button" class="btn btn-outline-secondary" id="btn_cancel_client_info"
                    data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" id="btn_submit_client_info"
                    name="btn_submit_client_info"><span class="oi oi-check"></span> Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- AUTHOR DETAILS -->
<div class="modal fade" tabindex="-1" role="dialog" id="acoa_details_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <a class="btn main-link font-weight-bold w-100 me-1 d-flex align-items-center justify-content-center">Show related articles<i class="oi oi-chevron-right ms-1" style="font-size: .7rem"></i></a>
            </div>
        </div>
    </div>
</div>
<!-- Citation Modal -->
<div class="modal fade" id="citationModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Citation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Please provide us with your Full Name and Email Address. Then click SUBMIT to show the APA
                    citation</p>
                <form id="form_citation" autocomplete="off">
                    <input type="hidden" id="cite_value" name="cite_value">
                    <div class="form-group">
                        <label class="font-weight-bold" for="cite_title">Title<span
                                class="text-danger font-weight-bold">*</span></label>
                        <input type="text" class="form-control" id="cite_title" name="cite_title"
                            placeholder="Mr. / Ms. / Dr.">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="cite_name">Name<span
                                class="text-danger font-weight-bold">*</span></label>
                        <input type="text" class="form-control" id="cite_name" name="cite_name" placeholder="Full name">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="cite_sex">Sex<span
                                class="text-danger font-weight-bold">*</span></label>
                        <select class="form-control" id="cite_sex" name="cite_sex">
                            <option value="">Select Sex</option>
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_affiliation">Affiliation<span
                                class="text-danger font-weight-bold">*</span></label>
                        <input type="text" class="form-control" id="cite_affiliation" name="cite_affiliation"
                            placeholder="Affiliation">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="cite_country">Country<span
                                class="text-danger font-weight-bold">*</span></label>
                        <select class="form-control" id="cite_country" name="cite_country" placeholder="Select Country"
                            style="background-color: white">
                            <!-- foreach of country -->
                            <?php foreach ($country as $c): ?>
                            d
                            <?php $selected = ($c->country_id == '175') ? 'selected' : '';
echo '<option value=' . $c->country_id . ' ' . $selected . '>' . $c->country_name . '</option>';?>
                            <?php endforeach;?>
                            <!-- /.end of foreach-->
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="cite_email">Email<span
                                class="text-danger font-weight-bold">*</span></label>
                        <input type="email" class="form-control" id="cite_email" name="cite_email" placeholder="Email">
                    </div>
                    <div class="form-group text-left">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" name="cite_member"
                                id="cite_member">
                            <label class="custom-control-label" for="cite_member">Please check the box if you are an
                                NRCP member?</label>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <div id="cite_content" class="w-100">
                    <ul class="nav nav-tabs" id="cite_tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#apa" role="tab">APA</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="cite_tab_content">
                        <div class="tab-pane fade show active text-center" role="tabpanel" id="apa">
                            <textarea id="apa_format" class="form-control" readonly rows="5"></textarea>
                        </div>
                    </div>
                    <button type="button" onClick="copyCitationToClipboard('#apa_format')"
                        class="btn btn-outline-primary mt-3 w-100">Copy to clipboard</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- feedback modal -->
<div class="modal fade" id="feedbackModal" data-backdrop="static" data-keyboard="false"
    style="z-index: 1051 !important;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <p><span class="modal-title font-weight-bold h3">Your Feedback</span><br />
                </p>
            </div>
            <div class="modal-body p-4">

                <form id="feedback_form" autocomplete="off">

                    <h6 class="font-weigh-bold mb-3">Please rate your experience for the following</h6>
                    <input type="hidden" id="fb_usr_id" name="fb_usr_id">
                    <input type="hidden" id="fb_source" name="fb_source">

                    <p>1. User Interface - Overall design of the website.</p>

                    <div class="rating">
                        <input type="radio" name="fb_rate_ui" value="5" id="ui-5" required><label for="ui-5"
                            data-toggle="tooltip" data-placement="top" title="Excellent">★</label>
                        <input type="radio" name="fb_rate_ui" value="4" id="ui-4" required><label for="ui-4"
                            data-toggle="tooltip" data-placement="top" title="Good">★</label>
                        <input type="radio" name="fb_rate_ui" value="3" id="ui-3" required><label for="ui-3"
                            data-toggle="tooltip" data-placement="top" title="Fair">★</label>
                        <input type="radio" name="fb_rate_ui" value="2" id="ui-2" required><label for="ui-2"
                            data-toggle="tooltip" data-placement="top" title="Poor">★</label>
                        <input type="radio" name="fb_rate_ui" value="1" id="ui-1" required><label for="ui-1"
                            data-toggle="tooltip" data-placement="top" title="Very Poor">★</label>
                    </div>

                    <p>2. Any other suggestions <sup class="text-info hide" id="fb_suggest_ui_prompt"></sup></p>

                    <textarea class="form-control mb-3" name="fb_suggest_ui" id="fb_suggest_ui" rows="3"
                        placeholder="Optional" maxlength="300"></textarea>


                    <p>3. User Experience - Overall experience of the website.</p>

                    <div class="rating">
                        <input type="radio" name="fb_rate_ux" value="5" id="ux-5" required><label for="ux-5"
                            data-toggle="tooltip" data-placement="top" title="Excellent">★</label>
                        <input type="radio" name="fb_rate_ux" value="4" id="ux-4" required><label for="ux-4"
                            data-toggle="tooltip" data-placement="top" title="Good">★</label>
                        <input type="radio" name="fb_rate_ux" value="3" id="ux-3" required><label for="ux-3"
                            data-toggle="tooltip" data-placement="top" title="Fair">★</label>
                        <input type="radio" name="fb_rate_ux" value="2" id="ux-2" required><label for="ux-2"
                            data-toggle="tooltip" data-placement="top" title="Poor">★</label>
                        <input type="radio" name="fb_rate_ux" value="1" id="ux-1" required><label for="ux-1"
                            data-toggle="tooltip" data-placement="top" title="Very Poor">★</label>
                    </div>

                    <p>4. Any other suggestions <sup class="text-info hide" id="fb_suggest_ux_prompt"></sup></p>

                    <textarea class="form-control" name="fb_suggest_ux" id="fb_suggest_ux" rows="3"
                        placeholder="Optional" maxlength="300"></textarea>
                    <div class="alert-prompt my-1" id="alert_prompt"></div>
                    <div class="form-group text-right mt-3 pb-0 mb-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submit_feedback">Submit Feedback</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /.feedback modal -->


<script>
function send_verification_code() {
    let clt_email = $("#clt_email").val();
    $("#send_verification_code").html("Please wait..");
    var url = "<?php base_url('client/ejournal/send_verification_code');?>";
    $.post(url, {
        clt_email: clt_email
    }, function(data, status) {
        console.log(status);
        console.log(data);
        if (status == "success") {
            $("#verification_code_div").html(data);
        } else {
            $("#verification_code_div").html(data);
        }
    })
}

$("#fb_suggest_ui").on('input', function() {
    var inputText = $(this).val();
    let x = $(this).val().length;
    if (x > 0 && x <= 300) {
        $("#fb_suggest_ui_prompt").text(x + " (Maximum of 300 characters in length)");
    } else if (x > 0 && x > 300) {
        $("#fb_suggest_ui_prompt").html(
            "<span class='text-danger font-weight-bold'>&#9888; Exceeded 300 characters limit!</span>");
    } else {
        $("#fb_suggest_ui_prompt").text("");
    }
    var sqlInjectionPattern = /^[^';()\/\\]*$/; // Regular expression pattern for sql Injection Pattern
    var foundSpecialChars = false;

    // Loop through each character in the input text
    for (var i = 0; i < x; i++) {
        if (!sqlInjectionPattern.test(inputText[i])) {
            foundSpecialChars = true;
            break; // Exit loop if a special character is found
        }
    }

    if (foundSpecialChars) {
        $("#fb_suggest_ui_prompt").html(
            "<span class='text-danger font-weight-bold'>  &#9888; Special characters not allowed</span>");
    }
});

$("#fb_suggest_ux").on('input', function() {
    var inputText = $(this).val();
    let x = $(this).val().length;
    if (x > 0 && x <= 300) {
        $("#fb_suggest_ux_prompt").text(x + " (Maximum of 300 characters in length)");
    } else if (x > 0 && x > 300) {
        $("#fb_suggest_ux_prompt").html(
            "<span class='text-danger font-weight-bold'> &#9888; Exceeded 300 characters limit!</span>");
    } else {
        $("#fb_suggest_ux_prompt").text("");
    }
    var sqlInjectionPattern = /^[^';()\/\\]*$/; // Regular expression pattern for sql Injection Pattern
    var foundSpecialChars = false;

    // Loop through each character in the input text
    for (var i = 0; i < x; i++) {
        if (!sqlInjectionPattern.test(inputText[i])) {
            foundSpecialChars = true;
            break; // Exit loop if a special character is found
        }
    }

    if (foundSpecialChars) {
        $("#fb_suggest_ux_prompt").html(
            "<span class='text-danger font-weight-bold'>  &#9888; Special characters not allowed</span>");
    }
});
</script>