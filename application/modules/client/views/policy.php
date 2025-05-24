<?php error_reporting(0);?>
<div class="container-fluid mt-2 p-4">
    <div class="row">
        <div class="col col-3 p-3">
            <a class="btn btn-link main-link" href="<?=base_url('/client/ejournal/articles')?>">View all articles</a>
		</div>
		<div class="col col-7 p-3">
					<!-- <div class="border p-0 mb-5 bg-white rounded"> -->
					<div class="mb-5">
						<div class="row">
                <div class="col-8 quick-links-content">
                  <?php echo $editorial_policy[0]['ep_content'];?>
                  <!-- <embed WMODE="transparent" src="<?php echo base_url('assets/uploads/editorial_policy/' . $editorial_policy . '.pdf#toolbar=0&navpanes=0&scrollbar=0'); ?>" type="application/pdf" width="100%" height="1000px"> -->
                </div>


							<div class="col-4">
								<!-- Table of Contents -->
                 <div class="sticky-top">
								 <p>Quick Links</p>
                  <ul class="quick-links">
                    <li><a class="text-darkk">Aim and Scope</a></li>
                    <li><a class="text-darkk">Types of Publications</a></li>
                    <li><a class="text-darkk">Editorial Criteria</a></li>
                    <li><a class="text-darkk">Editorial Process</a></li>
                    <li><a class="text-darkk">Detailed Peer Review Process</a></li>
                    <ul>
                      <li><a class="text-darkk">Initial Assessment</a></li>
                      <li><a class="text-darkk">Delegating to an Associate Editor</a></li>
                      <li><a class="text-darkk">External Review [Double Blind]</a></li>
                    </ul>
                    <li><a class="text-darkk">Editorial Review</a></li>
                    <li><a class="text-darkk">Open Access Policy</a></li>
                    <li><a class="text-darkk">Copyright Policy</a></li>
                    <li><a class="text-darkk">Retraction Policy</a></li>
                    <li><a class="text-darkk">Digital Archiving and Preservation Policy</a></li>
                    <ul>
                      <li><a class="text-darkk">Website (electronic) Archiving</a></li>
                      <li><a class="text-darkk">Abstracting/Indexing Services</a></li>
                      <li><a class="text-darkk">Self-archiving</a></li>
                    </ul>
                    <li><a class="text-darkk">Policy on Handling Complaints</a></li>
                    <li><a class="text-darkk">Use of Human Subjects in Research Policy</a></li>
                    <li><a class="text-darkk">Conflicts of interest / Competing interests Policy</a></li>
                    <li><a class="text-darkk">Publication Ethics and Malpractice</a></li>
                    <li><a class="text-darkk">Authorship and Co-authorship</a></li>
                    <li><a class="text-darkk">Originality and Plagiarism</a></li>
                    <li><a class="text-darkk">Ethical Oversight</a></li>
                    <li><a class="text-darkk">Policy on the Use of Generative AI</a></li>
                  </ul>
                 </div>

							</div>
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
		<div class="col col-2 p-3">
			<?php $this->load->view('common/side_panel');?>
		</div>
	</div>
    </div>
</div>


                <script>
                  
                  // Add IDs to the quick links
                  $('.quick-links li a').each(function() {

                    let $heading = $(this);
                    let text = $heading.text().trim();

                    // Generate a safe ID
                    let id = text.toLowerCase()
                                .replace(/[^a-z0-9\s]/g, '')
                                .replace(/\s+/g, '-');

                    // Set the ID and add a class
                    $heading.attr('href', '#' + id);
                  });

                  // Add IDs to the headings in the editorial policy content
                  $('.quick-links-content h5.fw-bold').each(function() {

                    let $heading = $(this);
                    let text = $heading.text().trim();

                    // Generate a safe ID
                    let id = text.toLowerCase()
                                .replace(/[^a-z0-9\s]/g, '')
                                .replace(/\s+/g, '-');

                    // Set the ID and add a class
                    $heading.attr('id', id).addClass('section-anchor');

                    // Create the copy link
                     let $link = $('<a>')
                    .attr('href', `#${id}`)
                    .addClass('copy-link ml-2') // `ml-2` for spacing
                    .attr('title', 'Copy link')
                    .html('<i class="fa fa-link fs-6 ms-2"></i>');

                    // Append it to the heading
                    $heading.append($link);



                  });

                  // Add IDs to the headings under a list in the editorial policy content
                  $('.quick-links-content li.h5.fw-bold').each(function() {

                    let $heading = $(this);
                    let text = $heading.text().trim();

                    // Generate a safe ID
                    let id = text.toLowerCase()
                                .replace(/[^a-z0-9\s]/g, '')
                                .replace(/\s+/g, '-');

                    // Set the ID and add a class
                    $heading.attr('id', id).addClass('section-anchor');

                    // Create the copy link
                     let $link = $('<a>')
                    .attr('href', `#${id}`)
                    .addClass('copy-link ml-2') // `ml-2` for spacing
                    .attr('title', 'Copy link')
                    .html('<i class="fa fa-link fs-6 ms-2"></i>');

                    // Append it to the heading
                    $heading.append($link);



                  });

                  // Smooth scrolling for quick links
                  $('.scroll-link').on('click', function(e) {
                    e.preventDefault();
                    const target = $(this).attr('href');
                    $('html, body').animate({
                      scrollTop: $(target).offset().top
                    }, 500);
                  });
                  
                  // Click-to-copy handler
                  $(document).on('click', '.copy-link', function(e) {
                    e.preventDefault();
                    const anchor = $(this).attr('href');
                    const fullUrl = window.location.origin + window.location.pathname + anchor;

                    navigator.clipboard.writeText(fullUrl).then(() => {
                      console.log('Link copied to clipboard!');
                    }).catch(err => {
                      console.error('Failed to copy: ', err);
                    });
                  });

              </script>
              
<!-- Submit Feedback Floater -->
<!-- <div class="fixed-bottom text-right pr-5">
	<button type="button" class="btn btn-sm btn-warning font-weight-bold px-3 py-2" data-target="#feedbackModal"
		data-toggle="modal">
		<span class="oi oi-comment-square mr-1"></span>
		Submit Feedback
	</button>
</div> -->
