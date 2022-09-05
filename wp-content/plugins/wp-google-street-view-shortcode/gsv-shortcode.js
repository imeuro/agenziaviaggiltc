jQuery(document).ready(function($) {
        $('a.gsv-overlay').magnificPopup({
                fixedContentPos: true,
                fixedBgPos: true,
                overflowY: 'auto',
                type:'iframe',
				iframe: {
					markup: 
					'<div class="mfp-iframe-scaler">'+
						'<div class="mfp-close"></div>'+
						'<iframe class="mfp-iframe" frameborder="0" allowfullscreen allow="vr"></iframe>'+
					'</div>'
				}, // HTML markup of popup, `mfp-close` will be replaced by the close button
                callbacks: {
                     beforeOpen: function() {
                        startWindowScroll = $(window).scrollTop();
                     },
                     open: function(){
                       if ( $('.mfp-content').height() < $(window).height() ){
                         $('body').on('touchmove', function (e) {
                             e.preventDefault();
                         });
                       }
                     },
                     close: function() {
                       $(window).scrollTop(startWindowScroll);
                       $('body').off('touchmove');
                     }
                },
                disableOn: function() {
                        if($(window).width() < 600) {
                                return false;
                        }
                        return true;
                }
        });
});
