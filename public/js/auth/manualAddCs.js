$('button[name=add_manual_cs_btn]').on('click', function(e) {
	$(this).prop("disabled", true);
	let needTextList = {
		'name'      : '名前',
		'app_date'  : '応募日時（年/月/日）',
		'birthday'  : '生年月日',
		'entry_job' : '応募案件名',
	};
	let notNeedTextList = {
		'kana'   : 'フリガナ',
		'address': '住所',
		'tel'    : '電話番号',
		'mail'   : 'メールアドレス'
	};
	let needSelectList = {
		//'userName'   : 'ユーザー',
		'app_media'    : '応募媒体',
		'entry_hour'   : '応募日時（時）',
		'entry_minute' : '応募日時（分）',
	};
	let needRadioList = {
		'is_sent_auto_msg' : '自動メッセージ送信を行うか'
	}
	let getData = {};
	let isError = false;
	[getData, isError] = getFormData(needTextList, true, getData, 'text')
	if(isError) {
        $(this).prop("disabled", false);
		return;
	}
	[getData, isError] = getFormData(notNeedTextList, false, getData, 'text');
	if(getData['tel'] == '' && getData['mail'] == '') {
		alert('電話番号かメールアドレスを入力してください。');
        $(this).prop("disabled", false);
		return;
	}
	[getData, isError] = getFormData(needSelectList, true, getData, 'select');
	if(isError) {
        $(this).prop("disabled", false);
		return;
	}
	[getData, isError] = getFormData(needRadioList, true, getData, 'radio');
	if(isError) {
        $(this).prop("disabled", false);
		return;
	}
	let postData = {
		'manualAddData': getData
	};


    let result = confirm('新しく応募者を登録してもよろしいですか？');
    if(!result) {
   		$(this).prop("disabled", false);
        return;
    }

	console.log(postData);

	apiRequest('manual-add-cs', postData).done(function(data) {
		window.location.replace(dymline.domain + '/cs-list-page');
        $(this).prop("disabled", false);
	}).fail(function(XMLHttpRequest, textStatus, errorThrown) {
		location.reload();
        $(this).prop("disabled", false);
	});
});

function getFormData(nameDict, isNeed, getData, type) {
	let isError   = false;
	let alertText = '';
	let getVal    = '';
	for (const name in nameDict) {
		getVal = '';
		if(type == "text") {
			getVal = $('input[name="' + name + '"]').val();
		} else if(type == "radio") {
			getVal = $('input[name="' + name + '"]:checked').val();
		} else if(type == "select") {
			getVal = $('select[name="' + name + '"] option:selected').val();
		}
		console.log(name);
		console.log(getVal);
		if(isNeed) {
			if((getVal == '' || getVal === undefined) && isError === false) {
				isError = true;
				let alertEndText = (type == "text") ? 'を入力してください。' : 'を選択してください。';
				alertText = nameDict[name] + alertEndText;
			}
		}
		getData[name] = getVal;
	}
	if(isError) {
		alert(alertText);
	}
	return [getData, isError];
}



