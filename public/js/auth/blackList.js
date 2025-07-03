$('button[name=add_black_list_btn]').on('click', function(e) {
	$(this).prop("disabled", true);

    let blackListName = $('input[name=add_name]').val();
    let blackListTel  = $('input[name=add_tel]').val();
	if(blackListTel == '') {
		blackListTel = '-';
	}
    let blackListMail = $('input[name=add_mail]').val();
	if(blackListMail == '') {
		blackListMail = '-';
	}

	let postData = {
        'blackListName': blackListName,
        'blackListTel' : blackListTel,
        'blackListMail': blackListMail,
        'apiToken'     : '$ik.E~~Q-N+7'
    }
    let isError = checkValidata(postData);
    if(isError) {
   		$(this).prop("disabled", false);
    	return;
    }
	console.log(postData);
	apiRequest('add-black-list', postData).done(function(data) {
		location.reload();
	}).fail(function(XMLHttpRequest, textStatus, errorThrown) {
		location.reload();
	});
});

function checkValidata(checkList)
{
	let isError = false;
	if(checkList['blackListName'] == '') {
		alert('名前を入力してください。');
		isError = true;
		return isError;
	}
	if(checkList['blackListTel'] == '-' && checkList['blackListMail'] == '-') {
		alert('電話番号か、メールアドレスを入力してください。');
		isError = true;
		return isError;
	}
	return isError;
}



$('button[name=delete_black_list_btn]').on('click', function(e) {
	$(this).prop("disabled", true);
	let blackListName = $(this).data('name');
    let confirmText   = blackListName + 'さんをブラックリストから削除してよろしいですか？\n';
    let result        = confirm(confirmText);
    if(!result) {
	    //いいえを選んだときの処理
   		$(this).prop("disabled", false);
        return;
    }
	let blackListId = $(this).data('id');
	let postData    = {
		'blackListId': blackListId,
	    'apiToken'   : '$ik.E~~Q-N+7'
	}
	apiRequest('delete-black-list', postData).done(function(data) {
		location.reload();
	}).fail(function(XMLHttpRequest, textStatus, errorThrown) {
		location.reload();
	});
});





