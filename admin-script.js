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

// validate the form that has password field using event.preventDefault() to stop form being submitted
function validateFormRequiredPassword(
  event,
  requiredFieldIds,
  passwordId,
  matchingPasswordId
) {
  event.preventDefault();
  addListenerInForm(event.target.id);
  let isRequiredValid = validateRequiredFields(requiredFieldIds);
  let isPasswordValid = validateMatchPassword(passwordId, matchingPasswordId);
  if (isRequiredValid && isPasswordValid) {
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

// validate the password and matching password
function validateMatchPassword(passwordId, matchingPasswordId) {
  let isValid = true;
  const password = document.getElementById(passwordId);
  const matchingPassword = document.getElementById(matchingPasswordId);

  if (password === null) {
    console.warn(`Element with ID ${passwordId} does not exist.`);
    isValid = false;
    return isValid;
  }

  if (matchingPassword === null) {
    console.warn(`Element with ID ${matchingPasswordId} does not exist.`);
    isValid = false;
    return isValid;
  }

  //get a value from url
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  const action = urlParams.get("action");

  passwordValue = password.value.trim();
  matchingPasswordValue = matchingPassword.value.trim();

  // check if users don't want to change password
  if (action == "edit") {
    if (passwordValue == "") {
      return true;
    }
  }

  if (passwordValue == "") {
    const errorElement = document.getElementById(passwordId + "_error");
    errorElement.style.display = "block";
    errorElement.textContent = "Password is required.";
    isValid = false;
  }

  if (passwordValue !== matchingPasswordValue) {
    const errorElement = document.getElementById(matchingPasswordId + "_error");
    errorElement.style.display = "block";
    errorElement.textContent = "Password and Confirm Password must be match.";
    isValid = false;
  }
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
  const textInputs = form.querySelectorAll(
    'input[type="text"], input[type="password"]'
  );

  if (form.getAttribute("data-listeners-added") === "true") {
    return;
  }
  textInputs.forEach((input) => {
    input.addEventListener("input", function () {
      clearErrorMessage(this.id);
    });
  });

  form.setAttribute("data-listeners-added", "true");
}
