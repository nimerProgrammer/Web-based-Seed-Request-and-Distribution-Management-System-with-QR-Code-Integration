function refreshSeasonTable() {
    fetch("dashboard/getSeasonTableBody")
        .then((res) => res.text())
        .then((html) => {
            const tbody = document.getElementById("season-tbody");
            if (tbody) {
                tbody.innerHTML = html;
            }
        })
        .catch((err) => {
            console.error("Failed to refresh cropping season table:", err);
        });
}

document.addEventListener("DOMContentLoaded", () => {
    refreshSeasonTable();
    setInterval(refreshSeasonTable, 10000); // every 10 seconds
});

$(document).on("click", ".edit-season-btn", function () {
    const season = $(this).data("season");
    const year = $(this).data("year");
    const start = $(this).data("start");
    const end = $(this).data("end");
    const id = $(this).data("id");

    $("#edit_cropping_season_tbl_id").val(id);
    $("#edit_season_name").val(season);
    $("#edit_season_year").val(year);
    $("#edit_season_start_date").val(start);
    $("#edit_season_end_date").val(end);

    $("#editSeasonModal").modal("show");
});

function showLoader() {
    const loader = document.getElementById("loading-spinner");
    if (loader) {
        loader.style.display = "flex";
    }
}

$(document).on("click", ".delete-season-btn", function () {
    // $(".delete-season-btn").on("click", function (e) {
    // e.preventDefault();

    const button = $(".delete-season-btn");
    if (button.hasClass("disabled")) return;

    const url = button.data("url");
    const csrfName = button.data("csrf-name");
    const csrfHash = button.data("csrf-hash");

    Swal.fire({
        title: "Are you sure?",
        text: "This cropping season will be permanently deleted.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
        customClass: {
            confirmButton: "btn btn-danger me-2",
            cancelButton: "btn btn-secondary",
        },
        buttonsStyling: false,
    }).then((result) => {
        if (result.isConfirmed) {
            showLoader();
            $(".btn-danger").text("Deleting...");
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    [csrfName]: csrfHash,
                },
                success: function (response) {
                    Swal.fire({
                        icon: "success",
                        title: "Deleted!",
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false,
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text:
                            xhr.responseJSON?.message ||
                            "Failed to delete cropping season.",
                    });
                },
            });
        }
    });
});
