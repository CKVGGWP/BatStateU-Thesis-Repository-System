<main id="main" class="main">

  <div class="pagetitle">
    <h1>Browse Manuscript</h1>
    <nav>
      <?php include('breadcrumbs.php'); ?>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Search Manuscript</h5>

            <!--<div class="row g-1 mb-3">-->
            <!--                <label class="col-sm-1 col-form-label">Filter:</label>-->
            <!--                <div class="col-sm-2 col-3">-->
            <!--                    <select class="form-control" id="filterByCampus">-->
            <!--                        <option value selected>Campus</option>-->
            <!--                    </select>-->
            <!--                </div>-->
            <!--                <div class="col-sm-5 col-3">-->
            <!--                    <select class="form-control" id="filterByDept" disabled>-->
            <!--                        <option value selected>Select Department</option>-->
            <!--                    </select>-->
            <!--                </div>-->
            <!--                <div class="col-sm-2 col-3">-->
            <!--                    <select class="form-control" id="filterByYear" disabled>-->
            <!--                        <option value selected>Year Published</option>-->
            <!--                    </select>-->
            <!--                </div>-->
            <!--                <div class="col-2">-->
            <!--                    <i class="fas fa-undo pt-2" id="undo" title="Undo" hidden></i>-->
            <!--                </div>-->
            <!--            </div>-->

            <!-- Table with stripped rows -->
            <table class="table table-hover dt-responsive nowrap text-center" width="100%" id="browseManuscriptTable">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Title</th>
                  <th scope="col">Authors</th>
                  <th scope="col">Year Published</th>
                  <th scope="col">Actions</th>
                  <th scope="col">Tags</th>
                </tr>
              </thead>
            </table>
            <!-- End Table with stripped rows -->

          </div>
        </div>

      </div>
    </div>
  </section>
  <div class="modal fade" id="viewAbstractModal" tabindex="-1" role="dialog" aria-labelledby="viewAbstractModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Abstract</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

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

  <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Manuscript Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="manuscriptPassword" method="POST">
          <div class="modal-body">
            <div class="form-floating">
              <input id="OTP" type="password" class="form-control" placeholder="One-Time-Password">
              <label for="password">One-Time-Password</label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-danger btn-md" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-outline-primary btn-md">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</main><!-- End #main -->