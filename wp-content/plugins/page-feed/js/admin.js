window.fbAsyncInit = function() {
FB.init({
  appId      : fb_option.fb_app_id,
  xfbml      : true,
  version    : 'v2.4'
});
};

(function(d, s, id){
 var js, fjs = d.getElementsByTagName(s)[0];
 if (d.getElementById(id)) {return;}
 js = d.createElement(s); js.id = id;
 js.src = "//connect.facebook.net/en_US/sdk.js";
 fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

(function( $ ){
	$(document).ready(function(){
		$('#page-feed-fetch-btn').off('click').on('click', function(){
			var that = $(this);
			that.attr('disabled', 'disabled');
			$('#page-feed-mesage').addClass('alert-info').html('Please wait...');
			FB.login(function(){
			  	FB.api(
				    "/"+fb_option.fb_page_id+"/events?limit=200&since=1325462400",
				    function (response) {
				    	if (response && !response.error) {
				    		var data = {
								'action': 'create_fb_post',
								'posts': response.data
							};
				        	jQuery.post(ajaxurl, data, function(response) {
								that.removeAttr('disabled');
								$('#page-feed-mesage').addClass('alert-success').html(response);
							});
				      	}
				    }
				);
			});
		});
	});
})( jQuery.noConflict() );