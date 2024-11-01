!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):a(jQuery)}(function(a){"use strict";function b(a){this.container=a,this.init()}function c(b,c){this.widget=b,this.options=a.extend({},c),this.detectService(),this.service&&this.init()}function d(a,b){return e(a,b,encodeURIComponent)}function e(a,b,c){return a.replace(/\{([^\}]+)\}/g,function(a,d){return d in b?c?c(b[d]):b[d]:a})}function f(a,b){var c=i+a;return c+" "+c+"_"+b}function g(b){function c(f){"keydown"===f.type&&27!==f.which||a(f.target).closest(b).length||(b.fadeOut(j),d.off(e,c))}var d=a(document),e="click touchstart keydown";d.on(e,c)}function h(a,b){if(document.documentElement.getBoundingClientRect){var c=parseInt(a.css("left"),10),d=parseInt(a.css("top"),10);a.css("visibility","hidden").show();var e=a[0].getBoundingClientRect();e.left<b?a.css("left",b-e.left+c):e.right>window.innerWidth-b&&a.css("left",window.innerWidth-e.right-b+c),e.top<b?a.css("top",b-e.top+d):e.bottom>window.innerHeight-b&&a.css("top",window.innerHeight-e.bottom-b+d),a.hide().css("visibility","visible")}a.fadeIn(j)}var i="social-likes__",j="fast",k={facebook:{counterUrl:"http://graph.facebook.com/fql?q=SELECT+total_count+FROM+link_stat+WHERE+url%3D%22{url}%22&callback=?",convertNumber:function(a){return a.data[0].total_count},popupUrl:"http://www.facebook.com/sharer/sharer.php?u={url}",popupWidth:600,popupHeight:500},twitter:{counterUrl:"http://urls.api.twitter.com/1/urls/count.json?url={url}&callback=?",convertNumber:function(a){return a.count},popupUrl:"http://twitter.com/intent/tweet?url={url}&text={title}",popupWidth:600,popupHeight:450,click:function(){return/[\.:\-–—]\s*$/.test(this.options.pageTitle)||(this.options.pageTitle+=":"),!0}},mailru:{counterUrl:"http://connect.mail.ru/share_count?url_list={url}&callback=1&func=?",convertNumber:function(a){for(var b in a)if(a.hasOwnProperty(b))return a[b].shares},popupUrl:"http://connect.mail.ru/share?share_url={url}&title={title}",popupWidth:550,popupHeight:360},vkontakte:{counterUrl:"http://vkontakte.ru/share.php?act=count&url={url}&index={index}",counter:function(b,c){var e=k.vkontakte;e._||(e._=[],window.VK||(window.VK={}),window.VK.Share={count:function(a,b){e._[a].resolve(b)}});var f=e._.length;e._.push(c),a.ajax({url:d(b,{index:f}),dataType:"jsonp"})},popupUrl:"http://vk.com/share.php?url={url}&title={title}",popupWidth:550,popupHeight:330},odnoklassniki:{counterUrl:"http://www.odnoklassniki.ru/dk?st.cmd=shareData&ref={url}&cb=?",convertNumber:function(a){return a.count},popupUrl:"http://www.odnoklassniki.ru/dk?st.cmd=addShare&st._surl={url}",popupWidth:550,popupHeight:360},plusone:{popupUrl:"https://plus.google.com/share?url={url}",popupWidth:700,popupHeight:500},livejournal:{click:function(){var b=this._livejournalForm;if(!b){var c=this.options.pageHtml.replace(/&/g,"&amp;").replace(/"/g,"&quot;");b=a(e('<form action="http://www.livejournal.com/update.bml" method="post" target="_blank" accept-charset="UTF-8"><input type="hidden" name="mode" value="full"><input type="hidden" name="subject" value="{title}"><input type="hidden" name="event" value="{html}"></form>',{title:this.options.pageTitle,html:c})),this.widget.append(b),this._livejournalForm=b}b.submit()}},pinterest:{counterUrl:"http://api.pinterest.com/v1/urls/count.json?url={url}&callback=?",convertNumber:function(a){return a.count},popupUrl:"http://pinterest.com/pin/create/button/?url={url}&description={title}",popupWidth:630,popupHeight:270}},l={promises:{},fetch:function(b,c,e){l.promises[b]||(l.promises[b]={});var f=l.promises[b];if(f[c])return f[c];var g=a.extend({},k[b],e),h=a.Deferred(),i=g.counterUrl&&d(g.counterUrl,{url:c});return a.isFunction(g.counter)?g.counter(i,h):g.counterUrl&&a.getJSON(i).done(function(b){try{var c=b;a.isFunction(g.convertNumber)&&(c=g.convertNumber(b)),h.resolve(c)}catch(d){h.reject(d)}}),f[c]=h.promise(),f[c]}};a.fn.socialLikes=function(){return this.each(function(){new b(a(this))})},b.prototype={optionsMap:{pageUrl:{attr:"url",defaultValue:function(){return window.location.href.replace(window.location.hash,"")}},pageTitle:{attr:"title",defaultValue:function(){return document.title}},pageHtml:{attr:"html",defaultValue:function(){return'<a href="'+this.options.pageUrl+'">'+this.options.pageTitle+"</a>"}},pageCounters:{attr:"counters",defaultValue:"yes",convert:function(a){return"yes"===a}}},init:function(){this.container.addClass("social-likes"),this.readOptions(),this.single=this.container.hasClass("social-likes_single"),this.initUserButtons(),this.single&&(this.makeSingleButton(),this.container.on("counter.social-likes",a.proxy(this.updateCounter,this)));var b=this.options;this.container.find("li").each(function(){new c(a(this),b)})},readOptions:function(){this.options={};for(var b in this.optionsMap){var c=this.optionsMap[b];this.options[b]=this.container.data(c.attr)||(a.isFunction(c.defaultValue)?a.proxy(c.defaultValue,this)():c.defaultValue),a.isFunction(c.convert)&&(this.options[b]=c.convert(this.options[b]))}},initUserButtons:function(){!this.userButtonInited&&window.socialLikesButtons&&a.extend(k,socialLikesButtons),this.userButtonInited=!0},makeSingleButton:function(){var b=this.container;b.addClass("social-likes_vertical"),b.wrap(a("<div>",{"class":"social-likes_single-w"}));var c=b.parent(),d=parseInt(b.css("left"),10),e=parseInt(b.css("top"),10);b.hide();var k=a("<div>",{"class":f("button","single"),text:b.data("single-title")||"Share"});k.prepend(a("<span>",{"class":f("icon","single")})),c.append(k);var l=a("<li>",{"class":i+"close",html:"&times;"});b.append(l),this.number=0,k.click(function(){return b.css({left:d,top:e}),h(b,20),g(b),!1}),l.click(function(){b.fadeOut(j)}),this.wrapper=c},updateCounter:function(a,b){b&&(this.number+=b,this.getCounterElem().text(this.number))},getCounterElem:function(){var b=this.wrapper.find("."+i+"counter_single");return b.length||(b=a("<span>",{"class":f("counter","single")}),this.wrapper.append(b)),b}},c.prototype={init:function(){if(this.detectParams(),this.initHtml(),this.options.pageCounters)if(this.options.counterNumber)this.updateCounter(this.options.counterNumber);else{var b=this.options.counterUrl?{counterUrl:this.options.counterUrl}:{};l.fetch(this.service,this.options.pageUrl,b).done(a.proxy(this.updateCounter,this))}},detectService:function(){for(var b=this.widget[0].classList||this.widget[0].className.split(" "),c=0;c<b.length;c++){var d=b[c];if(k[d])return this.service=d,a.extend(this.options,k[d]),void 0}},detectParams:function(){var a=this.widget.data("counter");if(a){var b=parseInt(a,10);isNaN(b)?this.options.counterUrl=a:this.options.counterNumber=b}var c=this.widget.data("title");c&&(this.options.pageTitle=c);var d=this.widget.data("url");d&&(this.options.pageUrl=d)},initHtml:function(){var b=this.options,c=this.widget,e=!!b.clickUrl;c.removeClass(this.service),c.addClass(this.getElementClassNames("widget"));var f=c.find("a");f.length&&this.cloneDataAttrs(f,c);var g=a(e?"<a>":"<span>",{"class":this.getElementClassNames("button"),text:c.text()});if(e){var h=d(b.clickUrl,{url:b.pageUrl,title:b.pageTitle});g.attr("href",h)}else g.click(a.proxy(this.click,this));g.prepend(a("<span>",{"class":this.getElementClassNames("icon")})),c.empty().append(g),this.button=g},cloneDataAttrs:function(a,b){var c=a.data();for(var d in c)c.hasOwnProperty(d)&&b.data(d,c[d])},getElementClassNames:function(a){return f(a,this.service)},updateCounter:function(b){if(b=parseInt(b,10)){var c=a("<span>",{"class":this.getElementClassNames("counter"),text:b});this.widget.append(c),this.widget.trigger("counter.social-likes",b)}},click:function(b){var c=this.options,e=!0;if(a.isFunction(c.click)&&(e=c.click.call(this,b)),e){var f=d(c.popupUrl,{url:c.pageUrl,title:c.pageTitle});f=this.addAdditionalParamsToUrl(f),this.openPopup(f,{width:c.popupWidth,height:c.popupHeight})}return!1},addAdditionalParamsToUrl:function(b){var c=a.param(this.widget.data());if(!c)return b;var d=-1===b.indexOf("?")?"?":"&";return b+d+c},openPopup:function(a,b){var c=Math.round(screen.width/2-b.width/2),d=0;screen.height>b.height&&(d=Math.round(screen.height/3-b.height/2));var e=window.open(a,"sl_"+this.service,"left="+c+",top="+d+","+"width="+b.width+",height="+b.height+",personalbar=0,toolbar=0,scrollbars=1,resizable=1");e?e.focus():location.href=a}},a(function(){a(".social-likes").socialLikes()})});
(function($){$.fn.extend({blurjs:function(options){this.defaultOptions={customClass:'blurjs',radius:5,persist:false};function removeBlurs(_obj){for(var i in window.blurJSClasses){if(_obj.hasClass(window.blurJSClasses[i])){_obj.removeClass(window.blurJSClasses[i])}}}function singleSetting(obj,target){switch(target){case'remove':removeBlurs(obj)}}function svg(amt){if($('#blurjs-'+amt).length!=0){$('#blurjs-'+amt).remove()}$('body').append('<svg id="blurjs-'+amt+'"><filter id="blur'+amt+'px"><feGaussianBlur in="SourceGraphic" stdDeviation="'+amt+'"></feGaussianBlur></filter></svg>')}function css(cssClass,amt){svg(amt);return'<style type="text/css">.'+cssClass+'{-ms-filter:blur('+amt+'px);-webkit-filter:blur('+amt+'px);-moz-filter:blur('+amt+'px);-o-filter:blur('+amt+'px);filter: url("#blur'+(amt)+'px");filter:progid:DXImageTransform.Microsoft.Blur(pixelradius='+amt+');}.'+cssClass+' img{filter:progid:DXImageTransform.Microsoft.Blur(pixelradius='+amt+');}</style>'}if(typeof options=='string'){singleSetting($(this),options);return}var settings=$.extend({},this.defaultOptions,options);if(typeof window.blurJSClasses=='undefined'){window.blurJSClasses=[]}return this.each(function(){var $this=$(this),blurredClass=settings.customClass+'-'+settings.radius+'-radius';window.blurJSClasses.push(blurredClass);if(!settings.persist&&$('head style:contains('+settings.customClass+')').length!==0){$('head style:contains('+settings.customClass+')').remove()}if($('head style:contains('+blurredClass+')').length===0){var cssData=css(blurredClass,settings.radius);$(cssData).appendTo('head')}$this.addClass(blurredClass)})}});$.extend({blurjs:function(action){if(action=='reset'){for(var i in window.blurJSClasses){$('.'+window.blurJSClasses[i]).removeClass(window.blurJSClasses[i])}}}})})(jQuery); 

jQuery(document).ready(function(){
	
	// Blur all snippets on page load
	jQuery('pre.socialshare').blurjs({
	    radius: 2
	});

	var buttons = jQuery('.social-likes li span'),
		continueButton = jQuery('.socialshare-continue-button');

	// Click on a share button to close the options dialog
	buttons.on('click',function(){

		var wrapper = jQuery(this).parents('.socialshare-wrapper'),
			overlay = wrapper.find('.socialshare-overlay'),
			codecontent = wrapper.find('pre.socialshare'),
			options = wrapper.find('.socialshare-share-options'),
			continueButton = wrapper.find('.socialshare-continue-button'),
			continuewrapper = wrapper.find('.socialshare-share-continue'),
			copybutton = wrapper.find('.socialshare-copy-snippet'),
			progress = wrapper.find('.socialshare-progress-bar'),
			button = jQuery(this);


		setTimeout(function(){

			progress.slideDown().find('span').animate({
				width: '100%'
			},5000, 'linear', function(){
				progress.slideUp();
			});
			
			setTimeout(function() {
				
				options.fadeOut(600,function(){
					var closenocopy = wrapper.find('.socialshare-close-overlay');

					continuewrapper.fadeIn(600);
					closenocopy.on('click',function(){
						
						overlay.hide();
						wrapper.find('pre.socialshare').blurjs('remove');
						copybutton.fadeIn();
						copybutton.zclip({
							path: '/wp-content/plugins/socialshare-basic/js/ZeroClipboard.swf',
						 	copy: codecontent.text(),
							beforeCopy:function(){
								// Before the copying happens
							},
							afterCopy:function(){
								alert('Snippet copied to clipboard');
							}
						});
						return false;
					});

					if (wrapper.hasClass('socialshare-download-wrapper')) {

						continueButton.on('click',function(){
							overlay.hide();
						});

					} else {

					continueButton.zclip({
						path: '/wp-content/plugins/socialshare-basic/js/ZeroClipboard.swf',
					 	copy: codecontent.text(),
						afterCopy:function(){
							overlay.hide();
							wrapper.find('pre.socialshare').blurjs('remove');
							alert('Snippet copied to clipboard');
							copybutton.fadeIn();
							copybutton.zclip({
								path: '/wp-content/plugins/socialshare-basic/js/ZeroClipboard.swf',
							 	copy: codecontent.text(),
								beforeCopy:function(){
									// Before the copying happens
								},
								afterCopy:function(){
									alert('Snippet copied to clipboard');
								}
							});
						}
					});


					}


				});

			}, 5000);

		},800);

	});

});