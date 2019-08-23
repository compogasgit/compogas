/* Device */
(function(){var a,b,c,d,e,f,g,h,i,j;b=window.device,a={},window.device=a,d=window.document.documentElement,j=window.navigator.userAgent.toLowerCase(),a.ios=function(){return a.iphone()||a.ipod()||a.ipad()},a.iphone=function(){return!a.windows()&&e("iphone")},a.ipod=function(){return e("ipod")},a.ipad=function(){return e("ipad")},a.android=function(){return!a.windows()&&e("android")},a.androidPhone=function(){return a.android()&&e("mobile")},a.androidTablet=function(){return a.android()&&!e("mobile")},a.blackberry=function(){return e("blackberry")||e("bb10")||e("rim")},a.blackberryPhone=function(){return a.blackberry()&&!e("tablet")},a.blackberryTablet=function(){return a.blackberry()&&e("tablet")},a.windows=function(){return e("windows")},a.windowsPhone=function(){return a.windows()&&e("phone")},a.windowsTablet=function(){return a.windows()&&e("touch")&&!a.windowsPhone()},a.fxos=function(){return(e("(mobile;")||e("(tablet;"))&&e("; rv:")},a.fxosPhone=function(){return a.fxos()&&e("mobile")},a.fxosTablet=function(){return a.fxos()&&e("tablet")},a.meego=function(){return e("meego")},a.cordova=function(){return window.cordova&&"file:"===location.protocol},a.nodeWebkit=function(){return"object"==typeof window.process},a.mobile=function(){return a.androidPhone()||a.iphone()||a.ipod()||a.windowsPhone()||a.blackberryPhone()||a.fxosPhone()||a.meego()},a.tablet=function(){return a.ipad()||a.androidTablet()||a.blackberryTablet()||a.windowsTablet()||a.fxosTablet()},a.desktop=function(){return!a.tablet()&&!a.mobile()},a.television=function(){var a;for(television=["googletv","viera","smarttv","internet.tv","netcast","nettv","appletv","boxee","kylo","roku","dlnadoc","roku","pov_tv","hbbtv","ce-html"],a=0;a<television.length;){if(e(television[a]))return!0;a++}return!1},a.portrait=function(){return window.innerHeight/window.innerWidth>1},a.landscape=function(){return window.innerHeight/window.innerWidth<1},a.noConflict=function(){return window.device=b,this},e=function(a){return-1!==j.indexOf(a)},g=function(a){var b;return b=new RegExp(a,"i"),d.className.match(b)},c=function(a){var b=null;g(a)||(b=d.className.replace(/^\s+|\s+$/g,""),d.className=b+" "+a)},i=function(a){g(a)&&(d.className=d.className.replace(" "+a,""))},a.ios()?a.ipad()?c("ios ipad tablet"):a.iphone()?c("ios iphone mobile"):a.ipod()&&c("ios ipod mobile"):a.android()?c(a.androidTablet()?"android tablet":"android mobile"):a.blackberry()?c(a.blackberryTablet()?"blackberry tablet":"blackberry mobile"):a.windows()?c(a.windowsTablet()?"windows tablet":a.windowsPhone()?"windows mobile":"desktop"):a.fxos()?c(a.fxosTablet()?"fxos tablet":"fxos mobile"):a.meego()?c("meego mobile"):a.nodeWebkit()?c("node-webkit"):a.television()?c("television"):a.desktop()&&c("desktop"),a.cordova()&&c("cordova"),f=function(){a.landscape()?(i("portrait"),c("landscape")):(i("landscape"),c("portrait"))},h=Object.prototype.hasOwnProperty.call(window,"onorientationchange")?"orientationchange":"resize",window.addEventListener?window.addEventListener(h,f,!1):window.attachEvent?window.attachEvent(h,f):window[h]=f,f(),"function"==typeof define&&"object"==typeof define.amd&&define.amd?define(function(){return a}):"undefined"!=typeof module&&module.exports?module.exports=a:window.device=a}).call(this);

