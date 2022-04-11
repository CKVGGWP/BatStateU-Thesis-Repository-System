function getParameterByName(name, url) {
  if (!url) url = window.location.href;
  name = name.replace(/[\[\]]/g, "\\$&");
  var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
    results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return "";
  return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function emailValidation(email) {
  email = email.split("@").slice(1);
  let allowedDomains = ["g.batstate-u.edu.ph"];

  if ($.inArray(email[0], allowedDomains) !== -1) {
    return true;
  } else {
    return false;
  }
}

function include(file) {
  let script = document.createElement("script");
  script.src = file;
  script.type = "text/javascript";
  script.defer = true;
  document.getElementsByTagName("head").item(0).appendChild(script);
}
