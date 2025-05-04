/** @format */

$(document).ready(function () {
  // Hamburger menu toggle
  $("#hamburger").on("click", function () {
    $("#links").toggleClass("hidden");
    $(this).find(".bar").toggleClass("transform");
  });
});