/* Aim */
!function(e){function t(t){var n=e(this),i=null,o=[],u=null,r=null,c=e.extend({rowSelector:"> li",submenuSelector:"*",submenuDirection:"right",tolerance:75,enter:e.noop,exit:e.noop,activate:e.noop,deactivate:e.noop,exitMenu:e.noop},t),l=2,f=100,a=function(e){o.push({x:e.pageX,y:e.pageY}),o.length>l&&o.shift()},s=function(){r&&clearTimeout(r),c.exitMenu(this)&&(i&&c.deactivate(i),i=null)},h=function(){r&&clearTimeout(r),c.enter(this),v(this)},m=function(){c.exit(this)},x=function(){y(this)},y=function(e){e!=i&&(i&&c.deactivate(i),c.activate(e),i=e)},v=function(e){var t=p();t?r=setTimeout(function(){v(e)},t):y(e)},p=function(){function t(e,t){return(t.y-e.y)/(t.x-e.x)}if(!i||!e(i).is(c.submenuSelector))return 0;var r=n.offset(),l={x:r.left,y:r.top-c.tolerance},a={x:r.left+n.outerWidth(),y:l.y},s={x:r.left,y:r.top+n.outerHeight()+c.tolerance},h={x:r.left+n.outerWidth(),y:s.y},m=o[o.length-1],x=o[0];if(!m)return 0;if(x||(x=m),x.x<r.left||x.x>h.x||x.y<r.top||x.y>h.y)return 0;if(u&&m.x==u.x&&m.y==u.y)return 0;var y=a,v=h;"left"==c.submenuDirection?(y=s,v=l):"below"==c.submenuDirection?(y=h,v=s):"above"==c.submenuDirection&&(y=l,v=a);var p=t(m,y),b=t(m,v),d=t(x,y),g=t(x,v);return d>p&&b>g?(u=m,f):(u=null,0)};n.mouseleave(s).find(c.rowSelector).mouseenter(h).mouseleave(m).click(x),e(document).mousemove(a)}e.fn.menuAim=function(e){return this.each(function(){t.call(this,e)}),this}}(jQuery);
function getURLVar(key) {
	var value = [];

	var query = String(document.location).split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	} else { 			// Изменения для seo_url от Русской сборки OpenCart 2x
		var query = String(document.location.pathname).split('/');
		if (query[query.length - 1] == 'cart') value['route'] = 'checkout/cart';
		if (query[query.length - 1] == 'checkout') value['route'] = 'checkout/checkout';

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}

function getURLVar(key) {
	var value = [];

	var query = String(document.location).split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	} else { 			// Изменения для seo_url от Русской сборки OpenCart 2x
		var query = String(document.location.pathname).split('/');
		if (query[query.length - 1] == 'cart') value['route'] = 'checkout/cart';
		if (query[query.length - 1] == 'checkout') value['route'] = 'checkout/checkout';

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}


