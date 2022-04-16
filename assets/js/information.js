$("#campus").on("change", function () {
  let campus = $(this).val();

  $.ajax({
    url: "controllers/newInformationController.php",
    method: "POST",
    data: {
      campus: campus,
      getCampus: true,
    },
    dataType: "json",
    success: function (data) {
      $("#department").html(data);
    },
  });
});

$("#userCampus").on("change", function () {
  let campus = $(this).val();

  $.ajax({
    url: "controllers/newInformationController.php",
    method: "POST",
    data: {
      campus: campus,
      getCampus: true,
    },
    dataType: "json",
    success: function (data) {
      $("#userDepartment").html(data);
    },
  });
});

$("#editAccountCampus").on("change", function () {
  let campus = $(this).val();

  $.ajax({
    url: "controllers/newInformationController.php",
    method: "POST",
    data: {
      campus: campus,
      getCampus: true,
    },
    dataType: "json",
    success: function (data) {
      $("#editDepartment").html(data);
    },
  });
});

$("#saveInfo").on("submit", function (e) {
  e.preventDefault();

  let srCode = $("#srCode").val();
  let email = $("#email").val();
  let firstName = $("#firstName").val();
  let middleName = $("#middleName").val();
  let lastName = $("#lastName").val();
  let userCampus = $("#userCampus").val();
  let userDepartment = $("#userDepartment").val();

  if (email == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please enter your email address.",
    }).then((result) => {
      email.focus();
      email.addClass("is-invalid");
    });
  } else if (emailValidation(email) == false) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please enter a valid email address.",
    }).then((result) => {
      email.focus();
      email.addClass("is-invalid");
    });
  } else if (firstName == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please enter your first name.",
    }).then((result) => {
      firstName.focus();
      firstName.addClass("is-invalid");
    });
  } else if (lastName == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please enter your last name.",
    }).then((result) => {
      lastName.focus();
      lastName.addClass("is-invalid");
    });
  } else if (userCampus == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please select your campus.",
    }).then((result) => {
      userCampus.focus();
      userCampus.addClass("is-invalid");
    });
  } else if (userDepartment == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please select your department.",
    }).then((result) => {
      userDepartment.focus();
      userDepartment.addClass("is-invalid");
    });
  } else {
    $("#saveInfoButton").blur();
    $("#saveInfoButton").attr("disabled", true);
    $("#saveInfoButton").html(
      '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...'
    );
    $.ajax({
      url: "controllers/newInformationController.php",
      method: "POST",
      data: {
        srCode: srCode,
        email: email,
        firstName: firstName,
        middleName: middleName,
        lastName: lastName,
        userCampus: userCampus,
        userDepartment: userDepartment,
        saveInfo: true,
      },
      success: function (response) {
        console.log(response);
        $("#saveInfoButton").attr("disabled", false);
        $("#saveInfoButton").html("Save Changes");
        if (response == 1) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Email Already Exists!",
          });
        } else if (response == 2) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Something went wrong with the server. Please try again later.",
          });
        } else if (response == 3) {
          Swal.fire({
            icon: "success",
            title: "Success",
            text: "Information Updated Successfully!",
          });
        } else if (response == 4) {
          Swal.fire({
            icon: "success",
            title: "Success",
            text: "Information Updated Successfully! Please verify your new email address.",
          }).then((result) => {
            window.location.href = "controllers/signoutController.php";
          });
        }
      },
    });
  }
});

$("#changePassForm").on("submit", function (e) {
  e.preventDefault();

  let currentPassword = $("#currentPassword").val();
  let newPassword = $("#newPassword").val();
  let renewPassword = $("#renewPassword").val();

  if (currentPassword == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please enter your current password.",
    }).then((result) => {
      currentPassword.focus();
      currentPassword.addClass("is-invalid");
    });
  } else if (newPassword == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please enter your new password.",
    }).then((result) => {
      newPassword.focus();
      newPassword.addClass("is-invalid");
    });
  } else if (renewPassword == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please confirm your new password.",
    }).then((result) => {
      renewPassword.focus();
      renewPassword.addClass("is-invalid");
    });
  } else if (newPassword != renewPassword) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "New password and confirm password do not match.",
    }).then((result) => {
      renewPassword.focus();
      renewPassword.addClass("is-invalid");
    });
  } else {
    $("#changePassButton").blur();
    $("#changePassButton").attr("disabled", true);
    $("#changePassButton").html(
      '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Changing...'
    );
    $.ajax({
      url: "controllers/newInformationController.php",
      method: "POST",
      data: {
        currentPassword: currentPassword,
        newPassword: newPassword,
        renewPassword: renewPassword,
        changePass: true,
      },
      success: function (response) {
        console.log(response);
        $("#changePassButton").attr("disabled", false);
        $("#changePassButton").html("Change Password");
        if (response == 1) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Current password is incorrect.",
          });
        } else if (response == 2) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Something went wrong with the server. Please try again later.",
          });
        } else if (response == 4) {
          Swal.fire({
            icon: "success",
            title: "Success",
            text: "Password Updated Successfully! Please login back again to your account.",
          }).then((result) => {
            window.location.href = "controllers/signoutController.php";
          });
        }
      },
    });
  }
});

