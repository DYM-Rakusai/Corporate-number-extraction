function apiRequest(endPoint, request) {
	return $.ajax({
		url        : dymline.domain + '/' + endPoint,
		type       : 'POST',
		// dataType: 'json',
		data       : request,
		xhrFields  : {
            responseType: 'blob'
        },
		timeout    : 30000,
		headers    : {
			'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content'),
            'Authorization': 'Bearer ' + $('input[name="api_token"]').data('token'),
        }
	});
}

$(document).on('click', function(event) {
	let btn_class = event.target.className;
	if (btn_class.indexOf('edit-btn_') == 0) {
		//修正ボタンのイベント
		console.log('編集');

		$('.'+btn_class).addClass('d-none');
		let keep_btn = btn_class.replace('edit-btn', 'keep-btn');
		$('.'+keep_btn).removeClass('d-none');
		
		let textarea = btn_class.replace('edit-btn', 'textarea');
		$('.'+textarea).removeClass('d-none');
		let memo = btn_class.replace('edit-btn', 'memo');
		$('.'+memo).addClass('d-none');
	}else if (btn_class.indexOf('keep-btn_') == 0) {
		//保存ボタンのイベント
		console.log('保存');
		$('.'+btn_class).addClass('d-none');

		let textarea = btn_class.replace('keep-btn', 'textarea');
		$('.'+textarea).attr('readonly', true);
		
		let consumerData     = {};
		consumerData['memo'] = $('.'+textarea).val();
		let userDetail_num   = btn_class.replace('keep-btn', 'userDetail');
		var user_id          = $('.'+userDetail_num).attr('href');
		let id               = user_id.split('=')[1];
		let postData         = {
			'consumerData': consumerData,
			'id': id
		};
		console.log(postData);
		
		apiRequest('edit-user-data', postData).done(function(data) {
			console.log('送信成功');
			location.reload();
			$(this).prop("disabled", false);
		}).fail(function(XMLHttpRequest, textStatus, errorThrown) {
			console.log('送信失敗');
			location.reload();
			$(this).prop("disabled", false);
		});
	}
});
