<main id="main" class="main">

  <div class="pagetitle">
    <h1>Manuscript Request</h1>
    <nav>
      <?php include("breadcrumbs.php"); ?>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <h5 class="card-title">Request</h5>
              <button type="button" class="h-25 btn btn-dark btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#requestHistory">History
                <!-- <span class="badge bg-danger">69</span> -->
              </button>

            </div>
            <p>List of students that are requesting to view/download the manuscripts</p>

            <!-- Table with stripped rows -->
            <table class="table table-hover table-striped text-center dt-responsive nowrap" style="width:100%" id="requestAdminTable">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Date</th>
                  <th scope="col">Manuscript Title</th>
                  <th scope="col">Authors</th>
                  <th scope="col">Requester</th>
                  <th scope="col">Actions</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
            <!-- End Table with stripped rows -->

          </div>
        </div>

      </div>
    </div>
  </section>
  <!-- Modal for Request History -->
  <div class="modal fade" id="requestHistory" tabindex="-1" role="dialog" aria-labelledby="requestHistory" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="requestHistory">Request History</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-hover dt-responsive nowrap text-center" width="100%">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Date</th>
                  <th scope="col">Manuscript Title</th>
                  <th scope="col">Authors</th>
                  <th scope="col">Requester</th>
                  <th scope="col">Actions</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</main><!-- End #main -->