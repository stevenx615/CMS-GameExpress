document.addEventListener("DOMContentLoaded", load);

function load() {}

// update image preview
function loadFile(event) {
  var output = document.getElementById("cover-preview");
  output.src = URL.createObjectURL(event.target.files[0]);
  output.onload = function () {
    URL.revokeObjectURL(output.src);
  };
}

// validate the form using event.preventDefault() to stop form being submitted
function validateForm(event, fieldIds) {
  event.preventDefault();
  isValid = validateRequiredFields(fieldIds);
  if (isValid) {
    event.target.submit();
  }
}

// validate the required fields
function validateRequiredFields(fieldIds) {
  let isValid = true;

  fieldIds.forEach(function (id) {
    const element = document.getElementById(id);
    if (element === null) {
      console.warn(`Element with ID ${id} does not exist.`);
      isValid = false;
      return;
    }

    const value = element.value.trim();
    const errorElement = document.getElementById(id + "_error");

    if (value == "") {
      errorElement.style.display = "block";
      errorElement.textContent = "This field is required.";
      isValid = false;
    }
  });

  return isValid;
}

// prompt window to confirm before continuing processing
