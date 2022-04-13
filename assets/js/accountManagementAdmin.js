$(document).ready(function () {
  let dataTable = $("#accountManagementTable").DataTable({
    // lengthChange: false,
    // searching: false,
    processing: true,
    // ordering: false,
    // serverSide: true,
    bInfo: false,
    ajax: {
      url: "controllers/accountManagementAdminController.php", // json datasource
      type: "POST", // method  , by default get
      data: { getAccounts: true },

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

//Delete Account
$(document).on("click", ".delete", function () {
  var srCode = $(this).data("srcode");
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.value) {
      $.ajax({
        url: "controllers/accountManagementAdminController.php",
        type: "POST",
        data: { deleteAccount: true, srCode: srCode },
        success: function (data) {
          Swal.fire({
            icon: "success",
            title: "Account Deleted",
            showConfirmButton: false,
            timer: 1500,
          });
          $("#accountManagementTable").DataTable().ajax.reload();
        },
      });
    }
  });
});

$(document).on("click", ".edit", function () {
  var srCode = $(this).data("srcode");
  $.ajax({
    url: "controllers/accountManagementAdminController.php",
    type: "POST",
    data: { getAccountDetails: true, srCode: srCode },
    success: function (data) {
      var dataObject = JSON.parse(data);
      console.log(dataObject);
      $("#editAccountID").val(dataObject[0][0]);
      $("#editEmail").val(dataObject[0][1]);
      $("#editFirstName").val(dataObject[0][2]);
      $("#editMiddleName").val(dataObject[0][3]);
      $("#editLastName").val(dataObject[0][4]);
    },
  });
});
