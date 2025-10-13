$(document).ready(function () {
  preventDevTools(false);
  // preventMobileAccess();

  function preventDevTools(enable) {
    if (!enable) return;

    document.addEventListener("contextmenu", (e) => {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Right click is disabled for security reasons.",
      });

      e.preventDefault();
    });

    document.addEventListener("keydown", (e) => {
      if (
        (e.ctrlKey && e.shiftKey && (e.key === "I" || e.key === "J")) ||
        (e.ctrlKey && (e.key === "u" || e.key === "s" || e.key === "p")) ||
        e.key === "F12"
      ) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "DevTools is disabled for security reasons.",
        });

        e.preventDefault();
      }
    });

    setInterval(() => {
      const devtools =
        window.outerWidth - window.innerWidth > 160 ||
        window.outerHeight - window.innerHeight > 160;

      if (devtools) {
        console.clear();

        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "DevTools is disabled for security reasons.",
        });
      }
    }, 1000);
  }

  $("#show_password_checkbox").on("click change", function () {
    const passwordInput = $("#login_password");
    passwordInput.attr("type", this.checked ? "text" : "password");
    passwordInput.focus();
  });

  $("#login_form").submit(function () {
    const email = $("#login_email").val();
    const password = $("#login_password").val();

    $("#login_submit").text("Please wait...");
    $("#login_submit").attr("disabled", true);

    var formData = new FormData();

    formData.append("email", email);
    formData.append("password", password);

    $.ajax({
      url: "../get_user_data",
      data: formData,
      type: "POST",
      dataType: "JSON",
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.success) {
          location.href = response.redirect_url;
        } else {
          if (response.error === "Email not found") {
            $("#email_error").text("Email not found.");
            $("#login_email").addClass("is-invalid");
          } else if (response.error === "Wrong password") {
            $("#password_error").text("Wrong password.");
            $("#login_password").addClass("is-invalid");
          }
          $("#login_submit").text("Login");
          $("#login_submit").attr("disabled", false);
        }
      },
      error: function (_, _, error) {
        console.error(error);
      },
    });
  });

  $("#login_email, #login_password").on("input", function () {
    $(this).removeClass("is-invalid");
    if ($(this).attr("id") === "login_email") {
      $("#email_error").text("");
    }
    if ($(this).attr("id") === "login_password") {
      $("#password_error").text("");
    }
  });

  $("#forgotPasswordForm").on("submit", function (e) {
    e.preventDefault();
    const email = $("#forgot_email").val();

    $("#forgotSpinner").removeClass("d-none");
    $("#forgotPasswordBtn").attr("disabled", true);
    // Example AJAX (replace URL with your actual endpoint)
    $.ajax({
      url: "forgotPassword", // Backend route here
      method: "POST",
      data: { email: email },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          $("#forgot_message").html(
            `<div class="alert-outline-success">Password reset link sent to your email!</div>`
          );
          $("#forgot_email").removeClass("is-invalid");
          $("#forgot_email").val("");
        } else {
          $("#forgot_message").html(
            `<div class="alert-outline-danger">Email not found or not registered.</div>`
          );
          $("#forgot_email").addClass("is-invalid");
        }

        $("#forgotSpinner").addClass("d-none");
        $("#forgotPasswordBtn").attr("disabled", false);
      },
      error: function () {
        $("#forgot_message").html(
          `<div class="alert alert-danger p-2 mb-2">Email not found or error occurred.</div>`
        );
        $("#forgotSpinner").addClass("d-none");
        $("#forgotPasswordBtn").attr("disabled", false);
      },
    });
  });
});
