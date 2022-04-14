$(document).ready(function () {
  let manuscriptTable = $('#manuscriptTable').DataTable({
    // lengthChange: false,
    // searching: false,
    processing: true,
    // ordering: false,
    // serverSide: true,
    bInfo: false,
    ajax: {
      url: 'controllers/manuscriptController.php', // json datasource
      type: 'POST', // method  , by default get
      data: { getManuscript: true },

      // success: function (row, data, index) {
      // console.log(row);
      // },

      error: function (data) {
        console.log(data);
        // error handling
      },
    },
    createdRow: function (row, data, index) {},
    columnDefs: [],
    fixedColumns: false,
    deferRender: true,
    scrollY: 500,
    scrollX: false,
    // scroller: {
    //   loadingIndicator: true,
    // },
    stateSave: false,
  });

  let browseManuscriptTable = $('#browseManuscriptTable').DataTable({
    // lengthChange: false,
    // searching: false,
    processing: true,
    // ordering: false,
    // serverSide: true,
    bInfo: false,
    ajax: {
      url: 'controllers/manuscriptController.php', // json datasource
      type: 'POST', // method  , by default get
      data: { browseManuscript: true },

      // success: function (row, data, index) {
      // console.log(row);
      // },

      error: function (data) {
        console.log(data);
        // error handling
      },
    },
    createdRow: function (row, data, index) {},
    columnDefs: [],
    fixedColumns: false,
    deferRender: true,
    scrollY: 500,
    scrollX: false,
    // scroller: {
    //   loadingIndicator: true,
    // },
    stateSave: false,
  });

  let requestAdminTable = $('#requestAdminTable').DataTable({
    // lengthChange: false,
    // searching: false,
    processing: true,
    // ordering: false,
    // serverSide: true,
    bInfo: false,
    ajax: {
      url: 'controllers/manuscriptController.php', // json datasource
      type: 'POST', // method  , by default get
      data: { getRequestAdmin: true },

      // success: function (row, data, index) {
      // console.log(row);
      // },

      error: function (data) {
        console.log(data);
        // error handling
      },
    },
    createdRow: function (row, data, index) {},
    columnDefs: [],
    fixedColumns: false,
    deferRender: true,
    scrollY: 500,
    scrollX: false,
    scroller: {
      loadingIndicator: true,
    },
    stateSave: false,
  });

  let pendingManuscriptTable = $('#pendingManuscriptTable').DataTable({
    // lengthChange: false,
    // searching: false,
    processing: true,
    // ordering: false,
    // serverSide: true,
    bInfo: false,
    ajax: {
      url: 'controllers/manuscriptController.php', // json datasource
      type: 'POST', // method  , by default get
      data: { pendingManuscript: true },

      // success: function (row, data, index) {
      // console.log(row);
      // },

      error: function (data) {
        console.log(data);
        // error handling
      },
    },
    createdRow: function (row, data, index) {},
    columnDefs: [],
    fixedColumns: false,
    deferRender: true,
    scrollY: 500,
    scrollX: false,
    scroller: {
      loadingIndicator: true,
    },
    stateSave: false,
  });
});

$(document).on('click', '.delete', function () {
  let manuscriptId = $(this).data('id');
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#dc3545',
    confirmButtonText: 'Yes, delete it!',
  }).then((result) => {
    if (result.value) {
      $.ajax({
        url: 'controllers/manuscriptController.php',
        type: 'POST',
        data: { deleteManuscript: 1, manuscriptId: manuscriptId },
        success: function (data) {
          if (data == 1) {
            Swal.fire('Deleted!', 'Manuscript has been deleted.', 'success');
          } else {
            Swal.fire('Error!', 'Something went wrong.', 'error');
          }
          $('#manuscriptTable').DataTable().ajax.reload();
        },
      });
    }
  });
});

$(document).on('click', '.edit', function () {
  let manuscriptId = $(this).data('id');
  manuscriptDetails(manuscriptId);
});

$(document).on('click', '.view-journal', function () {
  $('.radio-journal').prop('checked', true);
  $('#viewJournalModalTitle').html('Journal');

  let manuscriptId = $(this).data('id');
  manuscriptDetails(manuscriptId);
});

$('.toggle-manuscript').change(function () {
  if ($('.radio-journal').is(':checked')) {
    $('#modalJournal').prop('hidden', false);
    $('#modalAbstract').prop('hidden', true);
    $('#viewJournalModalTitle').html('Journal');
  } else {
    $('#modalJournal').prop('hidden', true);
    $('#modalAbstract').prop('hidden', false);
    $('#viewJournalModalTitle').html('Abstract');
  }
});

function manuscriptDetails(manuscriptId) {
  $.ajax({
    url: 'controllers/manuscriptController.php',
    type: 'POST',
    data: {
      manuscriptDetails: 1,
      manuscriptId: manuscriptId,
    },
    success: function (response) {
      var resp = JSON.parse(response);
      $('#manuscriptId').val(resp.id);
      $('#manuscriptTitle').val(resp.manuscriptTitle);
      $('#viewJournalModal .modal-body').html(
        '<iframe id="modalJournal" src="./assets/uploads/' +
          resp.journal +
          '" type="application/pdf" style="height:600px;width:100%"></iframe><iframe hidden id="modalAbstract" src="./assets/uploads/' +
          resp.abstract +
          '" type="application/pdf" style="height:600px;width:100%"></iframe>'
      );
    },
  });
}

$('#updateManuscript').click(function (e) {
  e.preventDefault();

  $('#updateManuscript').attr('disabled', true);
  $('#updateManuscript').html(
    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...'
  );

  let manuscriptId = $('#manuscriptId').val();
  let manuscriptTitle = $('#manuscriptTitle').val();

  $.ajax({
    url: 'controllers/manuscriptController.php',
    type: 'POST',
    data: {
      udpateManuscript: 1,
      manuscriptId: manuscriptId,
      manuscriptTitle: manuscriptTitle,
    },
    success: function (data) {
      if (data == 1) {
        Swal.fire('Updated!', 'Manuscript has been updated.', 'success');
      } else {
        Swal.fire('Error!', 'Something went wrong.', 'error');
      }

      $('#updateManuscript').attr('disabled', false);
      $('#updateManuscript').html('Save changes');
      $('#manuscriptTable').DataTable().ajax.reload();
    },
  });
});
