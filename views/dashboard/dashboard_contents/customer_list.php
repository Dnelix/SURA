<?php
    // replace this with customers later
    $contents = retrieveDataFrom($c_website.'controllers/users.php');
    $userCount = $contents->data->count;
    $userList = $contents->data->userlist;
?>
    <div class="card card-xl-stretch mb-xl-8">
        <!--begin::Header-->
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">My Customers</span>
                <span class="text-muted mt-1 fw-bold fs-7">You have registered <?= $userCount; ?> customers</span>
            </h3>
            <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Click to add a user">
                <a href="customers" class="btn btn-sm btn-light btn-active-primary" >
                <i class="fa fa-eye"></i> See all </a>
            </div>
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                    <thead>
                        <tr class="fw-bolder text-muted">
                            <th class="min-w-200px">Customer</th>
                            <th class="min-w-150px">Contact details</th>
                            <th class="min-w-150px"><?= $alt_job.'s'; ?></th>
                            <th class="min-w-150px text-end">Actions</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php 
                        foreach($userList as $user) { 
                            $name = ($user->fullname !== null) ? $user->fullname : $user->username;
                            $initials = getInitials($name);
                        ?>

                        <tr>
                            <td>
								<div class="d-flex align-items-center">
									<div class="me-5 position-relative">
										<?= showCustomerIcon($user->id, $initials, $user->active); ?>
									</div>
									<div class="d-flex flex-column justify-content-center">
										<a href="#" class="fs-6 text-gray-800 text-hover-primary"><?= $name; ?></a>
										<div class="fw-bold text-gray-400"><?= $user->email; ?></div>
									</div>
								</div>
							</td>
							<td><?= $user->phone; ?></td>
							<td>
								<div class="d-flex flex-column w-100 me-2">
                                    <div class="d-flex flex-stack mb-2">
                                        <span class="text-muted me-2 fs-7 fw-bold">5 <?= $alt_job.'s'; ?></span>
                                    </div>
                                    <div class="progress h-6px w-100">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
							</td>
							<td class="text-end">
								<div class="d-flex justify-content-end flex-shrink-0">
                                    <a href="customers?cid=<?= $user->id; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="View details">
                                        <span class="svg-icon svg-icon-3"><i class="fa fa-eye"></i></span>
                                    </a>
                                    <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Start new project">
                                        <span class="svg-icon svg-icon-3"><i class="fa fa-plus"></i></span>
                                    </a>
                                </div>
							</td>
                        </tr>

                        <?php } ?>

                    </tbody>
                </table>
                <?= sendEmail('general', 'Test mail', 'domainbuy101@gmail.com', 'Fleix', 'Just Testing this', 'Marcus'); ?>
                <br/>
                <button onClick="sendMail('welcome', 'Welcome to our world', 'domainbuy101@gmail.com')"> click to send mail</button>
            </div>
        </div>
    </div>