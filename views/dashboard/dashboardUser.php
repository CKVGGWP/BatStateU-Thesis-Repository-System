<main id="main" class="main">

  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <?php include('breadcrumbs.php'); ?>
    </nav>
  </div><!-- End Page Title -->

  <section class="section dashboard">
    <div class="row">

      <!-- Left side columns -->
      <div class="col-lg-12">
        <div class="row">
          <!-- Manuscript Request Status Card -->
          <div class="col-xxl-12 col-xl-12">
            <div class="card recent-sales overflow-auto">
              <div class="card-body">
                <h5 class="card-title">Manuscript Request Status</h5>

                <table class="table table-borderless table-sm table-hover text-center dt-responsive" id="userManuscriptRequestStatus">
                  <thead>
                    <tr>
                      <th scope="col">Title</th>
                      <th scope="col">Author</th>
                      <th scope="col">Status</th>
                      <th scope="col">Reason</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>

          <?php if ($campus == "Malvar") : ?>

            <!-- Manuscript Upload Status Card -->
            <div class="col-xxl-12 col-xl-12">
              <div class="card recent-sales overflow-auto">
                <div class="card-body">
                  <h5 class="card-title">Manuscript Upload Status</h5>

                  <table class="table table-borderless table-sm table-hover text-center dt-responsive" id="userManuscriptUploadStatus">
                    <thead>
                      <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Date Uploaded</th>
                        <th scope="col">Status</th>
                        <th scope="col">Reason</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>

          <?php endif; ?>

        </div>
      </div><!-- End Left side columns -->
    </div>
  </section>

  <div class="modal fade" id="viewJournalModal" tabindex="-1" role="dialog" aria-labelledby="viewJournalModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="row">
            <div class="col-4">
              <h5 class="modal-title" id="viewJournalModalTitle">Journal</h5>
            </div>
            <div class="col-8">
              <input type="radio" class="btn-check toggle-manuscript radio-journal" name="options-outlined" id="success-outlined" autocomplete="off" checked>
              <label class="btn btn-outline-success btn-sm" for="success-outlined">Journal</label>
              <input type="radio" class="btn-check toggle-manuscript" name="options-outlined" id="danger-outlined" autocomplete="off">
              <label class="btn btn-outline-danger btn-sm" for="danger-outlined">Abstract</label>
            </div>
          </div>

          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="d-flex justify-content-center">
              <div class="spinner-border" role="status">
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

</main><!-- End #main -->