$(function () {

	$(window).on('scroll', function() {
		if ( $(this).scrollTop() > 200 ) {
			$("#scrll-on-top").show()
		} else {
			$("#scrll-on-top").hide()
		}
	});

	/* Menu */
	$('#ft_menu .category-menu-list').menuAim({
		rowSelector: "> .category-menu-item",
		submenuSelector: "*",
		activate: activateSubmenu,
		deactivate: deactivateSubmenu,
		exitMenu: exitMenu
	});
	function activateSubmenu(row) {
		$(row).addClass('hover')
	}
	function deactivateSubmenu(row) {
		$(row).removeClass('hover')
	}
	function exitMenu(row) {
		return true
	}

	$('#ft_menu > button').on('click', function () {
		if (!$(this).parent().hasClass('show') && ($(window).width() < 768)) {
			console.log($(window).width());
			$('html, body').animate({
				scrollTop: $(this).offset().top - 10
			}, 250);
		}
	});

	$(window).resize(function () {
		if (typeof menu_open_holder == 'function') {
			menu_open_holder();
		}
	});
	$(window).on('load', function () {
		if (typeof menu_open_holder == 'function') {
			menu_open_holder();
		}
	});
	$(function () {
		if (typeof menu_open_holder == 'function') {
			menu_open_holder();
		}
	});


	// Currency
	$('#ft-form-currency .currency-select').on('click', function(e) {
		e.preventDefault();

		$('#ft-form-currency input[name=\'code\']').val($(this).find('input').attr('name'));

		$('#ft-form-currency').submit();
	});

	// Language
	$('#ft-form-language .language-select').on('click', function(e) {
		e.preventDefault();

		$('#ft-form-language input[name=\'code\']').val($(this).find('input').attr('name'));

		$('#ft-form-language').submit();
	});


	/* Search */
	$('#ft_search input[name=\'search\']').parent().find('.search-button').on('click', function(e) {

		var url = $('base').attr('href') + 'index.php?route=product/search',
				value = $('header #ft_search input[name=\'search\']').val(),
				category_id = $('header #ft_search input[name=\'category_id\']').val();

		if (value) {
			url += '&search=' + encodeURIComponent(value);
		}

		if (category_id > 0) {
			url += '&category_id=' + encodeURIComponent(category_id);
		}

		url += '&sub_category=true';

		location = url;
	});

	$('#ft_search input[name=\'search\']').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('header #ft_search input[name=\'search\']').parent().find('.search-button').trigger('click');
		}
	});

	$('#ft_search .select-list a').click(function(){
		$('.select-list > a').removeClass('active');
		$(this).addClass('active');
		$('.select-button .select-text').text($(this).text());
		$('.selected-category').val($(this).attr('data-category'));
	});


	/* contacts */
	contacts_modal_open = false;

	$('#ft-header-contacts').on('shown.bs.modal', function (e) {
		history.pushState(null, null, location.href + '#contacts');
		contacts_modal_open = true;
	});

	$('#ft-header-contacts').on('hidden.bs.modal', function (e) {
		$(this).find('.alert').remove();

		if (contacts_modal_open == true) {
			window.history.back();
			contacts_modal_open = false;
		}
	});

	/* Cart */
	cart_modal_open = false;

	$('#ft-popup-cart').on('shown.bs.modal', function (e) {
		history.pushState(null, null, location.href + '#cart');
		cart_modal_open = true;
	});

	$('#ft-popup-cart').on('hidden.bs.modal', function (e) {
		$(this).find('.alert').remove();

		if (cart_modal_open == true) {
			window.history.back();
			cart_modal_open = false;
		}
	});

});



var ft_countupd = function (step, minimum, field) {

	var count = parseInt($(field).val()) + Number(step);

	count = count < Number(minimum) ? Number(minimum) : count;

	$(field).val(count);
	$(field).change();

	return false;
}

/* History button close modal */
addEventListener("popstate",function(e){
	if ($('body').hasClass('modal-open') && !$('body').hasClass('psw-open')) {

		$('#ft-header-contacts').modal('hide');
		contacts_modal_open = false;

		$('#ft-popup-cart').modal('hide');
		cart_modal_open = false;

		$('#ft-modal-qview').modal('hide');
		qv_modal_open = false;

		$('#ft-modal-fastorder').modal('hide');
		fo_modal_open = false;
	}
},false);


