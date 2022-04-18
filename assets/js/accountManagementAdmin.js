$(document).ready(function () {
  let title = getParameterByName("title");
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
      data: { getAccounts: true, title: title },

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
$(document).on("click", ".deleteUser", function () {
  var srCode = $(this).data("srcode");
  Swal.fire({
    title:
      "Are you sure that you want to delete the account of " + srCode + "?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "controllers/accountManagementAdminController.php",
        type: "POST",
        data: { deleteAccount: true, srCode: srCode },
        dataType: "json",
        success: function (data) {
          console.log(data);
          if (data.status == 1) {
            Swal.fire({
              title: "Deleted!",
              text: data.message,
              icon: "success",
              showConfirmButton: false,
              timer: 1500,
            });
            $("#accountManagementTable").DataTable().ajax.reload();
          } else if (data.status == 2) {
            Swal.fire({
              title: "Error!",
              text: data.message,
              icon: "error",
              showConfirmButton: false,
              timer: 1500,
            });
          } else if (data.status == 0) {
            Swal.fire({
              title: "Error!",
              text: data.message,
              icon: "error",
              showConfirmButton: false,
              timer: 1500,
            });
          }
        },
      });
    }
  });
});

$(document).on("click", ".editUser", function () {
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
      $("#editDepartment").html(
        '<option value="' +
          dataObject[0][6] +
          '" selected>' +
          dataObject[0][7] +
          "</option>"
      );
      $("#editProgram").html(
        '<option value="' +
          dataObject[0][8] +
          '" selected>' +
          dataObject[0][9] +
          "</option>"
      );
    },
  });
});
