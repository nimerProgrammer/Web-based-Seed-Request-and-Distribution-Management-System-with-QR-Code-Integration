setInterval(() => {
  fetch("/cronjobs/checkSeason")
    .then((response) => response.json())
    .then((data) => {
      console.log("Cropping season status checked:", data.status);
    })
    .catch((error) => {
      console.error("Error checking season:", error);
    });
}, 1000); // every 5 seconds
