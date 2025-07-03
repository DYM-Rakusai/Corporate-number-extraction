
function apiRequest(endPoint, request) {
	return $.ajax({
		url      :  dymline.domain + '/' + endPoint,
		type     : 'POST',
		dataType : 'json',
		data     :  request,
		timeout  :  3000,
		headers  : {
			'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content'),
            'Authorization': 'Bearer ' + $('input[name="api_token"]').data('token'),
        }
	});
}

function getUrlParam()
{
	let urlArg   = new Object;
    let urlParam = location.search.substring(1).split('&');
    for (i = 0; urlParam[i]; i++) {
        let paramInfo        = urlParam[i].split('=');
        urlArg[paramInfo[0]] = paramInfo[1];
    }
    return urlArg;
}


$('a[name=side_accordion]').on('click', function(e) {
	let getClass = $(this).attr('class');
	console.log(getClass);
	if(getClass == 'open' || getClass === undefined) {
		$(this                  ).removeClass('open'     );
		$(this                  ).addClass   ('hide'     );
		$('img.menu-logo'       ).addClass   ('d-none'   );
		$('span.main-menu__text').addClass   ('d-none'   );
		$('aside.sidebar'       ).addClass   ('short_bar');
		$('div.main-container'  ).addClass   ('long'     );
		$(this                  ).text       ('>'        );
	} else {
		$(this                  ).addClass   ('open'     );
		$(this                  ).removeClass('hide'     );
		$('img.menu-logo'       ).removeClass('d-none'   );
		$('span.main-menu__text').removeClass('d-none'   );
		$('aside.sidebar'       ).removeClass('short_bar');
		$('div.main-container'  ).removeClass('long'     );
		$(this                  ).text       ('<'        );
	}
});