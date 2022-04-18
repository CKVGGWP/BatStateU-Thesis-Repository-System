<main id="main" class="main">

  <div class="pagetitle">
    <h1>Account Management</h1>
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
              <h5 class="card-title">Users</h5>
              <!-- <button type="button" class="h-25 btn btn-dark btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#accountPendings">Pending Accounts
                <span class="badge bg-danger">69</span>
              </button> -->

            </div>
            <p>List of Registered Accounts on the System</p>

            <!-- Table with stripped rows -->
            <table class="table table-hover dt-responsive nowrap text-center" id="accountManagementTable">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">ID</th>
                  <th scope="col">Name</th>
                  <th scope="col">Campus</th>
                  <th scope="col">Program</th>
                  <th scope="col">Department</th>
                  <th scope="col">Email</th>
                  <th scope="col">Role</th>
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

  <!-- Create a modal with a table -->
  <!-- <div class="modal fade" id="accountPendings" tabindex="-1" role="dialog" aria-labelledby="accountPendings" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="accountPendings">Pending Accounts</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table class="table table-hover dt-responsive nowrap text-center" id="accountPendingTable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Campus</th>
                <th scope="col">Department</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div> -->

  <!-- Create a modal for edit accounts -->
  <div class="modal fade" id="editAccounts" tabindex="-1" role="dialog" aria-labelledby="editAccount" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editAccount">Edit Account</h5>
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
            <div class="row">
              <div class="form-group col-md-3">
                <label for="editAccountCampus">Campus</label>
                <select class="form-control" id="editAccountCampus" name="editAccountCampus">
                  <option value="<?php echo $campID; ?>" selected><?php echo $campus; ?></option>
                  <?php foreach ($campusNotIncluded as $key => $row) : ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['campusName']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group col-md-9">
                <label for="editDepartment">Department</label>
                <select class="form-control" id="editDepartment" name="editDepartment">
                  <option value="<?php echo $deptID; ?>"><?php echo $department; ?></option>
                </select>
              </div>
              <div class="form-group col-md-12">
                <label for="editProgram">Program</label>
                <select class="form-control" id="editProgram" name="editProgram">
                  <option value="<?php echo $programID; ?>"><?php echo $programName; ?></option>
                </select>
              </div>
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