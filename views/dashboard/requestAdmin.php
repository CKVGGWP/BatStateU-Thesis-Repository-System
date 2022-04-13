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
            <table class="table table-hover dt-responsive nowrap" style="width:100%" id="requestAdminTable">
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
                <tr>
                  <th scope="row">1</th>
                  <td>2016-05-25</td>
                  <td>Brandon Jacob</td>
                  <td>Designer</td>
                  <td>28</td>
                  <td>DELETE</td>
                </tr>
                <tr>
                  <th scope="row">2</th>
                  <td>2014-12-05</td>
                  <td>Bridie Kessler</td>
                  <td>Developer</td>
                  <td>35</td>
                  <td>DELETE</td>
                </tr>
                <tr>
                  <th scope="row">3</th>
                  <td>2011-08-12</td>
                  <td>Ashleigh Langosh</td>
                  <td>Finance</td>
                  <td>45</td>
                  <td>DELETE</td>

                </tr>
                <tr>
                  <th scope="row">4</th>
                  <td>2012-06-11</td>
                  <td>Angus Grady</td>
                  <td>HR</td>
                  <td>34</td>
                  <td>DELETE</td>
                </tr>
                <tr>
                  <th scope="row">5</th>
                  <td>2011-04-19</td>
                  <td>Raheem Lehner</td>
                  <td>Dynamic Division Officer</td>
                  <td>47</td>
                  <td>DELETE</td>
                </tr>
                <tr>
                  <th scope="row">6</th>
                  <td>2011-04-06</td>
                  <td>Robie Advins</td>
                  <td>Cleaning Officer</td>
                  <td>50</td>
                  <td>DELETE</td>
                </tr>
                <tr>
                  <th scope="row">7</th>
                  <td>2011-01-19</td>
                  <td>Ramie Consult</td>
                  <td>Stealth Division Officer</td>
                  <td>35</td>
                  <td>DELETE</td>
                </tr>
                <tr>
                  <th scope="row">8</th>
                  <td>2011-07-30</td>
                  <td>Eira Bound</td>
                  <td>Dynamic Commanding Officer</td>
                  <td>80</td>
                  <td>DELETE</td>
                </tr>
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
            <table class="table table-striped table-hover">
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