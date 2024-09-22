<?php error_reporting(0);?>
<div class="container-fluid" style="margin-top:70px; background-color:#F3F4F6">

	<div class="row">
		<div class="col">
			<img src="<?php echo base_url("assets/images/banner2024.png"); ?>" class="img-fluid w-100"
				alt="Responsive image">
		</div>
	</div>
	<br />
	<div class="row">
		<!-- SIDE NAVIGATION -->
		<div class="col-2 mb-5">
			<div class="list-group sticky" id="list_group_menu">
				<li class="list-group-item font-weight-bold bg-light"><span class="oi oi-book"></span> JOURNALS</li>
				<?php if ($journals != null) {
				$c = 0;foreach ($journals as $row): ?>
				<?php $c++;?>
				<?php echo '<a href="'.base_url('/client/ejournal/get_issues/'.$row->jor_volume.'').'" class="list-group-item  list-group-item-action">Volume ' . $row->jor_volume . ', ' . $row->jor_year . '<span class="oi oi-chevron-right float-right mt-1" style="font-size:10px"></span></a>'; ?>
				<?php endforeach;}?>
			</div>
		</div>
		<div class="col-10">
			<div class="tab-content" id="nav-tabContent">

				<!-- GUIDELINES -->
				<div class="tab-pane fade show active " id="home" role="tabpanel" aria-labelledby="home">
					<div class="jumbotron shadow p-5 mb-5 bg-white rounded">
						<div class="row">
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
						</div>
						<div class="row mt-5">
							<div class="col">
								 <h2>SUBMISSION OF MANUSCRIPTS</h2>
									<ol>
										<li>Only NRCP members can submit manuscript.</li>
										<li>
										<strong>ONLINE SUBMISSION</strong>
										<ol type="a">
											<li>Login to your SKMS account at <a href="https://skms.nrcp.dost.gov.ph">https://skms.nrcp.dost.gov.ph</a></li>
											<li>Click Journal Publications</li>
											<li>Click Upload manuscript</li>
											<li>Provide/upload the necessary data/information</li>
											<li>Click proceed</li>
										</ol>
										</li>
										<li>The manuscript shall be submitted both in Microsoft Word and PDF document format.</li>
										<li>The text and format of the manuscript shall adhere to the style and bibliographic requirements outlined in the Instructions to Authors.</li>
										<li>
										The submission of the manuscript for publication in the NRCP Research Journal implies that it has not been published and/or has not been considered for publication by other journals.
										</li>
										<li>
										Once the manuscript is accepted for publication, the authors shall agree that the same will no longer be submitted elsewhere.
										</li>
									</ol>

									<h2>GENERAL INSTRUCTIONS TO AUTHORS</h2>
									<ol>
										<li>
										Submit manuscripts in word and pdf format as required by the online form.
										This includes an anonymized copy of the manuscript for distribution to referees (pdf format).
										</li>
										<li>
										<h6>Title page shall include:</h6>
										<ul>
											<li>TITLE of the Manuscript</li>
											<li>AUTHORS, full names, institutional and email addresses of the authors. An asterisk shall be affixed to corresponding author’s name</li>
											<li>KEYWORDS, three to ten keywords shall also be included, representing the main content of the manuscript.</li>
											<li>ABSTRACT, a single paragraph with at least 150-words summarizing the content of the manuscript. It shall not contain bibliographic citations unless otherwise fully specified.</li>
										</ul>
										</li>
										<li><h6>FORMAT</h6>
											<table class="table table-bordered">
												<tr>
												<th>Number of Words</th>
												<td>Minimum of 5000 and maximum 6000 words</td>
												</tr>
												<tr>
												<th>Title of Manuscript</th>
												<td>
													<ul>
													<li>Alignment: Center</li>
													<li>Font Style: Cambria, Bold, All Caps</li>
													<li>Font Size: 14</li>
													</ul>
												</td>
												</tr>
												<tr>
												<th>Author’s Name</th>
												<td>
													<ul>
													<li>Alignment: Center</li>
													<li>Font Style: Cambria, Bold, Sentence Case</li>
													<li>Font Size: 12</li>
													<li>Line Spacing: 1.5</li>
													</ul>
												</td>
												</tr>
												<tr>
												<th>Affiliations and Addresses</th>
												<td>
													<ul>
													<li>Alignment: Center</li>
													<li>Font Style: Cambria, Regular, Sentence Case</li>
													<li>Font Size: 9</li>
													<li>Line Spacing: 1.5</li>
													</ul>
												</td>
												</tr>
												<tr>
												<th>Section Headings</th>
												<td>
													<ul>
													<li>Alignment: Center</li>
													<li>Font Style: Cambria, Bold, All Caps</li>
													<li>Font Size: 11</li>
													<li>Line Spacing: 1.5</li>
													</ul>
												</td>
												</tr>
												<tr>
												<th>Subheadings</th>
												<td>
													<ul>
													<li>Alignment: Flash Left</li>
													<li>Font Style: Cambria, Bold, Sentence Case</li>
													<li>Font Size: 11</li>
													<li>Line Spacing: 1.5</li>
													</ul>
												</td>
												</tr>
												<tr>
												<th>Manuscript</th>
												<td>
													<ul>
													<li>Alignment: Justified, No Indention</li>
													<li>Font Style: Cambria</li>
													<li>Font Size: 11</li>
													<li>Line Spacing: 1.5, one blank line separating paragraphs</li>
													</ul>
												</td>
												</tr>
												<tr>
												<th>PAPER SIZE</th>
												<td>7 X 10 inches / 177 x 254 mm</td>
												</tr>
												<tr>
												<th>LEFT MARGIN</th>
												<td>1.0 inch / 2.54 cm</td>
												</tr>
												<tr>
												<th>RIGHT MARGIN</th>
												<td>0.5 inch / 1.27 cm</td>
												</tr>
												<tr>
												<th>TOP/BOTTOM MARGIN</th>
												<td>0.787 inch / 2 cm</td>
												</tr>
											</table>
											</li>
											<li>
											Tables and figures shall follow the APA 7th Edition and must be properly captioned, numbered, and placed at the center of the pages.
											The titles of the tables and figures shall have a font size 10, bold, and in sentence case.
											</li>
											<li>
											The references shall also follow the APA Style, 7th Edition with a font size of 10, regular, and in sentence case.
											</li>
											<li>
											The accomplished Author Agreement Form is a required attachment of the submitted manuscript.
											</li>
											<li>
											The Acknowledgements shall be placed under a separate heading before the References.
											</li>
											<li>
											For other details regarding manuscript submission, please email: <a href="mailto:ejournal@nrcp.dost.gov.ph">ejournal@nrcp.dost.gov.ph</a>
											</li>
											<li>
											To track the status of submissions, log in to SKMS Account.
											</li>
											<li>
											For studies with multiple authors, accounting for material contributions is needed. (e.g., A, did the literature review; B, undertook the analysis, etc.)
											</li>
									</ol>
								<!-- <a role="button" class="btn btn-sm btn-secondary"
									href="<?php echo base_url('assets/uploads/DO_NOT_DELETE_guidelines.pdf'); ?>"
									download="NRCP Journal Publication Guidelines 2023.pdf"><i
										class="oi oi-data-transfer-download"></i> Download</a> -->
							</div>
						</div>

						<div class="mt-5">
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
