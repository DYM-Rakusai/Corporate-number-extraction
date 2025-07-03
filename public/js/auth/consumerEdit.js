$('button[name="update_cs_data"]').on('click', function(e) {
    $(this).prop("disabled", true);
    let textList = {
        'name'  : '名前',
        //'kana': 'フリガナ',
        'tel'   : '電話番号',
        'mail'  : 'メールアドレス',
    };
    let isError      = false;
    let alertMsg     = '';
    let consumerData = {};
    for(let textKey in textList) {
        consumerData[textKey] = $('input[name=' + textKey + ']').val();
        if((consumerData[textKey] == '' || consumerData[textKey] == undefined) && isError == false) {
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

    let address = $('textarea[name=address]').val();
    consumerData['address'] = null;
    if(address != '' && address != undefined) {
        consumerData['address'] = address;
    }
    
    consumerData = getDateVal(consumerData, 'kana');

    let consumerStatus = $('select[name=consumer_status] option:selected').val();
    consumerData['consumer_status'] = consumerStatus;

    let memo = $('textarea[name=memo]').val();
    consumerData['memo'] = null;
    if(memo != '' && memo !== undefined) {
        consumerData['memo'] = memo;
    }

    let urlParams = getUrlParam();
    let postData  = {
        'consumerData': consumerData,
        'id'          : urlParams.id
    };
    let result = confirm('応募者情報を更新してもよろしいですか？');
    if(!result) {
        $(this).prop("disabled", false);
        return;
    }

    apiRequest('edit-cs-data', postData).done(function(data) {
        window.location.replace(dymline.domain + '/cs-detail-page?id=' + urlParams.id);

        $(this).prop("disabled", false);
    }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        location.reload();
        $(this).prop("disabled", false);
    });
});


function getDateVal(csData, keyName) {
    let dateVal = $('input[name=' + keyName + ']').val();
    csData[keyName] = null;
    if(dateVal != '') {
        csData[keyName] = dateVal;
    }
    return csData;
}