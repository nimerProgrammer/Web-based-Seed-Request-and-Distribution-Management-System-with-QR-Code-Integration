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
});
