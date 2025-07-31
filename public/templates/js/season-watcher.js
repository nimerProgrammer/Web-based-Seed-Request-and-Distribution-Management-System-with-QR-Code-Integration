setInterval(() => {
  fetch("/cronjobs/checkSeason")
    .then((response) => response.json())
    .then((data) => {
      console.log("Cropping season status checked:", data.status);
    })
    .catch((error) => {
      console.error("Error checking season:", error);
    });
}, 10000); // every 10 seconds
