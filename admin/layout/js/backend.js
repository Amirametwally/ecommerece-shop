document.addEventListener("DOMContentLoaded", function () {
  // Function to manage placeholder visibility
  function managePlaceholder(input) {
    let originalPlaceholder = input.placeholder;
    input.addEventListener("focus", () => {
      input.placeholder = "";
    });

    input.addEventListener("blur", () => {
      if (input.value === "") {
        input.placeholder = originalPlaceholder;
      }
    });
  }

  // Call managePlaceholder for all input elements
  var inputs = document.querySelectorAll("input");
  inputs.forEach(function (input) {
    managePlaceholder(input);
  });

  var passField = document.getElementById("password");
  var showPass = document.querySelector(".show-pass");

  showPass.addEventListener("mouseover", function () {
    passField.type = "text";
  });

  showPass.addEventListener("mouseout", function () {
    passField.type = "password";
  });
  var categoryNames = document.querySelectorAll(".category-name");

  // Add a click event listener to each category name
  categoryNames.forEach(function(categoryName) {
      categoryName.addEventListener("click", function() {
          // Find the closest parent element with the class 'cat'
          var parentCat = this.closest(".cat");

          // Toggle the visibility of the '.full-view' div within the parent category
          var fullView = parentCat.querySelector(".full-view");
          if (fullView.style.display === "none") {
              fullView.style.display = "block";
          } else {
              fullView.style.display = "none";
          }
      });
  });
});