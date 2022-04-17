<main id="main" class="main">

  <div class="pagetitle">
    <h1>Manuscript Upload</h1>
    <nav>
      <?php include('views/dashboard/breadcrumbs.php'); ?>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Upload</h5>
            <ul class="nav nav-tabs nav-tabs-bordered">
              <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#manuscriptPane">Manuscript Details</button>
              </li>
              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#fileUploadPane">File Upload</button>
              </li>
            </ul>
            <form class="row g-3" id="adminUpload" enctype="multipart/form-data">
              <div class="tab-content pt-2">
                <div class="tab-pane fade show active profile-edit pt-3" id="manuscriptPane">
                  <div class="col-md-12">
                    <div class="form-floating">
                      <textarea type="text" class="form-control" id="title" placeholder="Title"></textarea>
                      <label for="title">Title</label>
                    </div>
                  </div>
                  <div class="col-md-12 row my-2">
                    <div class="col-md-6">
                      <label for="authors">Authors</label>
                      <select class="form-control" id="registeredAuthors" placeholder="Registered Authors" multiple>
                        <?php foreach ($userByCampus as $key => $rows) : ?>
                          <option value="<?php echo $rows['fullName']; ?>"><?php echo $rows['fullName']; ?></option>
                        <?php endforeach; ?>
                      </select>

                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="yearPub" placeholder="Year of Publication">
                        <label for="yearPub">Year of Publication</label>
                      </div>
                    </div>
                  </div>
                  <?php if ($role == 'Admin') : ?>
                    <div class="col-md-12 row">
                      <div class="col-md-6">
                        <div class="form-floating">
                          <select class="form-select" id="department" placeholder="Department">
                            <?php foreach ($departments as $key => $row) : ?>
                              <option value="<?= $row['id'] ?>"><?= $row['departmentName'] ?></option>
                            <?php endforeach; ?>
                          </select>
                          <label for="yearPub">Department</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-floating">
                          <input type="text" class="form-control" id="program" placeholder="Program">
                          <label for="program">Program</label>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>

                <div class="tab-pane fade pt-3" id="fileUploadPane">
                  <div class="col-md-12 row">
                    <div class="col-md-6">
                      <label for="inputNumber" class="col-sm-2 col-form-label">Abstract</label>
                      <div class="col-md-12">
                        <input class="form-control" type="file" id="abstract" accept="application/pdf">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label for="inputNumber" class="col-sm-2 col-form-label">Journal</label>
                      <div class="col-md-12">
                        <input class="form-control" type="file" id="journal" accept="application/pdf">
                      </div>
                    </div>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn btn-danger w-25 mt-3 rounded-pill" id="uploadFiles">UPLOAD</button>
                  </div>
                </div>
              </div><!-- End Bordered Tabs -->
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

</main><!-- End #main -->