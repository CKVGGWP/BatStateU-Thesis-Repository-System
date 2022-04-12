<main id="main" class="main">

  <div class="pagetitle">
    <h1>Account Management</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active">Account Management</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <h5 class="card-title">Users</h5>
              <button type="button" class="h-25 btn btn-dark btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#accountPendings">Pending Accounts</button>
            </div>
            <p>People that registered their account to the system.</p>

            <!-- Table with stripped rows -->
            <table class="table table-hover dt-responsive nowrap text-center" id="accountManagementTable">
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
            <!-- End Table with stripped rows -->

          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- Create a modal with a table -->
  <div class="modal fade" id="accountPendings" tabindex="-1" role="dialog" aria-labelledby="accountPendings" aria-hidden="true">
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
  </div>

  <!-- Create a modal for edit accounts -->
  <div class="modal fade" id="editAccounts" tabindex="-1" role="dialog" aria-labelledby="editAccount" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editAccount">Edit Account</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editAccountForm" method="post">
            <div class="form-group">
              <label for="editAccountID">ID</label>
              <input type="text" class="form-control" id="editAccountID" name="editAccountID" readonly>
            </div>
            <div class="form-group">
              <label for="editAccountName">Name</label>
              <input type="text" class="form-control" id="editAccountName" name="editAccountName">
            </div>
            <div class="form-group">
              <label for="editAccountCampus">Campus</label>
              <select class="form-control" id="editAccountCampus" name="editAccountCampus"></select>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>



</main><!-- End #main -->