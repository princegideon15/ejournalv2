<div class="card bg-light border-0">
                <div class="card-body">
                    <h6 class="card-title fw-bold d-block">Journal Information</h6>
                    <a href="<?php echo base_url('/client/ejournal/editorial');?>"
                        class="text-dark text-decoration-underline">Editorial Board</a>
                    <a href="#" class="text-dark text-decoration-underline">International Editorial Board</a>
                </div>
            </div>

            <div class="card bg-light border-0 mt-3">
                <div class="card-body">
                    <h6 class="card-title fw-bold">Annual Journal Metrics</h6>
                    <h6 class="card-title fw-bold mt-3 mb-0">Citation Impact <?php echo date('Y');?></h6>
                    <h6 class="card-title fw-bold mt-2 mb-0">Speed <?php echo date('Y');?></h6>
                    <h6 class="card-title fw-bold mt-2 mb-0">Usage <?php echo date('Y');?></h6>
                    <div>Downloads: <?php echo $downloads;?></div>
                    <div>Altmetric mentions: <?php echo $citations;?></div>

                </div>
            </div>