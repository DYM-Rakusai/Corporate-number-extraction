$('button[name=confirm_btn]').on('click', function(e) {
	let answerData = getFormData('confirm');

	console.log('answerData');
	console.log( answerData );
	
	if(Object.keys(answerData).length == 0) {
		return;
	}

	for (let answerKey in answerData) {
		let answerVal     = answerData[answerKey];
		let $confirmBlock = $('.' + answerKey + '_content.work_term_cnfirm');
		let $confirmSpan  = $('span[name=confirm_answer_' + answerKey + ']');
		
        if (answerVal !== '' && answerVal !== undefined) {
            $confirmSpan .text       (answerVal);
			$confirmBlock.removeClass('d-none');
        } else {
			$confirmBlock.addClass   ('d-none');
        }
	}
	let birthday = answerData['birthday']['year'] + '年' + answerData['birthday']['month'] + '月' + answerData['birthday']['day'] + '日';
	$('span[name=confirm_answer_birthday]').text(birthday);

	$('div.worksheet_form'   ).addClass   ('d-none');
	$('div.worksheet_confirm').removeClass('d-none');
});

$('button[name=return_btn]').on('click', function(e) {
	$('div.worksheet_form'   ).removeClass('d-none');
	$('div.worksheet_confirm').addClass   ('d-none');
});

$('button[name=complete_btn]').on('click', function(e) {
	if (!confirm("回答を送信すると再選択や再応募ができなくなります。\nこのまま送信してもよろしいですか？")){
		return;
	} 
	$(this).prop("disabled", true);
	let answerData = getFormData();
	if(Object.keys(answerData).length == 0) {
		$(this).prop("disabled", false);
		return;
	}
	let urlParam = getUrlParam();
	//APIリクエスト（合否の判定を行う）
	let postData = {
		'answerData':  answerData         ,
		'consumerId':  urlParam.consumerId,
		'hashCs'    :  urlParam.hashCs    ,
		'apiToken'  : '$ik.E~~Q-N+7'
	};
	let check_pattern;
	$.ajax({
		url      :  dymline.root + '/check-worksheet-answer',
		type     : 'POST'                                   ,
		dataType : 'json'                                   ,
		data     :  postData                                ,
		timeout  :  10000                                   ,
		headers  : {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
		}
	}).done(function(data) {
		check_pattern = data.pattern;
		$('div.worksheet_confirm').addClass('d-none');
		if(check_pattern == 'success') {
			$('div.schedule_confirm').removeClass('d-none');
			$('.highlight'          ).addClass   ('d-none');
		} else {
			requestAnswer(answerData, check_pattern, null, null);
		}
	}).fail(function(XMLHttpRequest, textStatus, errorThrown) {
		if(textStatus == 'timeout') {
			location.reload();
		}
	}).always(function() {
		$('button[name=complete_btn]').prop("disabled", false);
	});
});

function getUrlParam() {
    let urlArg   = new Object;
    let urlParam = location.search.substring(1).split('&');
    for (i = 0; urlParam[i]; i++) {
        let paramInfo        = urlParam[i].split('=');
        urlArg[paramInfo[0]] = paramInfo[1];
    }
    return urlArg;
}

function getFormData(pattern = 'null')
{
	let formData = {};
	let isError  = false;
	$('div.form_content_item').each(function(i, elem) {
		if(isError) {
			return;
		}
		let type       = $(this).data('type');
		let formName   = $(this).data('form_name');
		let formTitle  = $(this).data('form_title');
		let isRequired = $(this).closest('.choice_content').not('.d-none').length > 0;
		let formVal;

		console.log(type);
		console.log(formName);
		console.log(formTitle);
		
		if(type == 'text') {
			formVal = getText(formName);
		} else if(type == 'radio') {
			formVal = getRadio(formName, pattern);
		} else if(type == 'checkbox') {
			formVal = getCheckbox(formName, formTitle);
		} else if(formName == 'birthday') {
			formVal = getBirthday();
		}
		if(isRequired) {
			isError = checkValidation(formVal, formTitle);
			if(isError) {
				return;
			}	
		}
		formData[formName] = formVal;
	});
	if(isError) {
		return {};
	}
	return formData;
}

function getText(formName) {
    let formVal = $('input[type="text"][name="' + formName + '_other"]').val();
    if (formVal !== undefined && formVal !== '') {
        formVal += " (その他)";
    } else {
        formVal = $('input[type="text"][name="' + formName + '"]').val();
    }
    return formVal;
}

