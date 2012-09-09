
$.fn.slideMenu = function(){
  
  $(this).click(function(){
    var thisParen = $(this).parent('ul li').find('ul');
    var thisParenParent = $(thisParen).parent('ul');
    if($(this).is('.closed')){
      $(thisParen).slideDown();
      $(this)
      .removeClass('closed')
      .addClass('open');
    }else{
      $(thisParen).slideUp();
      $(this)
      .removeClass('open')
      .addClass('closed');
    }
    return false;  
  });
  
}



jQuery(document).ready(function(){

$('.LeftMenu a.closed, .LeftMenu a.open').slideMenu();
 
});

