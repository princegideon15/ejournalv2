<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid pt-3">
        <div class="card border border-dark">
          <div class="card-body">
            <h3 class="fw-bold">Statistics</h3>

            <!-- <ul class="nav nav-underline" id="myTab" role="tablist"> -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#sub_sum" type="button" role="tab" aria-controls="sub_sum" aria-selected="true">Submission Summary</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sub_stat" type="button" role="tab" aria-controls="sub_stat" aria-selected="false">Submission Statistics</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#auth_sex" type="button" role="tab" aria-controls="auth_sex" aria-selected="false">Author by Sex</button>
            </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="sub_sum" role="tabpanel" tabindex="0">
                    <div class="d-flex gap-1 mt-3">
                        <div class="mb-3">
                            <label for="date_from" class="form-label">Start Date</label>
                            <input type="date" id="date_from" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="date_to" class="form-label">End Date</label>
                            <input type="date" id="date_to" class="form-control">
                        </div>
                        <div class="mb-3 d-flex flex-column justify-content-end">
                            <button class="btn btn-light border border-1" onclick="filter_submission_summary()">Go</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-hover" id="sub_sum_table">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Type of Publication</th>
                            <th>No. of submissions</th>
                            <th>Rejected from the overall process</th>
                            <th>Percentage Equivalent</th>
                            <th>Passed the overall process</th>
                            <th>Percentage Equivalent</th>
                            <th>In process</th>
                            <th>Percentage Equivalent</th>
                            <th>Published</th>
                            <th>Percentage Equivalent</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $c = 1;

                            $total_subm = 0;
                            $total_rej = 0;
                            $total_pass = 0;
                            $total_process = 0;
                            $total_publ = 0;

                            foreach ($stat_summary as $row): 
                            
                                $total_subm += $row->subm_count;
                                $total_rej += $row->rej_count;
                                $total_pass += $row->pass_count;
                                $total_process += $row->process_count;
                                $total_publ += $row->publ_count;
                            ?>

                            <tr class="text-center">
                                <td ><?= $row->pub_id ?></td>
                                <td style="text-align: left !important;"><?= $row->publication_desc ?></td>
                                <td><?= ($row->subm_count > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $row->subm_count . '</a>' : 0; ?></td>
                                <td><?= ($row->rej_count > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $row->rej_count . '</a>' : 0; ?></td>
                                <td><?= ($row->subm_count > 0 ) ? round ( ($row->rej_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                                <td><?= ($row->pass_count > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $row->pass_count . '</a>' : 0; ?></td>
                                <td><?= ($row->subm_count > 0 ) ? round ( ($row->pass_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                                <td><?= ($row->process_count > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $row->process_count . '</a>' : 0; ?></td>
                                <td><?= ($row->subm_count > 0 ) ? round ( ($row->process_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                                <td><?= ($row->publ_count > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $row->publ_count . '</a>' : 0; ?></td>
                                <td><?= ($row->subm_count > 0 ) ? round ( ($row->publ_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                            </tr>

                            <?php endforeach;?>

                            <tr class="text-center fw-bold">
                                <td><?= count($stat_summary) + 1 ?></td>
                                <td style="text-align: left !important";>Total</td>
                                <td><?= ($total_subm > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $total_subm . '</a>' : 0; ?></td>
                                <td><?= ($total_rej > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $total_rej . '</a>' : 0; ?></td>
                                <td><?= ($total_subm > 0 ) ? round ( ($total_rej / $total_subm) * 100, 2 ) : '0' ?>%</td>
                                <td><?= ($total_pass > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $total_pass . '</a>' : 0; ?></td>
                                <td><?= ($total_subm > 0 ) ? round ( ($total_pass / $total_subm) * 100, 2 ) : '0' ?>%</td>
                                <td><?= ($total_process > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $total_process . '</a>' : 0; ?></td>
                                <td><?= ($total_subm > 0 ) ? round ( ($total_process / $total_subm) * 100, 2 ) : '0' ?>%</td>
                                <td><?= ($total_publ > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $total_publ . '</a>' : 0; ?></td>
                                <td><?= ($total_subm > 0 ) ? round ( ($total_publ / $total_subm) * 100, 2 ) : '0' ?>%</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="sub_stat" role="tabpanel" tabindex="0">
                    <div class="d-flex gap-1 mt-3">
                            <div class="mb-3">
                                <label for="date_from" class="form-label">Start Date</label>
                                <input type="date" id="date_from" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="date_to" class="form-label">End Date</label>
                                <input type="date" id="date_to" class="form-control">
                            </div>
                        <div class="mb-3 d-flex flex-column justify-content-end">
                            <button class="btn btn-light border border-1" onclick="filter_submission_statistics()">Go</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="sub_stats_table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Type of Publication</th>
                                <th>No. of submissions</th>
                                <th>Rejected from the TeDeEd</th>
                                <th>Percentage Equivalent</th>
                                <th>Passed the TeDeEd</th>
                                <th>Percentage Equivalent</th>
                                <th>Rejected from the Associate Editors</th>
                                <th>Percentage Equivalent</th>
                                <th>Passed the Associate Editors</th>
                                <th>Percentage Equivalent</th>
                                <th>In process</th>
                                <th>Percentage Equivalent</th>
                                <th>Published</th>
                                <th>Percentage Equivalent</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                                $c = 1;
                                $total_subm = 0;
                                $total_teded_rej = 0;
                                $total_teded_pass = 0;
                                $total_assoced_rej = 0;
                                $total_assoced_pass = 0;
                                $total_process = 0;
                                $total_publ = 0;
                                
                                foreach ($stat_submission as $row): 
                            
                                    $total_subm += $row->subm_count;
                                    $total_teded_rej += $row->rej_teded_count;
                                    $total_teded_pass += $row->pass_teded_count;
                                    $total_assoced_rej += $row->rej_assoced_count;
                                    $total_assoced_pass += $row->pass_assoced_count;
                                    $total_process += $row->process_count;
                                    $total_publ += $row->publ_count;
                                ?>

                                <tr class="text-center">
                                    <td><?= $row->pub_id ?></td>
                                    <td style="text-align: left !important;"><?= $row->publication_desc ?></td>
                                    <td><?= ($row->subm_count > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $row->subm_count . '</a>' : 0; ?></td>
                                    <td><?= ($row->rej_teded_count > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $row->rej_teded_count . '</a>' : 0; ?></td>
                                    <td><?= ($row->subm_count > 0 ) ? round ( ($row->rej_teded_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                                    <td><?= ($row->pass_teded_count > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $row->pass_teded_count . '</a>' : 0; ?></td>
                                    <td><?= ($row->subm_count > 0 ) ? round ( ($row->pass_teded_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                                    <td><?= ($row->pass_assoced_count > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $row->pass_assoced_count . '</a>' : 0; ?></td>
                                    <td><?= ($row->subm_count > 0 ) ? round ( ($row->pass_assoced_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                                    <td><?= ($row->rej_assoced_count > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $row->rej_assoced_count . '</a>' : 0; ?></td>
                                    <td><?= ($row->subm_count > 0 ) ? round ( ($row->rej_assoced_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                                    <td><?= ($row->process_count > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $row->process_count . '</a>' : 0; ?></td>
                                    <td><?= ($row->subm_count > 0 ) ? round ( ($row->process_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                                    <td><?= ($row->publ_count > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $row->publ_count . '</a>' : 0; ?></td>
                                    <td><?= ($row->subm_count > 0 ) ? round ( ($row->publ_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                                </tr>
                                <?php endforeach;?>
                
                                <tr class="text-center fw-bold">
                                    <td><?= count($stat_submission) + 1 ?></td>
                                    <td style="text-align: left !important";>Total</td>
                                    <td><?= ($total_subm > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $total_subm . '</a>' : 0; ?></td>
                                    <td><?= ($total_teded_rej > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $total_teded_rej . '</a>' : 0; ?></td>
                                    <td><?= ($total_subm > 0 ) ? round ( ($total_teded_rej / $total_subm) * 100, 2 ) : '0' ?>%</td>
                                    <td><?= ($total_teded_pass > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $total_teded_pass . '</a>' : 0; ?></td>
                                    <td><?= ($total_subm > 0 ) ? round ( ($total_teded_pass / $total_subm) * 100, 2 ) : '0' ?>%</td>
                                    <td><?= ($total_assoced_rej > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $total_assoced_rej . '</a>' : 0; ?></td>
                                    <td><?= ($total_subm > 0 ) ? round ( ($total_assoced_rej / $total_subm) * 100, 2 ) : '0' ?>%</td>
                                    <td><?= ($total_assoced_pass > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $total_assoced_pass . '</a>' : 0; ?></td>
                                    <td><?= ($total_subm > 0 ) ? round ( ($total_assoced_pass / $total_subm) * 100, 2 ) : '0' ?>%</td>
                                    <td><?= ($total_process > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $total_process . '</a>' : 0; ?></td>
                                    <td><?= ($total_subm > 0 ) ? round ( ($total_process / $total_subm) * 100, 2 ) : '0' ?>%</td>
                                    <td><?= ($total_publ > 0) ? '<a href="javascript:void(0);" class="pe-auto text-decoration-none">' . $total_publ . '</a>' : 0; ?></td>
                                    <td><?= ($total_subm > 0 ) ? round ( ($total_publ / $total_subm) * 100, 2 ) : '0' ?>%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="auth_sex" role="tabpanel" tabindex="0">
                    <div class="d-flex gap-1 mt-3">
                            <div class="mb-3">
                                <label for="date_from" class="form-label">Start Date</label>
                                <input type="date" id="date_from" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="date_to" class="form-label">End Date</label>
                                <input type="date" id="date_to" class="form-control">
                            </div>
                        <div class="mb-3 d-flex flex-column justify-content-end">
                            <button class="btn btn-light border border-1" onclick="filter_author_by_sex()">Go</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="auth_by_sex_table">
                            <thead>
                            <tr>
                                <th>Type of Author</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Primary Author</td>
                                <?php $total_auth = 0; foreach ($stat_author_by_sex['authors'] as $row): ?>
                                    <?php $total_auth += $row->total;?>
                                    <td><?= $row->total ?></td>
                                <?php endforeach;?>
                                <td><?= $total_auth ?></td>
                            </tr>
                            <tr>
                                <td>Co-Authors</td>
                                <?php $total_coa = 0; foreach ($stat_author_by_sex['coauthors'] as $row): ?>
                                    <?php $total_coa += $row->total;?>
                                    <td><?= $row->total ?></td>
                                <?php endforeach;?>
                                <td><?= $total_coa ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
      </main>