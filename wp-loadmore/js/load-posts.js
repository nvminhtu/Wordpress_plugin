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
	  nextLink = pcode.nextLink,
    // Number of post
    numPosts = parseInt(pcode.numPosts);
    
    console.log(numPosts);

  var $window = null;
    /* constructor
    ----------------------------------------*/
    function init(){
      setUp();
      loadMore();
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
    function loadMore(){
      $window = $(window);
      $window.on({
        "scroll": function() {
          var
            windowScrollTop = $(window).scrollTop(),
            windowHeight = $(window).height(),
            $loadmore_btn = $("#pbd-alp-load-posts"),
            top_btn = $loadmore_btn.scrollTop();
            if((windowScrollTop-top_btn)<windowHeight) {
              if(pageNum <= max) {
                // Show that we're working.
                $("#pbd-alp-load-posts a").text('Loading posts...');
                
                $('.pbd-alp-placeholder-'+ pageNum).load(nextLink + ' .post',function() {
                    
                  // Update page number and nextLink.
                  pageNum++;
                  nextLink = nextLink.replace(/\/page\/[0-9]?/, '/page/'+ pageNum);
                  
                  // Add a new placeholder, for when user clicks again.
                  $('#pbd-alp-load-posts').before('<div class="pbd-alp-placeholder-'+ pageNum +'"></div>');
                  
                  // Update the button message.
                  if(pageNum <= max) {
                    $('#pbd-alp-load-posts a').text('Load More Posts');
                  } else {
                    $('#pbd-alp-load-posts a').text('No more posts to load.');
                  }
                });
              } else {
                $('#pbd-alp-load-posts a').append('.');
              }
            }
        }
      });
      $('#pbd-alp-load-posts a').click(function() {
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
      });
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