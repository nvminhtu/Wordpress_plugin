(function($){
  var Index = $.index = (function(){
    /* field
    ----------------------------------------*/
	
	var 
    // The number of the next page to load (/page/x/).
    pageNum = parseInt(pcode.startPage) + 1,
	  // The maximum number of pages the current query can return.
	  max = parseInt(pcode.maxPages),
	  // The link of the next page of posts.
	  nextLink = pcode.nextLink;
  
    /* constructor
    ----------------------------------------*/
    function init(){
      setUp();
      clickEvent();
      scrollEvent();
      // $(window).scroll(function(){

      // });
    }
    /**
     ** Load Setup
    **/
    function setUp(){
      if(pageNum <= max) {
        // Insert the "More Posts" link.
        $('#content')
          .append('<div class="pbd-alp-placeholder-'+ pageNum +'"></div>')
          .append('<p id="pbd-alp-load-posts"><a href="#">Load More Posts</a></p>');
          
        // Remove the traditional navigation.
        $('.navigation').remove();
      }
    }
    /**
     ** Load more Posts
    **/
    function clickEvent(){
      $('#pbd-alp-load-posts a').click(function() {
        loadMore();
      });
    }

    function scrollEvent(){
      // $(window).on("scroll", function(){
      //   loadMore();
      // });
    }

    function loadMore(){
    
        // Are there more posts to load?
        if(pageNum <= max) {
        
          // Show that we're working.
          $(this).text('Loading posts...');
          
          $('.pbd-alp-placeholder-'+ pageNum).load(nextLink + ' .post',
            function() {
              
              // Update page number and nextLink.
              pageNum++;
              nextLink = nextLink.replace(/\/page\/[0-9]?/, '/page/'+ pageNum);
              
              // Add a new placeholder, for when user clicks again.
              $('#pbd-alp-load-posts')
                .before('<div class="pbd-alp-placeholder-'+ pageNum +'"></div>')
              
              // Update the button message.
              if(pageNum <= max) {
                $('#pbd-alp-load-posts a').text('Load More Posts');
              } else {
                $('#pbd-alp-load-posts a').text('No more posts to load.');
              }
            }
          );
        } else {
          $('#pbd-alp-load-posts a').append('.');
        } 
        
        return false;
    
    }
    
    /* access control
    ----------------------------------------*/
    return {
      init : init
    }
  })();
  // document.ready
  $(Index.init);

})(jQuery);