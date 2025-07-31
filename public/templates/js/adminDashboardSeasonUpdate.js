function updateSeasonInfo() {
  fetch("dashboard/getCurrentSeason")
    .then((res) => res.json())
    .then((data) => {
      const title = document.getElementById("season-title");
      const dates = document.getElementById("season-dates");

      if (!title || !dates) return;

      if (data.success) {
        const start = new Date(data.date_start);
        const end = new Date(data.date_end);

        const options = { year: "numeric", month: "long", day: "numeric" };
        const startFormatted = start.toLocaleDateString(undefined, options);
        const endFormatted = end.toLocaleDateString(undefined, options);

        title.textContent = `${data.season} - ${data.year}`;
        dates.textContent = `${startFormatted} to ${endFormatted}`;
      } else {
        title.textContent = "-";
        dates.textContent = "No current season available.";
      }
    })
    .catch((err) => {
      console.error("Failed to update season info:", err);
    });
}

document.addEventListener("DOMContentLoaded", () => {
  updateSeasonInfo();
  setInterval(updateSeasonInfo, 1000); // update every 10s
});
