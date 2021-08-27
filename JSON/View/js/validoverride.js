console.log(" loaded validoverride");
var a_username = document.getElementById("a_username");
 a_username.setCustomValidity("Fill in this feild");
 a_username.addEventListener("input", function (event) {
  if ( a_username.validity.valueMissing) {
      a_username.setCustomValidity("Here you need to use your NewSimland login user name.");
  } else {
     a_username.setCustomValidity("Fill in this feild");
  }
});

var a_password = document.getElementById("a_password");
a_password.setCustomValidity("Fill in this field");
a_password.addEventListener("input", function (event) {
  if (a_password.validity.valueMissing) {
     a_password.setCustomValidity("Here you need to use your NewSimland login user password.");
  } else {
    a_password.setCustomValidity("Fill in this field");
  }
});