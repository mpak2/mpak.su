$(document).ready(function(){

    $('.MainMenu li').hover(
      function () {
      $(this).children('ul').css('display','block');
      }, 
      function () {
      $(this).children('ul').css('display','none');
      }
    );
    $('.MainMenu li:last').addClass('last');
    
});