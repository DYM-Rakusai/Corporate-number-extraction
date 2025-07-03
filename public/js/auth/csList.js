function apiRequest(endPoint, request) {
	return $.ajax({
		url       : dymline.domain + '/' + endPoint,
		type      : 'POST',
		//dataType: 'json',
		data      : request,
		xhrFields : {
            responseType: 'blob'
        },
		timeout   : 30000,
		headers   : {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
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
		
		let consumerData = {};
		consumerData['memo'] = $('.'+textarea).val();
		let csDetail_num = btn_class.replace('keep-btn', 'csDetail');
		var cs_id = $('.'+csDetail_num).attr('href');
		let id = cs_id.split('=')[1];
		let postData = {
			'consumerData': consumerData,
			'id': id
		};
		console.log(postData);
		
		apiRequest('edit-cs-data', postData).done(function(data) {
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

$('button[name=download-button').on('click', function(){
	$('button[name=download-button').prop("disabled", true);
	let postData  = {};
	let paramData = {};

	let urlParam  = getUrlParam();

	if(urlParam.start_app_date || urlParam.start_interview_date || urlParam.start_interview_decision_date || urlParam.start_hire_date || urlParam.app_name || urlParam.app_tel || urlParam.app_mail || urlParam.app_status || urlParam.interview_set || urlParam.search_alert){
		paramData['start_app_date'] 			   = $('input[name=start_app_date]').val();
		paramData['end_app_date'] 				   = $('input[name=end_app_date]').val();
		paramData['start_interview_date'] 		   = $('input[name=start_interview_date]').val();
		paramData['end_interview_date'] 		   = $('input[name=end_interview_date]').val();
		paramData['start_interview_decision_date'] = $('input[name=start_interview_decision_date]').val();
		paramData['end_interview_decision_date']   = $('input[name=end_interview_decision_date]').val();
		paramData['start_hire_date']               = $('input[name=start_hire_date]').val();
		paramData['end_hire_date']                 = $('input[name=end_hire_date]').val();
		paramData['app_name']                      = $('input[name=app_name]').val();
		paramData['app_tel']                       = $('input[name=app_tel]').val();
		paramData['app_mail']                      = $('input[name=app_mail]').val();
		paramData['app_status']                    = $('select[name=app_status]').val();
		let interview_set                          = $("input[name='interview_set']:checked").val();
		if (interview_set == undefined) {
			paramData['interview_set'] = '';
		}else {
			paramData['interview_set'] = interview_set;
		}
		let search_alert = $('input[name="search_alert"]:checked').val();
		if (search_alert == undefined) {
			paramData['search_alert'] = '';
		} else {
			paramData['search_alert'] = search_alert;
		}
		
		
		postData['param'] = paramData;
	}else{
		paramData = {'start_app_date'               :'',
					 'end_app_date'                 :'',
					 'start_interview_date'         :'',
					 'end_interview_date'           :'',
					 'start_interview_decision_date':'',
					 'end_interview_decision_date'  :'',
					 'start_hire_date'              :'',
					 'end_hire_date'                :'',
					 'app_name'                     :'',
					 'app_tel'                      :'',
					 'app_mail'                     :'',
					 'app_status'                   :'',
					 'interview_set'                :'',
					 'search_alert'                 :''}
		postData['param'] = paramData;
	}	

	let now = new Date();
	postData['Year']  = now.getFullYear();
	postData['Month'] = now.getMonth()+1;
	postData['Date']  = now.getDate();

	apiRequest(
		'download-cs-data', postData
	).done(function(data) {
		const a        = document.createElement('a');
		const url      = window.URL.createObjectURL(data);
		a.href         = url;
		const filename = postData['Year']+'-'+postData['Month']+'-'+postData['Date'];
		a.download     = 'apply_'+filename+'.xlsx';
		document.body.append(a);
		a.click();
		a.remove();
		window.URL.revokeObjectURL(url);
		$('button[name=download-button').css('pointer-events', '');
		$('button[name=download-button').prop("disabled", false);
	}).fail(function(XMLHttpRequest, textStatus, errorThrown) {
		if(textStatus == 'timeout') {
			alert('タイムアウトエラー。\n通信環境を確認して、もう一度試してください。');
			$('button[name=download-button').css('pointer-events', '');
			$('button[name=download-button').prop("disabled", false);
			return;
		}
		alert('処理に失敗しました。\nしばらくしてからもう一度試してください。');
		$('button[name=download-button').css('pointer-events', '');
		$('button[name=download-button').prop("disabled", false);
		return
	});
});



$('button[name=re-add-btn]').on('click', function() {
    if (!confirm("アンケートを送信してもよろしいですか？")) {
        return;
    } 
    $(this).prop("disabled", true);

    let csData = $(this).data('csdata');

    let postData = {
        'applicants': csData,
        'apiToken'  : '$ik.E~~Q-N+7'
    };

    $.ajax({
        url     : dymline.root + '/add-consumer',
        type    : 'POST',
        dataType: 'json',
        data    : postData,
        timeout : 5000,
        headers : {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    }).done(function(data) {
		check_pattern = data.pattern;
		let message;
		if (check_pattern == 'success') {
			location.reload();
			return;
		} else if (check_pattern == 'failure') {
			message = "マッピングエラー";
		} else {
			message = "既にアンケート送信済みです。";
		}
		confirm(message);
		$(this).prop("disabled", false);
		location.reload();

    }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
		confirm('送信に失敗しました。時間をあけて再度お試しください。');
		$(this).prop("disabled", false);
		location.reload();
    })
});
