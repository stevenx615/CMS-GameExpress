document.addEventListener("DOMContentLoaded", load);

function load() {
  navActiveEffect();
}

// navigation active effect
function navActiveEffect() {
  var url = window.location.pathname;
  var menuItems = document.querySelectorAll(".navigation-link");
  menuItems.forEach(function (item) {
    if (url.includes(item.getAttribute("href"))) {
      item.classList.add("navigation-active");
    }
  });
}

// validate the form using event.preventDefault() to stop form being submitted
function validateForm(event, fieldIds) {
  event.preventDefault();
  addListenerInForm(event.target.id);
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
      return isValid;
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

// hidden the error messages
function clearErrorMessage(inputElementId) {
  const errorElement = document.getElementById(inputElementId + "_error");
  if (errorElement) {
    errorElement.style.display = "none";
  }
}

// add event listener to all input element in a form
function addListenerInForm(formId) {
  const form = document.getElementById(formId);
  const textInputs = form.querySelectorAll('input[type="text"], textarea');

  if (form.getAttribute("data-listeners-added") === "true") {
    return;
  }
  textInputs.forEach((input) => {
    input.addEventListener("input", function () {
      clearErrorMessage(this.id);
    });
  });

  // avoid adding listener again
  form.setAttribute("data-listeners-added", "true");
}
