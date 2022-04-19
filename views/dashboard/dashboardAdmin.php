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
            <div class="col-lg-8">
                <div class="row">

                    <!-- Pending Request Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Pending Request/s <a href="dashboard.php?title=View Request"><small> View Details</small></a></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-hourglass"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6 id="requestManusriptAdminDash"></h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Sales Card -->

                    <!-- Pending Approval Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">

                            <div class="card-body">
                                <h5 class="card-title">Pending Approval <a href="dashboard.php?title=Pending Manuscripts"><small> View Details</small></a></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-hourglass-top"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6 id="pendingManuscriptAdminDash"></h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Pending Approval Card -->

                    <!-- Customers Card -->
                    <div class="col-xxl-4 col-xl-12">

                        <div class="card info-card customers-card">

                            <div class="card-body">
                                <h5 class="card-title">Total Page Visitors</h5><br>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6 id="pageVisitor"></h6>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div><!-- End Customers Card -->

                    <!-- Reports -->

                    <!-- Recently Added Manuscripts Card -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">
                            <div class="card-body">
                                <h5 class="card-title">Recently Added Manuscript/s</h5>

                                <table class="table table table-borderless table-sm table-hover text-center dt-responsive" id="recentlyAddedManuscript">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Author</th>
                                            <th scope="col">Date Added</th>
                                        </tr>
                                    </thead>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Recently Added Manuscripts Card -->
                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">

                <!-- Total User Card -->
                <div class="card">
                    <div class="card-body pb-0">
                        <h5 class="card-title">Total Users <a href="dashboard.php?title=Account Management"><br><small> View Details</small></a></h5>
                        <div id="totalUsersPieGraph"></div>
                    </div>
                </div><!-- End Total Users Traffic -->

                <!-- News & Updates Traffic -->
                <div class="card">
                    <div class="card-body pb-0">
                        <h5 class="card-title">Manuscripts<a href="dashboard.php?title=View Manuscript"><br><small> View Details</small></a></h5>
                        <div id="totalManuscriptsBarGraph"></div>
                    </div>
                </div><!-- End News & Updates -->

            </div><!-- End Right side columns -->

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
          <p>PDF HERE</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

</main><!-- End #main -->