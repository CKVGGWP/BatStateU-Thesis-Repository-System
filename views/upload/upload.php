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
                    <div class="col-md-5">
                      <select class="form-control" id="registeredAuthors" placeholder="Authors" multiple>
                        <?php foreach ($userByCampus as $key => $rows) : ?>
                          <option value="<?php echo $rows['fullName']; ?>"><?php echo $rows['fullName']; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="col-md-4">
                      <select class="form-control" id="tags" placeholder="Tags" multiple>
                      </select>
                    </div>
                    <div class="col-md-3">
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
                          <select class="form-control" id="program" placeholder="Program">
                          </select>
                          <label for="program">Program</label>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>

                  <div class="tab-content my-2 d-md-flex justify-content-md-end">
                    <div class="tab-pane active" id="manuscriptPane">
                      <a class="btn btn-danger btnNext">Next</a>
                    </div>
                  </div>

                </div>

                <div class="tab-pane fade pt-3" id="fileUploadPane">
                  <div class="col-md-12 row">
                    <div class="col-md-6">
                      <label for="inputNumber" class="col-sm-2 col-form-label">Abstract</label>
                      <div class="col-md-12">
                        <input class="form-control mb-2" type="file" id="abstract" accept="application/pdf">
                      </div>
                      <h5><a href="#abstractPreviewModal" id="abstractView" data-bs-toggle="modal" hidden>View file</a></h5>
                    </div>
                    <div class="col-md-6">
                      <label for="inputNumber" class="col-sm-2 col-form-label">Journal</label>
                      <div class="col-md-12">
                        <input class="form-control mb-2" type="file" id="journal" accept="application/pdf">
                      </div>
                      <h5><a href="#journalPreviewModal" id="journalView" data-bs-toggle="modal" hidden>View file</a></h5>
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
  <div class="modal fade" id="abstractPreviewModal" tabindex="-1" role="dialog" aria-labelledby="previewModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="row">
            <div class="col-4">
              <h5 class="modal-title" id="abstractPreviewModalTitle">Abstract</h5>
            </div>
          </div>

          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <iframe src="" type="application/pdf" id="abstractPreview" style="height:700px;width:100%"></iframe>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="journalPreviewModal" tabindex="-1" role="dialog" aria-labelledby="journalPreviewModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="row">
            <div class="col-4">
              <h5 class="modal-title" id="journalPreviewModalTitle">Journal</h5>
            </div>
          </div>

          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <iframe src="" type="application/pdf" id="journalPreview" style="height:700px;width:100%"></iframe>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</main><!-- End #main -->
