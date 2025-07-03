$('button[name=add_manual_user_btn]').on('click', function(e) {
	$(this).prop("disabled", true);

	let needTextList = {
		'name' 	        : '名前',
		//'kana'          : 'フリガナ',
		'tel'           : '電話番号',
		'mail' 	        : 'メールアドレス',
		//'interview_url' : '面接URL'
	};
	let getData = {};
	let isError = false;

	[getData, isError] = getFormData(needTextList, true, getData, 'text')

	if(isError) {
        $(this).prop("disabled", false);
		return;
	}
	
	getData['kana']          = $('input[name="kana"]').val();
    getData['interview_url'] = $('input[name="interview_url"]').val();

	let postData = {
		'manualAddData': getData
	};

    let result = confirm('新しくアカウントを追加してもよろしいですか？');
    if(!result) {
   		$(this).prop("disabled", false);
        return;
    }

	console.log(postData);

	apiRequest('manual-add-user', postData).done(function(data) {
		window.location.replace(dymline.domain + '/user-list-page');
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


