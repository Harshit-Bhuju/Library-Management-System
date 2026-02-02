// Main JS file
document.addEventListener("DOMContentLoaded", () => {
  console.log("Library System Loaded");

  // Auto-hide alerts after 3 seconds
  const alerts = document.querySelectorAll(".alert"); // Assuming you use .alert class
  if (alerts) {
    setTimeout(() => {
      alerts.forEach((alert) => {
        alert.style.opacity = "0";
        setTimeout(() => alert.remove(), 300);
      });
    }, 3000);
  }
});
