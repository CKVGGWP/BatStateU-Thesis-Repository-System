<main id="main" class="main">

  <div class="pagetitle">
    <h1>Pending Manuscript</h1>
    <nav>
      <?php include('breadcrumbs.php'); ?>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <h5 class="card-title">For Approval of Manuscript</h5>
              <a href="dashboard.php?title=View Manuscript" class="btn btn-dark btn-sm my-3">Manuscripts</a>
            </div>
            <!-- Table with stripped rows -->
            <table class="table table-hover dt-responsive nowrap text-center" width="100%" id="pendingManuscriptTable">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Title</th>
                  <th scope="col">Authors</th>
                  <th scope="col">Year Published</th>
                  <th scope="col">Campus</th>
                  <th scope="col">Department</th>
                  <th scope="col">Date Uploaded</th>
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

  <div class="modal fade" id="pendingRequestModal" tabindex="-1" role="dialog" aria-labelledby="viewJournalModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</main><!-- End #main -->