function getRadio(formName, pattern = 'null')
{
	let checkedElem  = $('input[name="' + formName + '"]:checked');
	let formKey;
	if(pattern == 'confirm'){
		formKey = checkedElem.val();
	}else{
		formKey = checkedElem.data('key');
	}

	if(formKey == 'その他') {
		formKey = getText(formName);
	}
	return formKey;
}

function getCheckbox(formName, formTitle)
{
	let checkboxData = [];
	$('input[name="' + formName + '"]:checked').each(function(i, elem) {
		let checkboxVal = $(this).val();
		if(checkboxVal == 'その他') {
			checkboxVal = getText(formName);
			if (checkboxVal === undefined || checkboxVal === '') {
				checkboxData = [];
				return '';
			}
		}
		checkboxData.push(checkboxVal);
	});
	return checkboxData;
}

function getBirthday()
{
	let selectKeys = {
		'year' : '生年月日が選択されていません。',
		'month': '生年月日が選択されていません。',
		'day'  : '生年月日が選択されていません。',
	}
	let birthday = {};
	for (let selectKey in selectKeys) {
		let selectAnswer = $('select[name=' + selectKey + '] option:selected').val();
		if(selectAnswer == '-') {
			return {};
		}
		birthday[selectKey] = selectAnswer;
	}
	return birthday;
}

function checkValidation(answerData, formTitle)
{
	let isError = false;
	console.log('check');
	console.log(answerData);
	if(typeof answerData == 'string') {
		if(answerData == '' || answerData ==  undefined) {
			isError = true;
		}
	} else if(typeof answerData == 'object') {
		if(answerData.length === 0) {
			isError = true;
		} else if(Object.keys(answerData).length === 0) {
			isError = true;
		}
	} else {
		if(answerData === undefined) {
			isError = true;
		}
	}

	if(isError) {
		alert(formTitle + 'の回答に不備があります。');
	}
	return isError;
}

//合格(応募者の希望日から日程調整)
$('button[name=send_adjust_schedule_btn]').on('click', function(e) {
	$(this).prop("disabled", true);

	let answerData      = getFormData();
	let adjustSchedules = getAdjustSchedule();

	if (Object.keys(adjustSchedules).length == 0) {
		alert("日時が選択されていません。");
		$(this).prop("disabled", false);
		return;
	}

	let scheduleText = Object.entries(adjustSchedules)
		.map(([date, times]) => `${date}: ${Array.isArray(times) ? times.join(', ') : times}`)
		.join('\n');

	if (!confirm(`以下の日時で送信してもよろしいですか？\n\n${scheduleText}`)) {
		$(this).prop("disabled", false);
		return;
	}
	requestAnswer(answerData, 'pattern1adjust', null, adjustSchedules);
});


function getAdjustSchedule()
{
	let adjustSchedules = [];
	let alertMsg        = '';

    $("table[name=schedule_table] ul").each(function(index, element) {
        let selectStartTime  = $(element).find('select[name=adjust_start_date] option:selected').val();
        let selectEndTime    = $(element).find('select[name=adjust_end_date] option:selected'  ).val();
        let startLoopCount   = $(element).find('select[name=adjust_start_date] option:selected').data('start_index');
        let endLoopCount     = $(element).find('select[name=adjust_end_date] option:selected'  ).data('end_index'  );
        let selectDate       = $(element).find('select[name=adjust_start_date]'                ).data('date'       );

        if (selectStartTime != '-' && selectEndTime != '-') {
            if (Number(startLoopCount) > Number(endLoopCount)) {
                alertMsg = selectDate + 'の選択した時間に不備があります。';
                return [];
            } else {
            	let scheduleDict = {
            		'start': selectDate + ' ' + selectStartTime,
            		'end'  : selectDate + ' ' + selectEndTime
            	};
                adjustSchedules.push(scheduleDict);
            }
			
        } else if (selectStartTime != '-' && selectEndTime == '-') {
        	alertMsg = selectDate + 'の終了時刻を入力してください。';
            return [];

        } else if (selectStartTime == '-' && selectEndTime != '-') {
			alertMsg = selectDate + 'の開始時刻を入力してください。';
            return [];
        }
    });
    if(alertMsg != '') {
    	alert(alertMsg);
    	return [];
    }
	console.log(adjustSchedules);

	let adjustRemarks = $('textarea[name=adjust_remarks]').val();

    if(adjustSchedules.length < 3 && (adjustRemarks == '' || adjustRemarks == undefined)) {
    	alert('希望日程の選択が３つ未満の場合は、備考欄に希望日程を記入してください。');
    	return [];
    }

	if(adjustRemarks != '' && adjustRemarks != undefined) {
		adjustSchedules.push({'memo': adjustRemarks});
	}

	return adjustSchedules;
}


