$('button[name="update_user_data"]').on('click', function(e) {
    $(this).prop("disabled", true);
    let textList = {
        'name'          : '名前',
        //'kana'          : 'フリガナ',
        'tel'           : '電話番号',
        'mail'          : 'メールアドレス',
        //'interview_url' : '面接URL'
    };
    let isError  = false;
    let alertMsg = '';
    let userData = {};
    for(let textKey in textList) {
        userData[textKey] = $('input[name=' + textKey + ']').val();
        if((userData[textKey] == '' || userData[textKey] == undefined) && isError == false) {
            let textTitle = textList[textKey];
            alertMsg      = textTitle + 'を入力してください。';
            isError       = true;
        }
    }
    if(isError) {
        alert(alertMsg);
        $(this).prop("disabled", false);
        return;
    }

    userData['kana']          = $('input[name="kana"]').val();
    userData['interview_url'] = $('input[name="interview_url"]').val();

    let urlParams = getUrlParam();
    let postData  = {
        'userData': userData,
        'id'      : urlParams.id
    };
    let result = confirm('アカウント情報を更新してもよろしいですか？');
    if(!result) {
        $(this).prop("disabled", false);
        return;
    }

    apiRequest('edit-user-data', postData).done(function(data) {
        window.location.replace(dymline.domain + '/user-list-page');
        $(this).prop("disabled", false);
    }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        location.reload();
        $(this).prop("disabled", false);
    });
});
