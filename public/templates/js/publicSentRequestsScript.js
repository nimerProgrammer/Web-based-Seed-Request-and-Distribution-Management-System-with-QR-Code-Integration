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

  $(".download-voucher-btn").on("click", function (e) {
    e.preventDefault();

    const requestId = $(this).data("id");
    const postUrl = $(this).data("download-url");

    // Create dynamic form
    const form = $("<form>", {
      method: "POST",
      action: postUrl,
      target: "_blank",
    }).append(
      $("<input>", {
        type: "hidden",
        name: "seed_requests_tbl_id",
        value: requestId,
      })
    );

    $("body").append(form);
    form.submit();
    form.remove();
  });

  $(".edit-request-btn").on("click", function () {
    const requestId = $(this).data("id");
    const currentSeedId = $(this).data("current-seed-id");

    $("#editSeedRequestId").val(requestId);

    const $seedSelect = $("#edit_seed_name");
    const $options = $seedSelect.find("option");

    // Reset all option text to original labels
    $options.each(function () {
      const original = $(this).data("original-label");
      if (original) {
        $(this).text(original);
      }
    });

    // Select the current seed and remove "• requested"
    const $selectedOption = $seedSelect.find(
      `option[value='${currentSeedId}']`
    );
    if ($selectedOption.length) {
      $selectedOption.prop("selected", true);

      // // Remove the "• requested" tag
      // let text = $selectedOption.text();
      // $selectedOption.text(text.replace(" • requested", ""));

      // Enable it just in case
      $selectedOption.prop("disabled", false);
    }
  });

  $("#submitEditSentRequestBtn").on("click", function (e) {
    e.preventDefault();

    showLoader();
    $("#submitEditSentRequestForm").submit();
  });

  $(".cancel-request-btn").on("click", function () {
    const requestId = $(this).data("id");

    Swal.fire({
      title: "Cancel Request?",
      text: "Are you sure you want to cancel this seed request?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, proceed",
      cancelButtonText: "Cancel",
      customClass: {
        confirmButton: "btn btn-md btn-primary mr-2",
        cancelButton: "btn btn-md btn-secondary",
      },
      buttonsStyling: false,
    }).then((result) => {
      if (result.isConfirmed) {
        showLoader();
        $.ajax({
          url: "/public/sentRequests/cancel",
          method: "POST",
          data: {
            seed_requests_tbl_id: requestId,
            [csrfTokenName]: csrfHash,
          },
          success: function () {
            hideLoader();
            Swal.fire({
              icon: "success",
              title: "Cancelled!",
              text: "Your request has been cancelled.",
              timer: 2000,
              showConfirmButton: false,
            }).then(() => location.reload());
          },
          error: function () {
            hideLoader();
            Swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to cancel the request. Please try again.",
            });
          },
        });
      }
    });
  });
});
