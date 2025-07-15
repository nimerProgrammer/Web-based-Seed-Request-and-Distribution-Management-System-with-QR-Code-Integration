$(document).ready(function () {
  $("#seasonsTable").DataTable({
    ordering: false,
    searching: false,
    lengthChange: false, // ✅ disables the "Show entries" dropdown
  });
});
