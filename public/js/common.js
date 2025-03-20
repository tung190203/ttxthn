document.addEventListener("DOMContentLoaded", () => {
  window.shortOptionsSwitch = false;
  const toggleTag = document.getElementById("toggle");
  toggleTag.addEventListener("click", () => {
    const shortOptions = document.querySelector(".shortOptions");
    if (shortOptionsSwitch) {
      shortOptions.classList.add("hidden");
    } else {
      shortOptions.classList.remove("hidden");
    }
    window.shortOptionsSwitch = !window.shortOptionsSwitch;
  });
});