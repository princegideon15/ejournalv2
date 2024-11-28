<div class="container-fluid mt-2 p-4">
	<div class="row justify-content-center">
	  <div class="col-8">
        <div class="border p-5 mb-5 bg-white rounded">
            <h3 class="text-center">HELP US SERVE YOU BETTER!</h3>
            <p>This Client Satsifaction Measurement (CSM) tracks the cusomter experience of government offices.
                Your feedback on your <span class="text-decoration-underline">recently concluded transaction</span>
                will help this office provide a better serive. Personal information shared will be kept confidential
                and you always have the option to not answer this form.
            </p>
            <?= form_open('client/ejournal/submit_arta', ['method' => 'post', 'id' => 'csfArtaForm']) ?>
                <div class="mb-3">
                    <label for="arta_ctype" class="fw-bold pe-2">Client type:</label>

                        <?php foreach ($client_types as $row): ?>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client<?= $row['ctype_value'] ?>" name="arta_ctype" value="<?= $row['ctype_value'] ?>" <?= (set_value('arta_ctype', $this->session->flashdata('arta_ctype')) == $row['ctype_value']) ? 'checked' : '' ?> onclick="checkOnlyOne(this)">
                                <label class="form-check-label" for="client<?= $row['ctype_value'] ?>"><?= $row['ctype_desc'] ?></label>
                            </div>

                        <?php endforeach;?>
                        
                        <div class="text-danger"><?= $this->session->flashdata('csf_arta_validation_errors')['arta_ctype'] ?></div> 
                </div>

                <div class="row">
                    <div class="col col-4">
                        <div class="mb-3 row">
                            <label for="arta_sex" class="col-sm-2 col-form-label fw-bold">Sex:</label>
                            <div class="col-sm-8">
                                <div class="form-check form-check-inline ps-3 mt-2">
                                    <input class="form-check-input" type="checkbox" id="arta_male" name="arta_sex" value="1" <?= (set_value('arta_sex', $this->session->flashdata('arta_sex')) == 1) ? 'checked' : '' ?> onclick="checkOnlyOne(this)">
                                    <label class="form-check-label" for="arta_male">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="arta_female" name="arta_sex" value="2" <?= (set_value('arta_sex', $this->session->flashdata('arta_sex')) == 2) ? 'checked' : '' ?> onclick="checkOnlyOne(this)">
                                    <label class="form-check-label" for="arta_female">Female</label>
                                </div>
                            </div>
                            <div class="text-danger"><?= $this->session->flashdata('csf_arta_validation_errors')['arta_sex'] ?></div>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="mb-3 row">
                            <label for="arta_age" class="col-sm-2 col-form-label fw-bold">Age:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control w-50" id="arta_age" name="arta_age" maxlength="2" value="<?= (set_value('arta_age', $this->session->flashdata('arta_age'))) ?? '' ?>" >
                            </div>
                            <div class="text-danger"><?= $this->session->flashdata('csf_arta_validation_errors')['arta_age'] ?></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col col-6">
                        <div class="mb-3 row">
                            <label for="arta_region" class="col-sm-5 col-form-label fw-bold">Region of residence:</label>
                            <div class="col-sm-7 ps-0">
                                <select class="form-select" name="arta_region" id="arta_region">
                                    <option value="">Select here</option>
                                    <?php $jsonData = json_encode($regions); ?>
                                    <?php $jsonDataDecoded = json_decode($jsonData, true); ?>
                                    <?php foreach ($jsonDataDecoded as $row): ?>
                                    <?php $region_input = set_value('arta_region', $this->session->flashdata('arta_region')); 
                                            $selected = ($row['region_id'] == $region_input) ? 'selected' : ''; ?>
                                    <?= '<option value="' . $row['region_id'] . '" '. $selected . '>' . $row['region_name'] . '</option>' ?>
                                    <?php endforeach;?>
                                </select>


                            </div>
                            <div class="text-danger"><?= $this->session->flashdata('csf_arta_validation_errors')['arta_region'] ?></div>
                        </div>
                    </div>
                    <div class="col col-6">
                        <div class="mb-3 row">
                            <label for="arta_service" class="col-sm-4 col-form-label fw-bold">Service availed:</label>
                            <div class="col-sm-8 pe-0">
                                <input type="text" class="form-control bg-light" name="arta_service" value="Journal Service" readonly>
                            </div>
                            <div class="text-danger"><?= $this->session->flashdata('csf_arta_validation_errors')['arta_service'] ?></div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div>
                    <p>INSTRUCTIONS: <span class="fw-bold">Check mark (&#10004)</span> your answer to the Citizen's Charter (CC) questions.
                    The Citizen's Charter is an official document that reflects the services of a government agancy/office including its requirements,
                    fees, and processing times among others.</p>
                </div>
                <div class="row mb-3">
                    <div class="col col-1 fw-bold">CC1</div>
                    <div class="col col-11">
                        Which of the following best describes your awareness of a CC?
                        <?php foreach ($cc1 as $row): ?>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="<?= $row['c1_value'] ?>" id="cc1_<?= $row['c1_value'] ?>" name="arta_cc1" <?= (set_value('arta_cc1', $this->session->flashdata('arta_cc1')) == $row['c1_value']) ? 'checked' : '' ?> onclick="checkOnlyOne(this)">
                                <label class="form-check-label" for="cc1_<?= $row['c1_value'] ?>">
                                <?= $row['c1_desc'] ?>
                                </label>
                            </div>

                        <?php endforeach;?>
                        <div class="text-danger"><?= $this->session->flashdata('csf_arta_validation_errors')['arta_cc1'] ?></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-1 fw-bold">CC2</div>
                    <div class="col col-11">
                        Which of the following best describes your awareness of a CC?
                        <?php foreach ($cc2 as $row): ?>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="<?= $row['c2_value'] ?>" id="cc2_<?= $row['c2_value'] ?>" name="arta_cc2" <?= (set_value('arta_cc2', $this->session->flashdata('arta_cc2')) == $row['c2_value']) ? 'checked' : '' ?> onclick="checkOnlyOne(this)">
                                <label class="form-check-label" for="cc2_<?= $row['c2_value'] ?>">
                                <?= $row['c2_desc'] ?>
                                </label>
                            </div>
                            
                        <?php endforeach;?>
                        <div class="text-danger"><?= $this->session->flashdata('csf_arta_validation_errors')['arta_cc2'] ?></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-1 fw-bold">CC3</div>
                    <div class="col col-11">
                        Which of the following best describes your awareness of a CC?
                        <?php foreach ($cc3 as $row): ?>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="<?= $row['c3_value'] ?>" id="cc3_<?= $row['c3_value'] ?>" name="arta_cc3" <?= (set_value('arta_cc3', $this->session->flashdata('arta_cc3')) == $row['c3_value']) ? 'checked' : '' ?> onclick="checkOnlyOne(this)">
                                <label class="form-check-label" for="cc3_<?= $row['c3_value'] ?>">
                                <?= $row['c3_desc'] ?>
                                </label>
                            </div>

                        <?php endforeach;?>
                        <div class="text-danger"><?= $this->session->flashdata('csf_arta_validation_errors')['arta_cc3'] ?></div>
                    </div>
                </div>
                <div>
                    <p>INSTRUCTIONS: For SQD 0-8, Please put a <span class="fw-bold">check mark (&#10004)</span> on the column that best corresponds to your answer.</p>
                    <table class="table table-striped table-bordered" id="sqd-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="text-center align-middle">Strongly Disagree</th>
                                <th class="text-center align-middle">Disagree</th>
                                <th class="text-center align-middle">Neither Agree nor Disagree</th>
                                <th class="text-center align-middle">Agree</th>
                                <th class="text-center align-middle">Strongly Agree</th>
                                <th class="text-center align-middle">Not Applicable</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sqd as $index => $row): ?>
                                <?php echo '<tr>
                                        <td><b>SQD '. ($index + 1) .'.</b> ' . $row['sqd_desc'] . '
                                        <div class="text-danger">'. $this->session->flashdata('csf_arta_validation_errors')['arta_sqd' . $row['sqd_value']] .'</div></td>
                                        <td class="text-center align-middle"><input class="form-check-input" type="checkbox" value="5" name="arta_sqd' . $row['sqd_value'] .'" ' . ( (set_value('arta_sqd' . $row['sqd_value'], $this->session->flashdata('arta_sqd' . $row['sqd_value']) == 5)) ? 'checked' : '' ) . ' onclick="checkOnlyOne(this)"></td>
                                        <td class="text-center align-middle"><input class="form-check-input" type="checkbox" value="4" name="arta_sqd' . $row['sqd_value'] .'" ' . ( (set_value('arta_sqd' . $row['sqd_value'], $this->session->flashdata('arta_sqd' . $row['sqd_value']) == 4)) ? 'checked' : '' ) . ' onclick="checkOnlyOne(this)"></td>
                                        <td class="text-center align-middle"><input class="form-check-input" type="checkbox" value="3" name="arta_sqd' . $row['sqd_value'] .'" ' . ( (set_value('arta_sqd' . $row['sqd_value'], $this->session->flashdata('arta_sqd' . $row['sqd_value']) == 3)) ? 'checked' : '' ) . ' onclick="checkOnlyOne(this)"></td>
                                        <td class="text-center align-middle"><input class="form-check-input" type="checkbox" value="2" name="arta_sqd' . $row['sqd_value'] .'" ' . ( (set_value('arta_sqd' . $row['sqd_value'], $this->session->flashdata('arta_sqd' . $row['sqd_value']) == 2)) ? 'checked' : '' ) . ' onclick="checkOnlyOne(this)"></td>
                                        <td class="text-center align-middle"><input class="form-check-input" type="checkbox" value="1" name="arta_sqd' . $row['sqd_value'] .'" ' . ( (set_value('arta_sqd' . $row['sqd_value'], $this->session->flashdata('arta_sqd' . $row['sqd_value']) == 1)) ? 'checked' : '' ) . ' onclick="checkOnlyOne(this)"></td>
                                        <td class="text-center align-middle"><input class="form-check-input" type="checkbox" value="6" name="arta_sqd' . $row['sqd_value'] .'" ' . ( (set_value('arta_sqd' . $row['sqd_value'], $this->session->flashdata('arta_sqd' . $row['sqd_value']) == 6)) ? 'checked' : '' ) . ' onclick="checkOnlyOne(this)"></td>
                                    </tr>'; ?>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <div class="mb-5">
                    <label for="arta_suggestion" class="fw-bold mb-2">Suggestions on how we can further improve our services (optional):</label>
                    <textarea class="form-control" name="arta_suggestion" id="arta_suggestion" rows="5" placeholder="Type your suggestions here..."></textarea>
                </div>
                
                <div class="text-center"><button type="submit" class="btn main-btn w-50">Submit Feedback</button></div>
            </form>
        </div>
      </div>
    </div>
</div>
