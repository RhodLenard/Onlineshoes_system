document.addEventListener("DOMContentLoaded", function () {
  const toggleButton = document.getElementById("darkModeToggle");
  const toggleKnob = document.querySelector(".toggle-knob");
  const body = document.body;

  // ✅ Check and apply dark mode on page load
  if (localStorage.getItem("darkMode") === "enabled") {
    enableDarkMode();
  }

  toggleButton.addEventListener("click", function () {
    if (body.getAttribute("data-theme") === "dark") {
      disableDarkMode();
    } else {
      enableDarkMode();
    }
  });

  function enableDarkMode() {
    body.setAttribute("data-theme", "dark");

    // ✅ Apply softer dark mode styles
    body.style.backgroundColor = "#2c2c2c"; // A soft dark gray
    body.style.color = "#dcdcdc"; // Light gray text

    // ✅ Modify all links
    document
      .querySelectorAll("a")
      .forEach((link) => (link.style.color = "#b0b0b0")); // Softer link color

    // ✅ Modify navbar with a muted dark background
    document.querySelector(".navbar").style.backgroundColor = "#333333"; // Darker gray

    // ✅ Modify tables with softer dark background and text color
    document.querySelectorAll("table").forEach((table) => {
      table.style.backgroundColor = "#444444"; // Softer dark gray
      table.style.color = "#dcdcdc"; // Light gray text
    });

    // ✅ Modify buttons with a subtle dark background
    document.querySelectorAll(".btn").forEach((btn) => {
      btn.style.backgroundColor = "#555555"; // Softer dark background
      btn.style.color = "#ffffff"; // Light text color
    });

    // ✅ Move toggle knob to the right
    toggleKnob.style.transform = "translateX(20px)";
    toggleButton.style.backgroundColor = "#333333"; // Softer dark background

    // ✅ Change the --underline-color to white for dark mode
    document.querySelectorAll(".nav ul li a, .nav1 ul li a").forEach((link) => {
      link.style.setProperty("--underline-color", "#ffffff"); // White underline in dark mode
    });

    // ✅ Save to localStorage
    localStorage.setItem("darkMode", "enabled");
  }

  function disableDarkMode() {
    body.removeAttribute("data-theme");

    // ✅ Reset to light mode styles
    body.style.backgroundColor = "";
    body.style.color = "";

    document.querySelectorAll("a").forEach((link) => (link.style.color = ""));
    document.querySelector(".navbar").style.backgroundColor = "";
    document.querySelectorAll("table").forEach((table) => {
      table.style.backgroundColor = "";
      table.style.color = "";
    });
    document.querySelectorAll(".btn").forEach((btn) => {
      btn.style.backgroundColor = "";
      btn.style.color = "";
    });

    // ✅ Move toggle knob to the left
    toggleKnob.style.transform = "translateX(0)";
    toggleButton.style.backgroundColor = "#e0e0e0"; // Light background

    // ✅ Change the --underline-color back to black for light mode
    document.querySelectorAll(".nav ul li a, .nav1 ul li a").forEach((link) => {
      link.style.setProperty("--underline-color", "#000000"); // Black underline in light mode
    });

    // ✅ Remove from localStorage
    localStorage.setItem("darkMode", "disabled");
  }
});
