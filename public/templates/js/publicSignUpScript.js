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

  $("#birthdate").on("input", function () {
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

  $("#rsbsa_no").on("input", function () {
    let value = $(this).val().trim();
    let input = $(this);

    if (!value) return; // Skip empty input

    $.post(
      BASE_URL + "public/signUp/checker",
      {
        table: "client_info",
        field: "rsbsa_ref_no",
        value: value,
      },
      function (res) {
        if (res.exists) {
          input.addClass("is-invalid");
          input
            .next(".invalid-feedback")
            .text("This RSBSA number is already in use.")
            .removeClass("d-none");
        } else {
          input.removeClass("is-invalid");
          input.next(".invalid-feedback").addClass("d-none");
        }
      },
      "json"
    );
  });

  $("#contact_no").on("keypress", function (e) {
    const char = String.fromCharCode(e.which);
    const value = $(this).val();

    // Allow digits
    if (/\d/.test(char)) return;

    // Allow "+" only at the start and only once
    if (char === "+" && this.selectionStart === 0 && !value.includes("+"))
      return;

    e.preventDefault(); // Block everything else
  });

  // Input length limiter and validation
  $("#contact_no").on("input", function () {
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
        BASE_URL + "public/signUp/checker",
        {
          table: "users",
          field: "contact_no",
          value: value,
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

  $("#email").on("input", function () {
    let value = $(this).val().trim();
    let input = $(this);

    if (!value) {
      input.removeClass("is-invalid");
      input.next(".invalid-feedback").hide();
      return;
    }

    // Allow only specific domains
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

    // Proceed to AJAX duplicate check
    $.post(
      BASE_URL + "public/signUp/checker",
      {
        table: "users",
        field: "email",
        value: value,
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

  $("#username").on("input", function () {
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
      BASE_URL + "public/signUp/checker",
      {
        table: "users", // Fixed typo here
        field: "username",
        value: value,
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

  $("#password").on("input", function () {
    let value = $(this).val().trim();
    let input = $(this);
    let desc = input.closest(".col-md-6").find(".description");

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
  });

  $("#signUpForm").on("submit", function (e) {
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
        title: "Please correct the errors",
        text: "Some fields have errors. Please fix them before submitting.",
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
    $("#submitBtn").prop("disabled", true).text("Saving...");
    showLoader();

    this.submit(); // Submit the form normally
  });
});
