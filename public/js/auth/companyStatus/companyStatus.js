// $('input[name=interview_setting_method]').on('change', function(e) {
//     let interviewSettingMethod = $(this).val();
//     console.log(interviewSettingMethod);
//     if(interviewSettingMethod == 'schedule_date') {
//         $('div.interview_date_set').removeClass('d-none');
//     } else {
//         $('div.interview_date_set').addClass   ('d-none');
//     }
// });


$('button[name="interview_set_btn"]').on('click', function(e) {
    $(this).prop("disabled", true);
    let interviewUserId = $('input[name="interview_user"]:checked').val();
    console.log(interviewUserId);
    if (!interviewUserId) {
        alert('面接担当者を選択してください。');
        $(this).prop("disabled", false);
        return;
    }

    // let interviewSettingMethod = $('input[name="interview_setting_method"]:checked').val();
    // if( interviewSettingMethod === undefined) {
    //     alert('面接方法を選択してください。');
    //     $(this).prop("disabled", false);
    //     return;
    // }

    // let interviewDate = null;
    // let interviewTime = null;

    let interviewSettingMethod = 'schedule_date';
    
    if( interviewSettingMethod == 'schedule_date') {       
	    interviewDate = $('input[name="interview_date"]'               ).val();
	    interviewTime = $('select[name=interview_time] option:selected').val();

	    if(interviewDate === '' || interviewTime === '-') {
	        alert('面接の日付・開始時刻を入力してください。');
	        $(this).prop("disabled", false);
	        return;
	    }

        var interviewDateTime = new Date(interviewDate + ' ' + interviewTime);
        var now               = new Date();

        if(interviewDateTime <  now) {
	        alert('現在以降の日時を入力してください。');
	        $(this).prop("disabled", false);
	        return;
	    }
    }

    let urlParam = getUrlParam();

    let postData = {
		'interviewUserId'        :  interviewUserId,
		'interviewSettingMethod' :  interviewSettingMethod,
        'interviewDate'          :  interviewDate,
        'interviewTime'          :  interviewTime,
        'consumerId'             :  urlParam.consumerId,
	    'apiToken'               : '$ik.E~~Q-N+7'
	}

    let result = confirm('面接設定に間違いはありませんか？');
    if(!result) {
   		$('button[name="interview_set_btn"]').prop("disabled", false);
        return;
    }

    apiRequest('get-company-answer', postData).done(function(data) {
        if (data.status === 'notDecide') {
            alert('この日程はすでに埋まっています。');
            $('button[name="interview_set_btn"]').prop("disabled", false);
            return;
        }

        if (data.status === 'error') {
            alert(data.message || 'エラーが発生しました。');
            $('button[name="interview_set_btn"]').prop("disabled", false);
            return;
        }
        location.reload();
    }).fail(function(xhr, textStatus, errorThrown) {
        console.error("status:", xhr.status);
        console.error("response:", xhr.responseText);
        alert('処理に失敗しました。\nしばらくしてからもう一度試してください。');
        $('button[name="interview_set_btn"]').prop("disabled", false);
    });
});