function requestAnswer(answerData, resultStatus, schedules = null, adjustSchedules = null)
{
    let urlArg   = new Object;
    let urlParam = location.search.substring(1).split('&');

    for (i = 0; urlParam[i]; i++) {
        let paramInfo        = urlParam[i].split('=');
        urlArg[paramInfo[0]] = paramInfo[1];
    }
	let postData = {
		'consumerId' : urlArg.consumerId,
		'hashCs'     : urlArg.hashCs    ,
		'answerData' : answerData       ,
		'pattern'    : resultStatus     ,
		'apiToken'   : '$ik.E~~Q-N+7'
	};
	if(schedules       != null) {
		postData['schedules'      ] = schedules;
	}
	if(adjustSchedules != null) {
		postData['adjustSchedules'] = adjustSchedules;
	}
	$.ajax({
        url      : dymline.root + '/get-worksheet-answer',
        type     : 'POST'                                ,
        dataType : 'json'                                ,
        data     : postData                              ,
        timeout  : 10000                                 ,
        headers  : {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    }).done(function(data) {
		console.log(data);
		if(data.status == 'error') {
			alert('予期せぬエラーが発生しました。しばらくしてからやり直してください。');
			return;
		}
		$('div.schedule_adjust_confirm').addClass('d-none');
		$('div.schedule_confirm').addClass('d-none');
		$('p.open_text').addClass('d-none');
		$('p.thanks_text').removeClass('d-none');
		$('div.thanks_frame').removeClass('d-none');
    }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
		if(textStatus == 'timeout') {
            location.reload();
        }
	});

	$('div.schedule_adjust_confirm').addClass   ('d-none');
	$('div.schedule_confirm'       ).addClass   ('d-none');
	$('p.open_text'                ).addClass   ('d-none');
	$('p.thanks_text'              ).removeClass('d-none');
	$('div.thanks_frame'           ).removeClass('d-none');
}

//合格(店舗の候補日から日程調整)
$('button[name=send_schedule_btn]').on('click', function(e) {
	$(this).prop("disabled", true);
	let answerData = getFormData();
	let schedules  = getSchedule();

	if (Object.keys(schedules).length == 0) {
		alert("日時が選択されていません。");
		$(this).prop("disabled", false);
		return;
	}
	let scheduleText = Object.entries(schedules).map(([date, time]) => `${date}: ${time}`).join('\n');

	if (!confirm(`以下の日時で送信してもよろしいですか？\n\n${scheduleText}`)) {
		$(this).prop("disabled", false);
		return;
	} 
	requestAnswer(answerData, 'pattern1decide', schedules, null);
});

function getSchedule()
{
	let selectSchedules = {};
	let checkSchedule   = [];

	for(let scheduleIndex = 1; scheduleIndex <= 3; scheduleIndex++) {
		let scheduleIndexStr = String(scheduleIndex);
		let selectSchedule   = $('select[name=choice_schedule_' + scheduleIndexStr + '] option:selected').val();

		if( selectSchedule ==  '-' ||
			selectSchedule ==  ''  ||
			selectSchedule === undefined) {

			alert('第' + scheduleIndexStr + '希望を選択してください。');
			return {};

		} else if(checkSchedule.indexOf(selectSchedule) != -1) {
			alert('同じ日程を複数回選択しています。');
			return {};

		} else {
			checkSchedule.push(selectSchedule);
			selectSchedules[scheduleIndexStr] = selectSchedule;
		}
	}
	return selectSchedules;
}

$('p[name=to_adjust_schedule]').on('click', function(e) {
	$('div.schedule_confirm'       ).addClass   ('d-none');
	$('div.schedule_adjust_confirm').removeClass('d-none');
});

$('p[name=to_schedule]').on('click', function(e) {
	$('div.schedule_adjust_confirm').addClass   ('d-none');
	$('div.schedule_confirm'       ).removeClass('d-none');
});

$('input[type="radio"], input[type="checkbox"]').on('change', function(e) {
	var formKey = $(this).attr('name');
    var otherInput = $('#' + formKey + '_other');
    var isOtherChecked = false;

    $('input[name="' + formKey + '"]').each(function() {
        if ($(this).val() === 'その他' && $(this).is(':checked')) {
            isOtherChecked = true;
        }
    });

    if (isOtherChecked) {
        otherInput.removeClass('d-none');
    } else {
        otherInput.addClass('d-none');
    }
});