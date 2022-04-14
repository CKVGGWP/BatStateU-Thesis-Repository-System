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
