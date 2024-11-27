<div class="container-fluid  mt-2 p-4">
	<div class="row justify-content-center">
	  <div class="col-8">
        <div class="border p-5 mb-5 bg-white rounded">
                <h3 class="text-center">HELP US SERVE YOU BETTER!</h3>
                <p>This Client Satsifaction Measurement (CSM) tracks the cusomter experience of government offices.
                    Your feedback on your <span class="text-decoration-underline">recently concluded transaction</span>
                    will help this office provide a better serive. Personal information shared will be kept confidential
                    and you always have the option to not answer this form.
                </p>
                <form id="csf_form" name="csf_form" action="<?php echo base_url('/client/ejournal/submit_arta');?>" method="post" accept-charset="utf-8">
                    
                    <div class="mb-3">
                        <label for="inputPassword" class="fw-bold pe-2">Client type:</label>

                            <?php foreach ($client_types as $row): ?>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="<?= $row['ctype_desc'] ?>">
                                    <label class="form-check-label" for="inlineCheckbox1"><?= $row['ctype_desc'] ?></label>
                                </div>

                            <?php endforeach;?>
                    </div>

                    <div class="row">
                        <div class="col col-4">
                            <div class="mb-3 row">
                                <label for="inputPassword" class="col-sm-2 col-form-label fw-bold">Sex:</label>
                                <div class="col-sm-8">
                                    <div class="form-check form-check-inline ps-3 mt-2">
                                        <input class="form-check-input" type="checkbox" id="arta_male" name="arta_ex" value="1">
                                        <label class="form-check-label" for="arta_male">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="arta_female" name="arta_ex" value="2">
                                        <label class="form-check-label" for="arta_female">Female</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col col-4">
                            <div class="mb-3 row">
                                <label for="inputPassword" class="col-sm-2 col-form-label fw-bold">Age:</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control w-50" min="0">
                                </input>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-6">
                            <div class="mb-3 row">
                                <label for="inputPassword" class="col-sm-5 col-form-label fw-bold">Region of residence:</label>
                                <div class="col-sm-7 ps-0">
                                    <select class="form-select" name="arta_region" id="arta_region">
                                        <option value="">Select here</option>
                                        <?php $jsonData = json_encode($regions); ?>
                                        <?php $jsonDataDecoded = json_decode($jsonData, true); ?>
                                        <?php foreach ($jsonDataDecoded as $row): ?>
                                        <?= '<option value="' . $row['region_id'] . '">' . $row['region_name'] . '</option>' ?>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col col-6">
                            <div class="mb-3 row">
                                <label for="inputPassword" class="col-sm-4 col-form-label fw-bold">Service availed:</label>
                                <div class="col-sm-8 pe-0">
                                    <input type="text" class="form-control bg-light" value="Journal Service" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <p>INSTUCTIONS: <span class="fw-bold">Check mark (&#10004)</span> your answer to the Citizen's Charter (CC) questions.
                        The Citizen's Charter is an official document that reflects the services of a government agancy/office including its requirements,
                        fees, and processing times among others.</p>
                    </div>
                    <div class="row mb-3">
                        <div class="col col-1 fw-bold">CC1</div>
                        <div class="col col-11">
                            Which of the following best describes your awareness of a CC?
                            <?php foreach ($cc1 as $row): ?>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value=" <?= $row['c1_value'] ?>" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                    <?= $row['c1_desc'] ?>
                                    </label>
                                </div>

                            <?php endforeach;?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col col-1 fw-bold">CC2</div>
                        <div class="col col-11">
                            Which of the following best describes your awareness of a CC?
                            <?php foreach ($cc2 as $row): ?>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value=" <?= $row['c2_value'] ?>" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                    <?= $row['c2_desc'] ?>
                                    </label>
                                </div>
                               
                            <?php endforeach;?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col col-1 fw-bold">CC3</div>
                        <div class="col col-11">
                            Which of the following best describes your awareness of a CC?
                            <?php foreach ($cc3 as $row): ?>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value=" <?= $row['c3_value'] ?>" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                    <?= $row['c3_desc'] ?>
                                    </label>
                                </div>

                            <?php endforeach;?>
                        </div>
                    </div>
                    <div>
                        <p>INSTRUCTIONS: For SQD 0-8, please pur a <span class="fw-bold">check mark (&#10004)</span> on the column that best corresponds to your answer.</p>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center align-middle">Strongly Disagree</th>
                                    <th class="text-center align-middle">Disagree</th>
                                    <th class="text-center align-middle">Neither Agree nor Disagree</th>
                                    <th class="text-center align-middle">Agree</th>
                                    <th class="text-center align-middle">Stronglee Agree</th>
                                    <th class="text-center align-middle">Not Applicable</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php foreach ($sqd as $index => $row): ?>
                                    <?= '<tr><td><b>SQD ' . ($index + 1) . '.</b> ' . $row['sqd_desc'] . '</td><td><input class="form-check-input text-center" type="checkbox" value="5"><td><input class="form-check-input" type="checkbox" value="4"></td><td><input class="form-check-input text-center align-middle" type="checkbox" value="3"></td><td><input class="form-check-input" type="checkbox" value="2"></td><td><input class="form-check-input" type="checkbox" value="1"></td><td><input class="form-check-input" type="checkbox" value="6"></td></tr>' ?>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mb-5">
                        <label for="arta_suggestion" class="fw-bold mb-2">Suggestions on how we can further improve our services (optional):</label>
                        <textarea class="form-control" name="arta_suggestion" id="arta_suggestion"></textarea>
                    </div>
                    
                    <div class="text-center"><button type="submit" class="btn main-btn w-50">Submit Feedback</button></div>
                </form>

        </div>
      </div>
    </div>
</div>
