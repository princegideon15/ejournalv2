<?php error_reporting(0);?>

<div class="container-fluid mt-3 p-4">
    <div class="row pt-3">
        <div class="col col-3 p-3">
            <a class="main-link text-decoration-underline" href="<?=base_url('/client/ejournal/articles')?>">All Articles</a>
        </div>
        <div class="col col-7 p-3">
            <h3>Volumes</h3>
            
            <ul class="list-unstyled">
                <?php foreach($volumes as $key => $row):?>
                    <li><span class="fw-bold h6 text-decoration-underline"><?=$key?></span>
                    
                    <ul class="list-unstyled mb-3">
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
                    </ul>
                
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col col-2 p-3">
            <?php $this->load->view('common/side_panel');?>
        </div>
    </div>

    <div class="mt-5">   
    <h6 class="fw-bold">Open Access</h6>    
        <a rel="license" href="http://creativecommons.org/licenses/by/4.0/" target="_blank">
            <img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by/4.0/88x31.png" />
        </a>
        <p>This work is licensed under a <a class="text-dark" rel="license" href="http://creativecommons.org/licenses/by/4.0/" target="_blank">Creative Commons
            Attribution 4.0 International License</a>.
        </p>
    </div>

    <!-- FEEDBACK MODAL -->
    <div class="modal fade" id="feedbackModal" data-backdrop="static" data-keyboard="false" style="z-index: 1051 !important;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-0">
                    <p><span class="modal-title font-weight-bold h3">Your feedback</span><br />
                        <small>We would like your feedback to improve our system.</small>
                    </p>
                    <!-- <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
                </div>
                <div class="modal-body p-4">
                    <form id="feedback_form">
                        <input type="hidden" id="fb_usr_id" name="fb_usr_id">
                        <input type="hidden" id="fb_source" name="fb_source">
                        <div class="feedback text-center">
                            <p class="font-weight-bold h4 text-center">User Interface</p>
                            <div class="feedback-container ui-container">
                                <div class="feedback-item">
                                    <label for="ui-1" data-toggle="tooltip" data-placement="bottom" title="Sad">
                                        <input class="radio" type="radio" name="fb_rate_ui" id="ui-1" value="1">
                                        <span>🙁</span>
                                    </label>
                                </div>

                                <div class="feedback-item">
                                    <label for="ui-2" data-toggle="tooltip" data-placement="bottom" title="Neutral">
                                        <input class="radio" type="radio" name="fb_rate_ui" id="ui-2" value="2">
                                        <span>😶</span>
                                    </label>
                                </div>

                                <div class="feedback-item">
                                    <label for="ui-3" data-toggle="tooltip" data-placement="bottom" title="Happy">
                                        <input class="radio" type="radio" name="fb_rate_ui" id="ui-3" value="3">
                                        <span>🙂</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="fb_suggest_ui"></label>
                                <textarea class="form-control" name="fb_suggest_ui" id="fb_suggest_ui" rows="3"
                                    placeholder="Type your suggestions here"></textarea>
                            </div>

                            <hr />

                            <p class="font-weight-bold h4 text-center">User Experience</p>
                            <div class="feedback-container ux-container">
                                <div class="feedback-item">
                                    <label for="ux-1" data-toggle="tooltip" data-placement="bottom" title="Sad">
                                        <input class="radio" type="radio" name="fb_rate_ux" id="ux-1" value="1">
                                        <span>🙁</span>
                                    </label>
                                </div>

                                <div class="feedback-item">
                                    <label for="ux-2" data-toggle="tooltip" data-placement="bottom" title="Nuetral">
                                        <input class="radio" type="radio" name="fb_rate_ux" id="ux-2" value="2">
                                        <span>😶</span>
                                    </label>
                                </div>

                                <div class="feedback-item">
                                    <label for="ux-3" data-toggle="tooltip" data-placement="bottom" title="Happy">
                                        <input class="radio" type="radio" name="fb_rate_ux" id="ux-3" value="3">
                                        <span>🙂</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="fb_suggest_ux"></label>
                                <textarea class="form-control" name="fb_suggest_ux" id="fb_suggest_ux" rows="3"
                                    placeholder="Type your suggestions here"></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary  w-100">Submit Feedback</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>