$(document).on("submit", "#editAccountForm", function (e) {
  e.preventDefault();

  let editAccountID = $("#editAccountID").val();
  let editEmail = $("#editEmail").val();
  let editFirstName = $("#editFirstName").val();
  let editMiddleName = $("#editMiddleName").val();
  let editLastName = $("#editLastName").val();
  let editAccountCampus = $("#editAccountCampus").val();
  let editDepartment = $("#editDepartment").val();

  if (editEmail == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please enter your email address.",
    }).then((result) => {
      email.focus();
      email.addClass("is-invalid");
    });
  } else if (emailValidation(editEmail) == false) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please enter a valid email address.",
    }).then((result) => {
      email.focus();
      email.addClass("is-invalid");
    });
  } else if (editFirstName == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please enter your first name.",
    }).then((result) => {
      firstName.focus();
      firstName.addClass("is-invalid");
    });
  } else if (editLastName == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please enter your last name.",
    }).then((result) => {
      lastName.focus();
      lastName.addClass("is-invalid");
    });
  } else if (editAccountCampus == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please select your campus.",
    }).then((result) => {
      userCampus.focus();
      userCampus.addClass("is-invalid");
    });
  } else if (editDepartment == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please select your department.",
    }).then((result) => {
      userDepartment.focus();
      userDepartment.addClass("is-invalid");
    });
  } else {
    $("#closeModal").attr("disabled", true);
    $("#editAccountSubmit").blur();
    $("#editAccountSubmit").attr("disabled", true);
    $("#editAccountSubmit").html(
      '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...'
    );
    $.ajax({
      url: "controllers/newInformationController.php",
      method: "POST",
      data: {
        srCode: editAccountID,
        email: editEmail,
        firstName: editFirstName,
        middleName: editMiddleName,
        lastName: editLastName,
        userCampus: editAccountCampus,
        userDepartment: editDepartment,
        saveInfo: true,
      },
      success: function (response) {
        console.log(response);
        $("#editAccountSubmit").attr("disabled", false);
        $("#closeModal").attr("disabled", false);
        $("#editAccountSubmit").html("Save Changes");
        if (response == 1) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Email Already Exists!",
          });
        } else if (response == 2) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Something went wrong with the server. Please try again later.",
          });
        } else if (response == 3) {
          Swal.fire({
            icon: "success",
            title: "Success",
            text: "Information Updated Successfully!",
          }).then((result) => {
            location.reload();
          });
        } else if (response == 4) {
          Swal.fire({
            icon: "success",
            title: "Success",
            text:
              "Information Updated Successfully! We've sent a verification email to " +
              editEmail +
              ".",
          }).then((result) => {
            location.reload();
          });
        }
      },
    });
  }
});

if ($("#notifications").length > 0) {
  function loadNotifications(view = "") {
    $.ajax({
      url: "controllers/newInformationController.php",
      method: "POST",
      data: { view: view },
      dataType: "json",
      success: function (data) {
        // console.log(data);
        $("#countHeader").html(data.countHeader);
        $("#notificationDIV").html(data.notifications);
        if (data.countNotifications > 0) {
          $("#notifbadge").html(data.countNotifications);
        }
      },
    });
  }

  loadNotifications();

  $(document).on("click", "#notifications", function () {
    $("#notifbadge").html("");
    loadNotifications("view");
  });

  setInterval(function () {
    loadNotifications();
  }, 5000);

  $(document).on("click", "#markAllBTN", function (e) {
    e.preventDefault();

    $.ajax({
      url: "controllers/newInformationController.php",
      method: "POST",
      data: { markAll: true },
      success: function (response) {
        loadNotifications();
      },
    });
  });
}
