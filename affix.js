jQuery(document).ready(function($) {
  if (!!$('.widget_amazonaffiliateaffix').offset()) { // make sure ".sticky" element exists
    var stickyTop = $('.widget_amazonaffiliateaffix').offset().top;
    $(window).scroll(function(){ // scroll event
      var windowTop = $(window).scrollTop();
      if (stickyTop < windowTop){
        $('.widget_amazonaffiliateaffix').css({ position: 'fixed', top: 10 });
      }
      else {
        $('.widget_amazonaffiliateaffix').css('position','static');
      }
    });
  }
});