$(function () {

	old_location_href_str = location.href;
	old_location_href_arr = old_location_href_str.split('#');

	if (old_location_href_arr.length > 1) {

		hash_str = old_location_href_arr[old_location_href_arr.length - 1];
		new_location_href = old_location_href_str.substr(0, old_location_href_str.length - hash_str.length - 1);

		if (hash_str == 'cart') {

			history.replaceState(null, null, new_location_href);
			$('#ft-popup-cart').modal('show');

		} else if (hash_str == 'contacts') {

			history.replaceState(null, null, new_location_href);
			$('#ft-header-contacts').modal('show');

		} else {

			hash_arr = hash_str.split('=');

			if (hash_arr.length > 1 && hash_arr[0] == 'view') {

				history.replaceState(null, null, new_location_href);
				ft_qview(hash_arr[1]);

			} else if (hash_arr.length > 1 && hash_arr[0] == 'fastorder') {

				history.replaceState(null, null, new_location_href);
				ft_fastorder(hash_arr[1]);

			}
		}
	}
});

/* Cart add remove functions */
var cart = {
	'add': function(product_id, quantity) {
		$.ajax({
			url: 'index.php?route=extension/module/frametheme/ft_cart/add',
			type: 'post',
			data: 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				var loading_text = $('#cart > button').attr('data-loading');
				$('#cart').addClass('loading');
				$('#cart > button').attr('disabled', 'disabled');
				$('#cart > button #cart-total').html('<span class="loading-wrapper">' + loading_text + '</span>');
				$('#ft-popup-cart').modal('show');
			},
			success: function(json) {
				$('.alert-dismissible').remove();
				if (json['redirect']) {
					location = json['redirect'];
				}
				if (json['success']) {
					setTimeout(function () {
						$.ajax({
						url: 'index.php?route=extension/module/frametheme/ft_cart/info',
						type: 'post',
						dataType: 'html',
						complete: function() {
							$('#cart').removeClass('loading');
							$('#cart > button').removeAttr('disabled');
						},
						success: function(data){
							var data_alert 	= '<div class="alert alert-light">';
									data_alert += 	'<div class="row no-gutters">';
									data_alert += 		'<div class="col-auto">';
									data_alert += 			'<i class="fa fa-fw fa-check mr-2"></i>';
									data_alert += 		'</div>';
									data_alert += 		'<div class="col">';
									data_alert += 			json['success'];
									data_alert += 		'</div>';
									data_alert += 		'<div class="col-auto">';
									data_alert += 			'<button type="button" class="close" data-dismiss="alert">&times;</button>';
									data_alert += 		'</div>';
									data_alert += 	'</div>';
									data_alert += '</div>';

							$('#cart .cart-list').before(data_alert);
							$('#cart > button #cart-total').html(json['total']);
							$('#cart .cart-list').html($(data).find('.cart-list').html());
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
					}, 100);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'update': function(key, quantity) {
		$.ajax({
			url: 'index.php?route=extension/module/frametheme/ft_cart/edit',
			type: 'post',
			data: 'key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
			setTimeout(function () {
					var loading_text = $('#cart > button').attr('data-loading');
					$('#cart').addClass('loading');
					$('#cart > button').attr('disabled', 'disabled');
					$('#cart > button #cart-total').html('<span class="loading-wrapper">' + loading_text + '</span>');
					$('#ft-popup-cart').find('.alert').remove();
				}, 99);
			},
			success: function(json) {
				setTimeout(function () {
					if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
						location = 'index.php?route=checkout/cart';
					} else {
						$.ajax({
							url: 'index.php?route=extension/module/frametheme/ft_cart/info',
							type: 'post',
							dataType: 'html',
							complete: function() {
								$('#cart').removeClass('loading');
								$('#cart > button').removeAttr('disabled');
							},
							success: function(data){
								$('#cart > button #cart-total').html(json['total']);
								$('#cart .cart-list').html($(data).find('.cart-list').html());
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});
					}
				}, 100);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'updatePopup': function(key, quantity) {
		$.ajax({
			url: 'index.php?route=extension/module/frametheme/ft_cart/editPopup',
			type: 'post',
			data: 'key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				setTimeout(function () {
					var loading_text = $('#cart > button').attr('data-loading');
					$('#cart').addClass('loading');
					$('#cart > button').attr('disabled', 'disabled');
					$('#cart > button #cart-total').html('<span class="loading-wrapper">' + loading_text + '</span>');
					$('#ft-popup-cart').find('.alert').remove();
				}, 99);
			},
			success: function(json) {
				setTimeout(function () {
					if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
						location = 'index.php?route=checkout/cart';
					} else {
						$.ajax({
							url: 'index.php?route=extension/module/frametheme/ft_cart/info',
							type: 'post',
							dataType: 'html',
							complete: function() {
								$('#cart').removeClass('loading');
								$('#cart > button').removeAttr('disabled');
							},
							success: function(data){
								$('#cart > button #cart-total').html(json['total']);
								$('#cart .cart-list').html($(data).find('.cart-list').html());
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});
					}
				}, 100);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=extension/module/frametheme/ft_cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				setTimeout(function () {
					var loading_text = $('#cart > button').attr('data-loading');
					$('#cart').addClass('loading');
					$('#cart > button').attr('disabled', 'disabled');
					$('#cart > button #cart-total').html('<span class="loading-wrapper">' + loading_text + '</span>');
					$('#ft-popup-cart').find('.alert').remove();
				}, 99);
			},
			success: function(json) {
				setTimeout(function () {
					if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
						location = 'index.php?route=checkout/cart';
					} else {
						$.ajax({
							url: 'index.php?route=extension/module/frametheme/ft_cart/info',
							type: 'post',
							dataType: 'html',
							complete: function() {
								$('#cart').removeClass('loading');
								$('#cart > button').removeAttr('disabled');
							},
							success: function(data){
								$('#cart > button #cart-total').html(json['total']);
								$('#cart .cart-list').html($(data).find('.cart-list').html());
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});
					}
				}, 100);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

var voucher = {
	'add': function() {

	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				setTimeout(function () {
					var loading_text = $('#cart > button').attr('data-loading');
					$('#cart').addClass('loading');
					$('#cart > button').attr('disabled', 'disabled');
					$('#cart > button #cart-total').html('<span class="loading-wrapper">' + loading_text + '</span>');
					$('#ft-popup-cart').find('.alert').remove();
				}, 99);
			},
			success: function(json) {
				setTimeout(function () {
					if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
						location = 'index.php?route=checkout/cart';
					} else {
						$.ajax({
							url: 'index.php?route=extension/module/frametheme/ft_cart/info',
							type: 'post',
							dataType: 'html',
							complete: function() {
								$('#cart').removeClass('loading');
								$('#cart > button').removeAttr('disabled');
							},
							success: function(data){
								$('#cart > button #cart-total').html(json['total']);
								$('#cart .cart-list').html($(data).find('.cart-list').html());
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});
					}
				}, 100);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

var wishlist = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?route=account/wishlist/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert-dismissible').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					$('body').prepend('<div style="width: 300px;position: fixed;z-index:9999; top: 10px;right: 10px;"><div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div></div>');
				}

				$('#wishlist-total').html(json['total']);
				$('#wishlist-total').attr('title', json['total']);

				// $('html, body').animate({ scrollTop: 0 }, 'slow');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function() {

	}
}

var compare = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?route=product/compare/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert-dismissible').remove();

				if (json['success']) {
					$('body').prepend('<div style="width: 300px;position: fixed;z-index:9999; top: 10px;right: 10px;"><div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div></div>');

					$('#compare-total').html(json['total']);

					// $('html, body').animate({ scrollTop: 0 }, 'slow');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function() {

	}
}

var ft_qview = function(product_id) {

	$.ajax({
		url: 'index.php?route=extension/module/frametheme/ft_qview&product_id=' + product_id,
		type: 'post',
		dataType: 'html',
		beforeSend: function() {

			$('#ft-modal-qview, .modal-backdrop').remove();

			html  = '<div id="ft-modal-qview" class="modal fade" tabindex="-1" role="dialog">';
			html += '  <div class="modal-dialog modal-lg" role="document">';
			html += '    <div class="modal-content" id="qview-product-' + product_id + '">';
			html += '      <div class="modal-load-mask text-center p-5 text-muted">';
			html += '				 <div class="d-table w-100 h-100">';
			html += '					 <div class="d-table-cell w-100 h-100 align-middle">';
			html += '    	 			 <span class="si">';
			html += '							 <svg class="spinner" width="24px" height="24px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">';
			html += '								 <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>';
			html += '							 </svg>';
			html += '						 </span>';
			html += '    	 		 </div>';
			html += '    	 	 </div>';
			html += '    	 </div>';
			html += '    </div>';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#ft-modal-qview').modal('show');

		},
		success: function(data) {
			$('#ft-modal-qview .modal-content').html(data);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});

	qv_modal_open = false;

	$('#ft-modal-qview').on('shown.bs.modal', function (e) {
		history.pushState(null, null, location.href + '#view=' + product_id);
		qv_modal_open = true;
	});

	$('#ft-modal-qview').on('hidden.bs.modal', function (e) {
		if (qv_modal_open == true) {
			window.history.back();
			qv_modal_open = false;
		}
	});

}

var ft_fastorder = function(product_id) {

	$.ajax({
		url: 'index.php?route=extension/module/frametheme/ft_fastorder&product_id=' + product_id,
		type: 'post',
		dataType: 'html',
		beforeSend: function() {

			$('#ft-modal-fastorder, .modal-backdrop').remove();

			html  = '<div id="ft-modal-fastorder" class="modal fade" tabindex="-1" role="dialog">';
			html += '  <div class="modal-dialog" role="document">';
			html += '    <div class="modal-content" id="fastorder-product-' + product_id + '">';
			html += '      <div class="modal-load-mask text-center p-5 text-muted">';
			html += '				 <div class="d-table w-100 h-100">';
			html += '					 <div class="d-table-cell w-100 h-100 align-middle">';
			html += '    	 			 <span class="si">';
			html += '							 <svg class="spinner" width="24px" height="24px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">';
			html += '								 <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>';
			html += '							 </svg>';
			html += '						 </span>';
			html += '    	 		 </div>';
			html += '    	 	 </div>';
			html += '    	 </div>';
			html += '    </div>';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			if ($('#ft-modal-qview').is('.show')) {
				console.log('we are here');
				$('#ft-modal-qview').modal('hide');
				$('#ft-modal-qview').on('hidden.bs.modal', function (e) {
					$('#ft-modal-fastorder').modal('show');
				});
			} else {
				$('#ft-modal-fastorder').modal('show');
			}

		},
		success: function(data) {
			$('#ft-modal-fastorder .modal-content').html(data);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});

	fo_modal_open = false;

	$('#ft-modal-fastorder').on('shown.bs.modal', function (e) {
		history.pushState(null, null, location.href + '#fastorder=' + product_id);
		fo_modal_open = true;
	});

	$('#ft-modal-fastorder').on('hidden.bs.modal', function (e) {
		if (fo_modal_open == true) {
			window.history.back();
			fo_modal_open = false;
		}
	});

}

function list_view() {
	$('#content .product-grid > .clearfix').remove();

	$('#content .row > .product-grid').attr('class', 'product-layout product-items product-list col-12');
	$('#ft-grid-view').removeClass('active');
	$('#ft-list-view').addClass('active');

	localStorage.setItem('display', 'list');
}


function grid_view() {

	var cols = $('#column-right, #column-left').length;

	if (cols == 2) {
		$('#content .product-list').attr('class', 'product-layout product-items product-grid col-xl-4 col-lg-6 col-md-4 col-sm-4 col-6');
	} else if (cols == 1) {
		$('#content .product-list').attr('class', 'product-layout product-items product-grid col-xl-3 col-lg-4 col-sm-4 col-6');
	} else {
		$('#content .product-list').attr('class', 'product-layout product-items product-grid col-lg-3 col-md-4 col-sm-4 col-6');
	}

	$('#ft-list-view').removeClass('active');
	$('#ft-grid-view').addClass('active');

	localStorage.setItem('display', 'grid');
}

$(function () {

	if (localStorage.getItem('display') == 'list') {
		list_view()
	} else {
		grid_view()
	}

});


/* Agree to Terms */

$(document).undelegate('.agree', 'click');

$(document).delegate('.agree', 'click', function(e) {
	e.preventDefault();

	$('#modal-agree').remove();

	var element = this;

	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-agree" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header no-gutters">';
			html += '        <div class="col"><h5 class="modal-title">' + $(element).text() + '</h5></div><div class="col-auto"><div class="h5 modal-title modal-h-icon ml-2" data-dismiss="modal"><svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path></svg></div></div>';
			html += '      </div>';
			html += '      <div class="modal-body">' + data + '</div>';
			html += '    </div>';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#modal-agree').modal('show');
		}
	});
});




// Autocomplete */
(function($) {
	$.fn.autocomplete = function(option) {
		return this.each(function() {
			this.timer = null;
			this.items = new Array();

			$.extend(this, option);

			$(this).attr('autocomplete', 'off');

			// Focus
			$(this).on('focus', function() {
				this.request();
			});

			// Blur
			$(this).on('blur', function() {
				setTimeout(function(object) {
					object.hide();
				}, 200, this);
			});

			// Keydown
			$(this).on('keydown', function(event) {
				switch(event.keyCode) {
					case 27: // escape
						this.hide();
						break;
					default:
						this.request();
						break;
				}
			});

			// Click
			this.click = function(event) {
				event.preventDefault();

				value = $(event.target).attr('data-value');

				if (value && this.items[value]) {
					this.select(this.items[value]);
				}
			}

			// Show
			this.show = function() {
				var pos = $(this).position();

				$(this).siblings('div.dropdown-menu').css({
					top: pos.top + $(this).outerHeight(),
					left: pos.left
				});

				$(this).siblings('div.dropdown-menu').show();
			}

			// Hide
			this.hide = function() {
				$(this).siblings('div.dropdown-menu').hide();
			}

			// Request
			this.request = function() {
				clearTimeout(this.timer);

				this.timer = setTimeout(function(object) {
					object.source($(object).val(), $.proxy(object.response, object));
				}, 200, this);
			}

			// Response
			this.response = function(json) {
				html = '';

				if (json.length) {
					for (i = 0; i < json.length; i++) {
						this.items[json[i]['value']] = json[i];
					}

					for (i = 0; i < json.length; i++) {
						if (!json[i]['category']) {
							html += '<a class="dropdown-item"  href="#" data-value="' + json[i]['value'] + '">' + json[i]['label'] + '</a>';
						}
					}

					// Get all the ones with a categories
					var category = new Array();

					for (i = 0; i < json.length; i++) {
						if (json[i]['category']) {
							if (!category[json[i]['category']]) {
								category[json[i]['category']] = new Array();
								category[json[i]['category']]['name'] = json[i]['category'];
								category[json[i]['category']]['item'] = new Array();
							}

							category[json[i]['category']]['item'].push(json[i]);
						}
					}

					for (i in category) {
						html += '<h6 class="dropdown-header">' + category[i]['name'] + '</h6>';

						for (j = 0; j < category[i]['item'].length; j++) {
							html += '<a class="dropdown-item" href="#" data-value="' + category[i]['item'][j]['value'] + '">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a>';
						}
					}
				}

				if (html) {
					this.show();
				} else {
					this.hide();
				}

				$(this).siblings('div.dropdown-menu').html(html);
			}

			$(this).after('<div class="dropdown-menu"></div>');
			$(this).siblings('div.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));

		});
	}
})(window.jQuery);
