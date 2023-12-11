window.onload = function() {
    var myform = document.querySelector("#form");

    myform.addEventListener('submit', function (element) {

        var validationfailed = false;
        var email = document.querySelector('#email');
        var password = document.querySelector('#password');

        clearErrors();
   
        if (!isValidEmail(email.value.trim())) {
            validationfailed = true;
            displayErrorMessage(email, "Incorrect format for email address.");
        }

        if (!isValidPassword(password.value.trim())) {
            validationfailed = true;
            displayErrorMessage(password, "Passwords must be a minimum of 8 characters in length and include at least one number, one letter, and one capital letter.")
        }

        if (validationfailed) {
            alert('Please fix issues in Form submission and try again.');
            element.preventDefault();
        }
        else 
        {
            alert("User successfully added")
        }

    });
};


function isValidPassword(password) {
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    return passwordRegex.test(password);
  }

  function isValidEmail(emailAddress) {
    return /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/.test(emailAddress);
  }

  function insertAfter(element, newNode) {
    element.parentNode.insertBefore(newNode, this.nextSibling);
  }

function clearErrors() {
    var elementsWithErrors = document.querySelectorAll('.error');
    for (var x = 0; x < elementsWithErrors.length; x++) {
      elementsWithErrors[x].setAttribute('class', '');
      elementsWithErrors[x].parentNode.removeChild(elementsWithErrors[x].nextElementSibling);
    }
  
  }

  function displayErrorMessage(formfield,message){
    formfield.setAttribute('class', 'error');
    var errorMessageText = document.createTextNode(message);
    var errorMessage = document.createElement('span');
    errorMessage.setAttribute('class', 'error-message');
    errorMessage.appendChild(errorMessageText);
    insertAfter(formfield, errorMessage);
  }
  

