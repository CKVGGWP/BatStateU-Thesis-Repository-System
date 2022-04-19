<main id="main" class="main">

    <div class="pagetitle">
        <h1>Admin Management</h1>
        <nav>
            <?php include('views/dashboard/breadcrumbs.php'); ?>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title">Administrators</h5>
                            <button type="button" class="h-25 btn btn-primary btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#createAdminAccount">Add Account</button>

                        </div>
                        <p>List of Registered Admin Accounts on the System</p>

                        <!-- Table with stripped rows -->
                        <table class="table table-hover dt-responsive nowrap text-center" id="accountManagementTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Campus</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Create a modal for edit accounts -->
    <div class="modal fade" id="createAdminAccount" tabindex="-1" role="dialog" aria-labelledby="createAdminAccount" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAccount">Add Admin Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createAdmin" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="createID">ID</label>
                                <input type="text" class="form-control" id="createID" name="createID">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="createEmail">Email Address</label>
                                <input type="email" class="form-control" id="createEmail" name="createEmail">
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="form-group col-md-4">
                                <label for="createFirstName"> First Name</label>
                                <input type="text" class="form-control" id="createFirstName" name="createFirstName">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="createMiddleName"> Middle Name</label>
                                <input type="text" class="form-control" id="createMiddleName" name="createMiddleName">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="createLastName"> Last Name</label>
                                <input type="text" class="form-control" id="createLastName" name="createLastName">
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="form-group col-md-6">
                                <label for="createPassword"> Password</label>
                                <input type="password" class="form-control" id="createPassword" name="createPassword">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="createRepeat"> Confirm Password</label>
                                <input type="password" class="form-control" id="createRepeat" name="createRepeat">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="createCampus">Campus</label>
                            <select class="form-control" id="createCampus" name="createCampus">
                                <?php foreach ($campuses as $key => $row) : ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['campusName']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeCreate">Close</button>
                        <button type="submit" class="btn btn-danger" id="createSubmit">Create Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create a modal for edit accounts -->
    <div class="modal fade" id="editAccounts" tabindex="-1" role="dialog" aria-labelledby="editAccount" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAccount">Edit Admin Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editAccountForm" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="editAccountID">ID</label>
                                <input type="text" class="form-control" id="editAccountID" name="editAccountID" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="editEmail">Email Address</label>
                                <input type="email" class="form-control" id="editEmail" name="editEmail">
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="form-group col-md-4">
                                <label for="editFirstName"> First Name</label>
                                <input type="text" class="form-control" id="editFirstName" name="editFirstName">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="editMiddleName"> Middle Name</label>
                                <input type="text" class="form-control" id="editMiddleName" name="editMiddleName">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="editLastName"> Last Name</label>
                                <input type="text" class="form-control" id="editLastName" name="editLastName">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editAccountCampus">Campus</label>
                            <select class="form-control" id="editAccountCampus" name="editAccountCampus">
                                <option value="<?php echo $campID; ?>" selected><?php echo $campus; ?></option>
                                <?php foreach ($campusNotIncluded as $key => $row) : ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['campusName']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeModal">Close</button>
                        <button type="submit" class="btn btn-danger" id="editAccountSubmit">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



</main><!-- End #main -->