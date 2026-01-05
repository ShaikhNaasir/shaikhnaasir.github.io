// Countdown Timer
// Set launch date - Change this to your actual launch date
const launchDate = new Date("2026-01-15T00:00:00").getTime();

function updateCountdown() {
  const now = new Date().getTime();
  const distance = launchDate - now;

  // Calculate time units
  const days = Math.floor(distance / (1000 * 60 * 60 * 24));
  const hours = Math.floor(
    (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
  );
  const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  const seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Update DOM
  document.getElementById("days").textContent = days
    .toString()
    .padStart(2, "0");
  document.getElementById("hours").textContent = hours
    .toString()
    .padStart(2, "0");
  document.getElementById("minutes").textContent = minutes
    .toString()
    .padStart(2, "0");
  document.getElementById("seconds").textContent = seconds
    .toString()
    .padStart(2, "0");

  // If countdown is finished
  if (distance < 0) {
    clearInterval(countdownInterval);
    document.getElementById("countdown").innerHTML =
      '<h3 class="coming-soon-english">We are Live!</h3>';
  }
}

// Update countdown every second
const countdownInterval = setInterval(updateCountdown, 1000);

// Initialize countdown immediately
updateCountdown();

// Add subtle animations on load
window.addEventListener("load", function () {
  document.querySelector(".content-wrapper").style.opacity = "0";
  setTimeout(() => {
    document.querySelector(".content-wrapper").style.transition =
      "opacity 1s ease";
    document.querySelector(".content-wrapper").style.opacity = "1";
  }, 100);
});
