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

  $("#editBirthdate").on("input", function () {
    const value = $(this).val();
    const input = $(this);

    if (!value) {
      input.removeClass("is-invalid");
      input.next(".invalid-feedback").hide();
      return;
    }

    const birthdate = new Date(value + "T00:00:00+08:00"); // treat input as PH time
    const nowPH = new Date(
      new Date().toLocaleString("en-US", { timeZone: "Asia/Manila" })
    );

    let age = nowPH.getFullYear() - birthdate.getFullYear();
    let hasHadBirthday =
      nowPH.getMonth() > birthdate.getMonth() ||
      (nowPH.getMonth() === birthdate.getMonth() &&
        nowPH.getDate() >= birthdate.getDate());

    let isAtLeast18 = age > 18 || (age === 18 && hasHadBirthday);

    if (!isAtLeast18) {
      input.addClass("is-invalid");

      if (!input.next(".invalid-feedback").length) {
        input.after('<div class="invalid-feedback"></div>');
      }

      input
        .next(".invalid-feedback")
        .text("You must be at least 18 years old to sign up.")
        .show();
    } else {
      input.removeClass("is-invalid");
      input.next(".invalid-feedback").hide();
    }
  });

  $("#editContactNo").on("input", function () {
    let original = $("#originalContactNo").val();
    let value = $(this).val().trim();
    let input = $(this);

    if (!value) return;

    // Slice value based on prefix
    if (value.startsWith("+639")) {
      value = value.slice(0, 13);
    } else if (value.startsWith("09")) {
      value = value.slice(0, 11);
    } else {
      value = value.slice(0, 13);
    }

    $(this).val(value);

    // Validate pattern
    let isValid = false;
    if (/^09\d{9}$/.test(value)) {
      isValid = true;
    } else if (/^\+639\d{9}$/.test(value)) {
      isValid = true;
    } else {
      isValid = false;
    }

    if (!isValid) {
      input.addClass("is-invalid");
      input
        .next(".invalid-feedback")
        .text("Must start with 09 (11 digits) or +639 (13 characters).");
    } else {
      $.post(
        "checker",
        {
          table: "users",
          field: "contact_no",
          value: value,
          original: original,
        },
        function (res) {
          if (res.exists) {
            input.addClass("is-invalid");
            input
              .next(".invalid-feedback")
              .text("This contact number is already in use.");
          } else {
            input.removeClass("is-invalid");
          }
        },
        "json"
      );
    }
  });

  $("#editEmail").on("input", function () {
    let original = $("#originalEmail").val();
    let value = $(this).val().trim();
    let input = $(this);

    if (!value) {
      input.removeClass("is-invalid");
      input.next(".invalid-feedback").hide();
      return;
    }

    // Allowed domains
    let allowedDomains = ["@gmail.com", "@email.com", "@yahoo.com"];
    let isValid = false;

    for (let domain of allowedDomains) {
      if (value.endsWith(domain)) {
        let prefix = value.slice(0, -domain.length);
        if (prefix.length > 0 && /^[A-Za-z0-9._%+-]+$/.test(prefix)) {
          isValid = true;
          break;
        }
      }
    }

    if (!isValid) {
      input.addClass("is-invalid");
      input
        .next(".invalid-feedback")
        .text(
          "Please enter a complete email address (e.g., abc@gmail.com, abc@yahoo.com, or abc@email.com), not only the domain."
        )
        .show();
      return;
    }

    // AJAX duplicate check
    $.post(
      "checker",
      {
        table: "users",
        field: "email",
        value: value,
        original: original,
      },
      function (res) {
        if (res.exists) {
          input.addClass("is-invalid");
          input
            .next(".invalid-feedback")
            .text("This email address is already in use.")
            .show();
        } else {
          input.removeClass("is-invalid");
          input.next(".invalid-feedback").hide();
        }
      },
      "json"
    );
  });

  $("#editUsername").on("input", function () {
    let original = $("#originalUsername").val();
    let value = $(this).val().trim();
    let input = $(this);

    if (!value) {
      input.removeClass("is-invalid");
      input.next(".invalid-feedback").hide();
      return;
    }

    // Check for minimum length
    if (value.length <= 7) {
      input.addClass("is-invalid");
      input
        .next(".invalid-feedback")
        .text("Username must be at least 8 characters long.")
        .show();
      return;
    }

    // Disallow the literal word "username"
    else if (value.toLowerCase() === "username") {
      input.addClass("is-invalid");
      input
        .next(".invalid-feedback")
        .text("Username cannot be 'username'.")
        .show();
      return;
    }

    // Disallow uppercase letters
    if (/[A-Z]/.test(value)) {
      input.addClass("is-invalid");
      input
        .next(".invalid-feedback")
        .text("Username must not contain uppercase letters.")
        .show();
      return;
    }

    // AJAX duplicate check
    $.post(
      "checker",
      {
        table: "users", // Fixed typo here
        field: "username",
        value: value,
        original: original,
      },
      function (res) {
        if (res.exists) {
          input.addClass("is-invalid");
          input
            .next(".invalid-feedback")
            .text("This username is already in use.")
            .show();
        } else {
          input.removeClass("is-invalid");
          input.next(".invalid-feedback").hide();
        }
      },
      "json"
    );
  });

  $("#editFullnameBtn").on("click", function () {
    const firstName = $(this).data("firstname") || "";
    const lastName = $(this).data("lastname") || "";
    const middleName = $(this).data("middlename") || "";
    const extName = $(this).data("extname") || "";

    $("#editFirstName").val(firstName);
    $("#editLastName").val(lastName);
    $("#editMiddleName").val(middleName);
    $("#editExtName").val(extName);

    $("#editNameModal").modal("show");
  });

  $("#editGenderBtn").on("click", function () {
    const gender = $(this).data("gender") || "";
    $("#editGender").val(gender);
    $("#editGenderModal").modal("show");
  });

  $("#editBirthdateBtn").on("click", function () {
    const birthdate = $(this).data("birthdate") || "";
    $("#editBirthdate").val(birthdate);
    $("#editBirthdateModal").modal("show");
  });

  $("#editContactNoBtn").on("click", function () {
    const contactno = $(this).data("contactno") || "";
    $("#editContactNo").val(contactno);
    $("#originalContactNo").val(contactno);
    $("#editContactNoModal").modal("show");
  });

  $("#editEmailBtn").on("click", function () {
    const email = $(this).data("email") || "";
    $("#editEmail").val(email);
    $("#originalEmail").val(email);
    $("#editEmailModal").modal("show");
  });

  $("#editUsernameBtn").on("click", function () {
    const username = $(this).data("username") || "";
    $("#editUsername").val(username);
    $("#originalUsername").val(username);
    $("#editUsernameModal").modal("show");
  });

  $("#toggleCurrentPassword").on("click", function () {
    toggleVisibility($("#currentPassword"), $(this).find("i"));
  });

  $("#toggleNewPassword").on("click", function () {
    toggleVisibility($("#newPassword"), $(this).find("i"));
  });

  $("#toggleConfirmPassword").on("click", function () {
    toggleVisibility($("#confirmPassword"), $(this).find("i"));
  });

  function toggleVisibility(input, icon) {
    const isPassword = input.attr("type") === "password";
    input.attr("type", isPassword ? "text" : "password");
    icon.toggleClass("fa-eye fa-eye-slash");
  }

  $("#currentPassword").on("input", function () {
    $("#currentPassword").removeClass("is-invalid");
    $(".invalid-feedback").text("").hide();
  });

  function validateConfirmPassword() {
    const newPassword = $("#newPassword").val().trim();
    const confirmPassword = $("#confirmPassword").val().trim();

    if (newPassword && confirmPassword) {
      if (newPassword !== confirmPassword) {
        $("#newPassword").addClass("is-invalid");
        $("#confirmPassword").addClass("is-invalid");
        $("#confirmPasswordFeedback")
          .text("Please make sure both passwords match.")
          .show();
      } else {
        $("#newPassword").removeClass("is-invalid");
        $("#confirmPassword").removeClass("is-invalid");
        $("#confirmPasswordFeedback").text("").hide();
      }
    } else {
      $("#newPassword").removeClass("is-invalid");
      $("#confirmPassword").removeClass("is-invalid");
      $("#confirmPasswordFeedback").text("").hide();
    }
  }

  $("#confirmPassword").on("input", function () {
    validateConfirmPassword();
  });

  $("#newPassword").on("input", function () {
    let value = $(this).val().trim();
    let input = $(this);
    let desc = input.closest(".mb-3").find(".description");

    // Rule checks
    let hasUppercase = /[A-Z]/.test(value);
    let hasNumber = /[0-9]/.test(value);
    let hasSymbol = /[^A-Za-z0-9]/.test(value);
    let isLongEnough = value.length >= 8;

    // Password rule output
    let html = `
      <div><strong>Password must contain the following:</strong></div>
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

    // Input border styling
    if (hasSymbol && hasUppercase && hasNumber && isLongEnough) {
      input.removeClass("is-invalid");
      $("#confirmPassword").removeClass("is-invalid");
      $("#confirmPasswordFeedback").text("").hide();
    } else {
      input.addClass("is-invalid");
    }
  });

  /* forms */
  $("#editFullnameForm").on("submit", function (e) {
    $(".btn").text("Saving...");
    showLoader();
  });

  $("#editGenderForm").on("submit", function (e) {
    $(".btn").text("Saving...");
    showLoader();
  });

  $("#editBirthdateForm").on("submit", function (e) {
    e.preventDefault(); // Stop form from submitting immediately

    let hasError = false;

    // Check all invalid-feedback elements for visible error messages
    $(this)
      .find(".invalid-feedback")
      .each(function () {
        if ($(this).is(":visible") && $(this).text().trim() !== "") {
          hasError = true;
        }
      });

    if (hasError) {
      Swal.fire({
        icon: "error",
        title: "Oops!",
        text: "Invalid date, you must be at least 18 years old.",
        timer: 3000, // 3 seconds
        showConfirmButton: false,
        customClass: {
          confirmButton: "btn btn-primary",
        },
        buttonsStyling: false,
      });
      return;
    }

    // If no errors, proceed
    this.submit(); // Submit the form normally
    $(".btn").text("Saving...");
    showLoader();
  });

  $("#editContactNoForm").on("submit", function (e) {
    e.preventDefault(); // Stop form from submitting immediately

    let hasError = false;

    // Check all invalid-feedback elements for visible error messages
    $(this)
      .find(".invalid-feedback")
      .each(function () {
        if ($(this).is(":visible") && $(this).text().trim() !== "") {
          hasError = true;
        }
      });

    if (hasError) {
      Swal.fire({
        icon: "error",
        title: "Oops!",
        text: "Invalid contact number, please correct it.",
        timer: 3000, // 3 seconds
        showConfirmButton: false,
        customClass: {
          confirmButton: "btn btn-primary",
        },
        buttonsStyling: false,
      });
      return;
    }

    // If no errors, proceed
    this.submit(); // Submit the form normally
    $(".btn").text("Saving...");
    showLoader();
  });

  $("#editEmailForm").on("submit", function (e) {
    e.preventDefault(); // Stop form from submitting immediately

    let hasError = false;

    // Check all invalid-feedback elements for visible error messages
    $(this)
      .find(".invalid-feedback")
      .each(function () {
        if ($(this).is(":visible") && $(this).text().trim() !== "") {
          hasError = true;
        }
      });

    if (hasError) {
      Swal.fire({
        icon: "error",
        title: "Oops!",
        text: "Invalid email address, please correct it.",
        timer: 3000, // 3 seconds
        showConfirmButton: false,
        customClass: {
          confirmButton: "btn btn-primary",
        },
        buttonsStyling: false,
      });
      return;
    }

    // If no errors, proceed
    this.submit(); // Submit the form normally
    $(".btn").text("Saving...");
    showLoader();
  });

  $("#editUsernameForm").on("submit", function (e) {
    e.preventDefault(); // Stop form from submitting immediately

    let hasError = false;

    // Check all invalid-feedback elements for visible error messages
    $(this)
      .find(".invalid-feedback")
      .each(function () {
        if ($(this).is(":visible") && $(this).text().trim() !== "") {
          hasError = true;
        }
      });

    if (hasError) {
      Swal.fire({
        icon: "error",
        title: "Oops!",
        text: "Invalid username, please correct it.",
        timer: 3000, // 3 seconds
        showConfirmButton: false,
        customClass: {
          confirmButton: "btn btn-primary",
        },
        buttonsStyling: false,
      });
      return;
    }

    // If no errors, proceed
    this.submit(); // Submit the form normally
    $(".btn").text("Saving...");
    showLoader();
  });

  $("#changePasswordForm").on("submit", function (e) {
    e.preventDefault(); // Prevent default form submission

    validateConfirmPassword(); // Ensure confirm password is validated
    const form = $(this);
    let hasError = false;

    // Check invalid-feedback visibility
    form.find(".invalid-feedback").each(function () {
      if ($(this).is(":visible") && $(this).text().trim() !== "") {
        hasError = true;
      }
    });

    // Check for .is-invalid inputs
    if (form.find(".is-invalid").length > 0) {
      hasError = true;
    }

    if (hasError) {
      Swal.fire({
        icon: "error",
        title: "Oops!",
        text: "Some fields are invalid. Please correct them before saving.",
        timer: 3000,
        showConfirmButton: false,
        customClass: {
          confirmButton: "btn btn-primary",
        },
        buttonsStyling: false,
      });
      return;
    }

    // 🔐 Validate current password via AJAX
    const currentPassword = $("#currentPassword").val().trim();

    $.post(
      "checkCurrentPassword",
      { currentPassword },
      function (res) {
        if (res.valid) {
          // If valid, disable submit and show loader
          form
            .find("button[type='submit']")
            .prop("disabled", true)
            .text("Saving...");
          showLoader();
          form.off("submit").submit(); // remove handler and submit
        } else {
          // Show invalid error
          $("#currentPassword").addClass("is-invalid");
          $("#currentPassword")
            .closest(".mb-3")
            .find(".invalid-feedback")
            .text("Incorrect current password.")
            .show();

          Swal.fire({
            icon: "error",
            title: "Authentication Failed",
            text: "Your current password is incorrect.",
            timer: 3000,
            showConfirmButton: false,
            customClass: {
              confirmButton: "btn btn-primary",
            },
            buttonsStyling: false,
          });
        }
      },
      "json"
    ).fail(function () {
      Swal.fire({
        icon: "error",
        title: "Server Error",
        text: "Could not verify your current password. Please try again.",
        timer: 3000,
        showConfirmButton: false,
      });
    });
  });
});
