<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid pt-3">
        <h3 class="fw-bold">Statistics</h3>
        <div class="card border border-dark">
          <div class="card-body">

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
                            <button class="btn btn-primary" onclick="filter_submission_summary()">Go</button>
                        </div>
                        <div class="mb-3 d-flex flex-column justify-content-end">
                            <button class="btn btn-light" onclick="filter_submission_summary('clear')">Clear</button>
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

                            foreach ($stat_summary as $row): 
                            
                            ?>

                            <tr class="text-center">
                                <td ><?= $row->pub_id ?></td>
                                <td style="text-align: left !important;"><?= $row->publication_desc ?></td>
                                <td><?= ($row->subm_count > 0) ? '<a href="javascript:void(0);" onclick="view_stats_info(`#sub_sum`,'. $row->pub_id .',null)" class="pe-auto text-decoration-none">' . $row->subm_count . '</a>' : 0; ?></td>
                                <td><?= ($row->rej_count > 0) ? '<a href="javascript:void(0);" onclick="view_stats_info(`#sub_sum`,'. $row->pub_id .',14)" class="pe-auto text-decoration-none">' . $row->rej_count . '</a>' : 0; ?></td>
                                <td><?= ($row->subm_count > 0 && $row->rej_count > 0) ? number_format ( ($row->rej_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                                <td><?= ($row->pass_count > 0) ? '<a href="javascript:void(0);" onclick="view_stats_info(`#sub_sum`,'. $row->pub_id .',12)" class="pe-auto text-decoration-none">' . $row->pass_count . '</a>' : 0; ?></td>
                                <td><?= ($row->subm_count > 0 && $row->pass_count > 0) ? number_format ( ($row->pass_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                                <td><?= ($row->process_count > 0) ? '<a href="javascript:void(0);" onclick="view_stats_info(`#sub_sum`,'. $row->pub_id .',1)" class="pe-auto text-decoration-none">' . $row->process_count . '</a>' : 0; ?></td>
                                <td><?= ($row->subm_count > 0 && $row->process_count > 0) ? number_format ( ($row->process_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                                <td><?= ($row->publ_count > 0) ? '<a href="javascript:void(0);" onclick="view_stats_info(`#sub_sum`,'. $row->pub_id .',16)" class="pe-auto text-decoration-none">' . $row->publ_count . '</a>' : 0; ?></td>
                                <td><?= ($row->subm_count > 0 && $row->publ_count > 0) ? number_format ( ($row->publ_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                            </tr>

                            <?php endforeach;?>

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
                            <button class="btn btn-primary" onclick="filter_submission_statistics()">Go</button>
                        </div>
                        <div class="mb-3 d-flex flex-column justify-content-end">
                            <button class="btn btn-light" onclick="filter_submission_statistics('clear')">Clear</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="sub_stats_table" width="100%">
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
                                foreach ($stat_submission as $row): 
                        
                                ?>

                                <tr class="text-center">
                                    <td><?= $row->pub_id ?></td>
                                    <td style="text-align: left !important;"><?= $row->publication_desc ?></td>
                                    <td><?= ($row->subm_count > 0) ? '<a href="javascript:void(0);" onclick="view_stats_info(`#sub_stat`,'. $row->pub_id .',null)" class="pe-auto text-decoration-none">' . $row->subm_count . '</a>' : 0; ?></td>
                                    <td><?= ($row->rej_teded_count > 0) ? '<a href="javascript:void(0);" onclick="view_stats_info(`#sub_stat`,'. $row->pub_id .',2,`technical`)" class="pe-auto text-decoration-none">' . $row->rej_teded_count . '</a>' : 0; ?></td>
                                    <td><?= ($row->subm_count > 0 && $row->rej_teded_count > 0) ? number_format ( ($row->rej_teded_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                                    <td><?= ($row->pass_teded_count > 0) ? '<a href="javascript:void(0);" onclick="view_stats_info(`#sub_stat`,'. $row->pub_id .',1,`technical`)" class="pe-auto text-decoration-none">' . $row->pass_teded_count . '</a>' : 0; ?></td>
                                    <td><?= ($row->subm_count > 0 && $row->pass_teded_count > 0) ? number_format ( ($row->pass_teded_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                                    <td><?= ($row->pass_assoced_count > 0) ? '<a href="javascript:void(0);" onclick="view_stats_info(`#sub_stat`,'. $row->pub_id .',15,`associate`)" class="pe-auto text-decoration-none">' . $row->pass_assoced_count . '</a>' : 0; ?></td>
                                    <td><?= ($row->subm_count > 0 && $row->pass_assoced_count > 0) ? number_format ( ($row->pass_assoced_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                                    <td><?= ($row->rej_assoced_count > 0) ? '<a href="javascript:void(0);" onclick="view_stats_info(`#sub_stat`,'. $row->pub_id .',14,`associate`)" class="pe-auto text-decoration-none">' . $row->rej_assoced_count . '</a>' : 0; ?></td>
                                    <td><?= ($row->subm_count > 0 && $row->rej_assoced_count > 0) ? number_format ( ($row->rej_assoced_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                                    <td><?= ($row->process_count > 0) ? '<a href="javascript:void(0);" onclick="view_stats_info(`#sub_stat`,'. $row->pub_id .',1))" class="pe-auto text-decoration-none">' . $row->process_count . '</a>' : 0; ?></td>
                                    <td><?= ($row->subm_count > 0 && $row->process_count > 0) ? number_format ( ($row->process_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                                    <td><?= ($row->publ_count > 0) ? '<a href="javascript:void(0);" onclick="view_stats_info(`#sub_stat`,'. $row->pub_id .',16))" class="pe-auto text-decoration-none">' . $row->publ_count . '</a>' : 0; ?></td>
                                    <td><?= ($row->subm_count > 0 && $row->publ_count > 0) ? number_format ( ($row->publ_count / $row->subm_count) * 100, 2 ) : '0' ?>%</td>
                                </tr>
                                <?php endforeach;?>
                
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
                            <button class="btn btn-primary" onclick="filter_author_by_sex()">Go</button>
                        </div>
                        <div class="mb-3 d-flex flex-column justify-content-end">
                            <button class="btn btn-light" onclick="filter_author_by_sex('clear')">Clear</button>
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