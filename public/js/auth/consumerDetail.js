$('button[name=interview_change_btn]').on('click', function(e) {
    $('div.set_title_frame').removeClass('d-none');
    $('div.interview_div').removeClass('d-none');
    $('div.interview_change_frame').addClass('d-none');
});


$('button[name=interview_set_btn]').on('click', function(e) {
    $(this).prop("disabled", true);
    let interviewSet  = $('select[name=interview_set] option:selected').val();
    let remindSet     = $('input[name=remind_set]:checked').val();
    let postData      = {};
    let interviewDate = null;
    let interviewTime = null;
    if(interviewSet == '') {
        alert('選択してください。');
        $(this).prop("disabled", false);
        return;
    }
    if(interviewSet  != '' && interviewSet != '面接キャンセル') {
        interviewDate = $('input[name="interview_date"]').val();
	    interviewTime = $('select[name=interview_time] option:selected').val();
        var interviewDateTime = new Date(interviewDate + ' ' + interviewTime);
        var now = new Date();
	    if(interviewDate === '') {
	        alert('面接の日付を入力してください。');
	        $(this).prop("disabled", false);
	        return;
	    }
        if(interviewDateTime <  now) {
	        alert('現在以降の日時を入力してください。');
	        $(this).prop("disabled", false);
	        return;
	    }
	    if(interviewTime === '-') {
	        alert('面接の時刻を選択してください。');
	        $(this).prop("disabled", false);
	        return;
	    }
        if(remindSet === undefined) {
            alert('リマインドの設定を選択してください。');
            $(this).prop("disabled", false);
            return;
        }
        interviewSet = `${interviewDate} ${interviewTime}:00`;
        postData['remindSet'] = remindSet;
    }
    postData['interviewSet'] = interviewSet;
    let urlParam = getUrlParam();
    postData['id'] = urlParam.id;

    var now = new Date();
    postData['interview_decision_date'] = now.getFullYear() + '-' + (now.getMonth()+1).toString().padStart(2, '0') + '-'
                                          + now.getDate().toString().padStart(2, '0') + ' ' +  now.getHours().toString().padStart(2, '0') + ':'
                                          + now.getMinutes().toString().padStart(2, '0') + ':' + now.getSeconds().toString().padStart(2, '0');

    let result = confirm('入力内容を反映してよろしいですか？');
    if(!result) {
   		$(this).prop("disabled", false);
        
        return;
    }

    apiRequest('change-schedule', postData).done(function(data) {
        if(data.error && data.error == 'filled') {
            alert('この日程は既に埋まっています。');
            $('button[name=interview_set_btn]').prop("disabled", false);
        } else {
            location.reload();
        }
	}).fail(function(XMLHttpRequest, textStatus, errorThrown) {
		location.reload();
	});
});

$('button[name=add_black_list]').on('click', function(e) {
    $(this).prop("disabled", true);
    
    let postData   = {};
    let urlParam   = getUrlParam();
    postData['id'] = urlParam.id;
    postData['apiToken'] = '$ik.E~~Q-N+7';

    let result = confirm('応募者をブラックリストに登録しますか？');
    if(!result) {
        $(this).prop("disabled", false);
        return;
    }

    apiRequest('add-black-list-for-csDetail', postData).done(function(data) {
        location.reload();
    }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        location.reload();
    });
});

$('button[name=delete_black_list]').on('click', function(e) {
    $(this).prop("disabled", true);
    
    let postData         = {};
    let urlParam         = getUrlParam();
    postData['id']       = urlParam.id;
    postData['apiToken'] = '$ik.E~~Q-N+7';

    let result = confirm('応募者をブラックリストから削除しますか？');
    if(!result) {
        $(this).prop("disabled", false);
        return;
    }

    apiRequest('delete-black-list-for-csDetail', postData).done(function(data) {
        location.reload();
    }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        location.reload();
    });
});

$('select[name=interview_set]').on('change', function(e) {
    let interviewSet = $('select[name=interview_set] option:selected').val();
    if(interviewSet == '' || interviewSet == '面接キャンセル') {
        $('div.interview_date_set').addClass('d-none');
        $('div.remind_set_frame').addClass('d-none');
        $('div.interview_way_set_frame').addClass('d-none');
    } else {
        $('div.interview_date_set').removeClass('d-none');
        $('div.remind_set_frame').removeClass('d-none');
        $('div.interview_way_set_frame').removeClass('d-none');
    }
    $('div.interview_set_btn_frame').removeClass('d-none');
});




