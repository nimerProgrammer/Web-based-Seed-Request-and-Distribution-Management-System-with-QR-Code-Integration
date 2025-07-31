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
