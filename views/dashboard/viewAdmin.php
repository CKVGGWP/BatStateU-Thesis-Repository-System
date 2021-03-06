<main id="main" class="main">

  <div class="pagetitle">
    <h1>Manuscript</h1>
    <nav>
      <?php include('breadcrumbs.php'); ?>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">

            <div class="d-flex justify-content-between" id="pendingManuscriptButton">

            </div>
            <div class="d-flex justify-content-between">
              <!--<button class="btn btn-sm btn-danger" title="Export as PDF" id="exportManuscript"><i class="fa-regular fa-file-pdf"></i> Export</button>-->
            </div>
            <div class="row g-1 mb-3">
              <label class="col-sm-1 col-form-label">Filter:</label>
              <div class="col-sm-2 col-3">
                <select class="form-control" id="filterByDept">
                  <option value selected>Select Department</option>
                </select>
              </div>
              <div class="col-sm-4 col-3">
                <select class="form-control" id="filterByProg" disabled>
                  <option value selected>Select Program</option>
                </select>
              </div>
              <div class="col-sm-2 col-3">
                <select class="form-control" id="filterByYear" disabled>
                  <option value selected>Year Published</option>
                </select>
              </div>
              <div class="col-2">
                <i class="fas fa-undo pt-2" id="undo" title="Undo" hidden></i>
              </div>
            </div>

            <!-- Table with stripped rows -->
            <table class="table table-hover dt-responsive nowrap text-center" width="100%" id="manuscriptTable">
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

  <div class="modal fade" id="pendingManuscriptModal" tabindex="-1" role="dialog" aria-labelledby="pendingManuscriptModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="pendingManuscriptModalTitle">Pending Manuscript/s</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table class="table table-hover dt-responsive nowrap text-center" width="100%" id="pendingManuscriptTable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Authors</th>
                <th scope="col">Year Published</th>
                <th scope="col">Date Uploaded</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
          </table>
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

  <div class="modal fade" id="editManuscriptModal" tabindex="-1" role="dialog" aria-labelledby="editManuscriptModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editManuscriptModalTitle">Journal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="" method="POST" autocomplete="off" class="m-2">
            <input type="hidden" id="manuscriptId">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <input type="hidden" id="manuscriptId">
                  <div class="form-floating">
                    <textarea type="text" class="form-control" id="manuscriptTitle" placeholder="Title"></textarea>
                    <label for="manuscriptTitle">Title</label>
                    <div class="invalid-feedback">Please enter a valid Title.</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row my-2">
              <div class="form-group col-md-6 col-12">
                <div class="form-floating">
                  <textarea type="text" class="form-control" id="manuscriptAuthors" placeholder="Authors" data-toggle="tooltip" title="Use a comma (,) when you are going to add multiple authors. Don't put a space after a comma."></textarea>
                  <label for="manuscriptAuthors">Author(s)</label>
                </div>
              </div>
              <div class="form-group col-md-6 col-12">
                <div class="form-floating">
                  <input type="number" class="form-control" id="manuscriptYearPub" placeholder="Year Published">
                  <label for="manuscriptYearPub">Year Published</label>
                  <div class="invalid-feedback">Please enter a valid Year.</div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6 col-12">
                <div class="form-floating">
                  <select class="form-control" name="manuscriptCampus" id="manuscriptCampus" readonly disabled>
                    <option value selected disabled>Please select a Campus</option>
                    <?php foreach ($campuses as $key => $row) : ?>
                      <option value="<?php echo $row['id']; ?>">
                        <?php echo $row['campusName']; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <label for="campus" class="form-label">Campus</label>
                  <div class="invalid-feedback">Please enter a valid Campus.</div>
                </div>
              </div>
              <div class="form-group col-md-6 col-12">
                <div class="form-floating">
                  <select class="form-control" name="manuscriptDept" id="manuscriptDept" readonly disabled>
                  </select>
                  <label for="department" class="form-label">Department</label>
                  <div class="invalid-feedback">Please enter a valid Department.</div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeModal">Close</button>
          <button type="submit" class="btn btn-danger" id="updateManuscript">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</main><!-- End #main -->