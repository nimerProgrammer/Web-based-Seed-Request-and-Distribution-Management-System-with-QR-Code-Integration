$(document).ready(function () {
  function showLoader() {
    const loader = document.getElementById("loading-spinner");
    if (loader) {
      loader.style.display = "flex";
    }
  }

  function hideLoader() {
    const loader = document.getElementById("loading-spinner");
    if (loader) {
      loader.style.display = "none";
    }
  }

  function validatePassword() {
    let value = $("#new_password").val().trim();
    let input = $("#new_password");
    let desc = input.closest(".pass-description").find(".description");

    // Rule checks
    let hasUppercase = /[A-Z]/.test(value);
    let hasNumber = /[0-9]/.test(value);
    let hasSymbol = /[^A-Za-z0-9]/.test(value);
    let isLongEnough = value.length >= 8;

    // Final HTML message
    let html = `
        <div><strong>Password must be contains the following:</strong></div>
        <ul class="list-unstyled mb-0">
          <li class="d-flex align-items-center">
            <i class="bi me-2 ${
              hasSymbol
                ? "bi-check-circle-fill text-success"
                : "bi-x-circle-fill text-danger"
            }"></i>
            At least one special symbol <small class="text-muted">(e.g. !@#$)</small>
          </li>
          <li class="d-flex align-items-center">
            <i class="bi me-2 ${
              hasUppercase
                ? "bi-check-circle-fill text-success"
                : "bi-x-circle-fill text-danger"
            }"></i>
            At least one uppercase letter <small class="text-muted">(e.g. A-Z)</small>
          </li>
          <li class="d-flex align-items-center">
            <i class="bi me-2 ${
              hasNumber
                ? "bi-check-circle-fill text-success"
                : "bi-x-circle-fill text-danger"
            }"></i>
            At least one number <small class="text-muted">(e.g. 0–9)</small>
          </li>
          <li class="d-flex align-items-center"> 
            <i class="bi me-2 ${
              isLongEnough
                ? "bi-check-circle-fill text-success"
                : "bi-x-circle-fill text-danger"
            }"></i>
            At least 8 characters
          </li>
        </ul>
      `;

    desc.html(html);

    // Input border color
    if (hasSymbol && hasUppercase && hasNumber && isLongEnough) {
      input.removeClass("is-invalid");
    } else {
      input.addClass("is-invalid");
    }
  }
  // Show Password Toggle
  $("#show_reset_password").on("change", function () {
    const type = this.checked ? "text" : "password";
    $("#new_password, #confirm_password").attr("type", type);
  });

  $("#new_password").on("input change", function () {
    validatePassword();
  });

  // Submit Handler (Adjust API URL as needed)
  $("#resetPasswordForm").on("submit", function (e) {
    e.preventDefault();

    const email = $("#token").val();
    const new_password = $("#new_password").val();
    const confirm_password = $("#confirm_password").val();

    if (new_password !== confirm_password) {
      $("#reset_message").html(
        `<div class="alert-outline-danger">The passwords you entered don’t match. Please try again.</div>`
      );
      $("#new_password").addClass("is-invalid");
      $("#confirm_password").addClass("is-invalid");
      return;
    }
    $("#resetSpinner").removeClass("d-none");
    $("#resetPasswordBtn").prop("disabled", true).text("Updating...");

    $.ajax({
      url: "submitResetPassword", // Backend route here
      method: "POST",
      data: { email: email, new_password: new_password },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          location.reload();
        }
      },
      error: function () {
        $("#forgot_message").html(
          `<div class="alert alert-danger p-2 mb-2">Can't change your password or error occurred.</div>`
        );
        $("#resetSpinner").addClass("d-none");
        $("#resetPasswordBtn").attr("disabled", false);
      },
    });
  });

  $("#proceedLoginBtn").on("click", function (e) {
    e.preventDefault(); // prevent immediate navigation
    $("#linkSpinner").removeClass("d-none"); // show spinner
    $(this).attr("disabled", true);
    window.location.href = "/"; // navigate after spinner
  });
});
