$(document).ready(function () {
  if(localStorage.getItem('popState') != 'shown'){
    $("#popup").modal();
    localStorage.setItem('popState','shown');
  }
});
