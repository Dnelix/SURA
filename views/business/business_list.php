	<div class="card card-flush mt-6 mt-xl-9"> 
		<!--begin::Card header-->
		<div class="card-header mt-5">
			<div class="card-title flex-column">
				<h3 class="fw-bolder mb-1">Business Directory (<?= $bizCount; ?>)</h3>
				<div class="fs-6 text-gray-400">Select a business to view details </div>
			</div>
			<div class="card-toolbar my-1">
				<div class="d-flex align-items-center position-relative my-1">
					<span class="svg-icon svg-icon-3 position-absolute ms-3">
						<i class="fa fa-search"></i>
					</span>
					<input type="text" id="data_table_search" class="form-control form-control-solid form-select-sm w-300px ps-9" placeholder="Search Business" />
				</div>
			</div>
		</div>
		<!--end::Card header-->
		<!--begin::Card body-->
		<div class="card-body pt-0">
			<div class="table-responsive">
				<table id="data_table" class="table table-row-bordered table-row-dashed gy-4 align-middle fw-bolder">
					<thead class="fs-7 text-gray-400 text-uppercase">
						<tr>
							<th class="min-w-200px">Customer</th>
							<th class="min-w-100px">Phone Number</th>
							<th class="min-w-150px"><?= $alt_job.'s'; ?></th>
							<th class="min-w-150px text-end">Actions</th>
						</tr>
					</thead>

					<tbody class="fs-6">
						<?php
							if($bizCount >= 1){
								//$bizList = (array)$bizList;
						
								foreach ($bizList as $business){
									$bid = $business->id;
						?>

						<tr>
							<td>
								<div class="d-flex align-items-center">
									<div class="me-5 position-relative">
										<?= showCustomerIcon($bid, getInitials($business->name)); ?>
									</div>
									<div class="d-flex flex-column justify-content-center">
										<a href="business?bid=<?= $bid; ?>" class="fs-6 text-gray-800 text-hover-primary"><?= $business->name; ?></a>
										<div class="fw-bold text-gray-400"><?= $business->email; ?></div>
									</div>
								</div>
							</td>
							<td><?= $business->phone; ?></td>
							<td>
								<div class="d-flex flex-column w-100 me-2">
                                    <div class="d-flex flex-stack mb-2">
                                        <span class="text-muted me-2 fs-7 fw-bold"><?= '0 '.$alt_job.'s'; ?></span>
                                    </div>
                                    <div class="progress h-6px w-100">
										<div class="progress-bar bg-primary" role="progressbar" style="width: <?= progressPosition(0); ?>%" aria-valuenow="<?= progressPosition(0); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
							</td>
							<td class="text-end">
								<div class="d-flex justify-content-end flex-shrink-0">
                                    <a href="business?bid=<?= $bid; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="View details">
                                        <span class="svg-icon svg-icon-3"><i class="fa fa-eye"></i></span>
                                    </a>
                                </div>
							</td>
						</tr>

						<?php
								} 
							} else {
								echo '<tr><td> No business record found </td></tr>';
							}
						?>
						
					</tbody>
				</table>
			</div>
		</div>
		<!--end::Card body-->
	</div>