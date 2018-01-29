$(document).ready(function(){

  if ($(window).width() > 990) {
    var paragraphHeight = $('.content').height();
    $('.image').css('height', paragraphHeight);
    $('.img-container').css('height', paragraphHeight);
  }
});
