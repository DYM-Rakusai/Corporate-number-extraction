let urlParams = new URLSearchParams(window.location.search);
let userId    = urlParams.get('userId');
let isError   = false;
$('button[name=update_job_keywords_btn]').on('click', function(e) {
    let $button = $(this);
    $button.prop("disabled", true);
    let jobKeywords = {};
    let checkList   = [];
    let isError     = false;
    let jobKey      = userId;
    jobKeywords     = [];
    $('.keyword_frame').find('input[name="keyword_map"]').each(function(index, element) {
        jobMapVal = $(this).val().trim();
        if (jobMapVal) {
            jobKeywords.push(jobMapVal);
            checkList.push(jobMapVal);
        }
    });
    let result = confirm('求人マッピングを更新してもよろしいですか？');
    if (!result) {
        $button.prop("disabled", false);
        return;
    }
    console.log(isError);
    let postData = {
        'jobKeywords': jobKeywords,
        'userId'     : userId
    };
    console.log(jobKeywords);
    apiRequest('update-job-keywords', postData).done(function(data) {
        alert('更新が完了しました');
        location.reload();
    }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        if (XMLHttpRequest.status === 400) {
            var response = XMLHttpRequest.responseJSON;
            if (response.error === 'sameWord') {
                alert('同じキーワードを設定しています。');
            }
        } else {
            alert('更新に失敗しました');
        }
        $button.prop("disabled", false);
    });

});
$(document).ready(function() {
    $('a[name="add_btn"]').on('click', function(e) {
        e.preventDefault();
        let jobName   = $(this).data('job_name');
        let itemCount = 0;
        let selector  = '.keyword_frame';
        $(selector).find('.job_keyword').each(function(index, elem) {
            let itemCountStr = $(elem).data('item_count');
            let checkCount   = Number(itemCountStr);
            if (itemCount < checkCount) {
                itemCount = checkCount;
            }
        });

        let nextCount = itemCount + 1;
        let addHtml   = addJobKeywordForm(nextCount, jobName);
        $(this).closest('.add_btn_frame').before(addHtml);
    });
    function addJobKeywordForm(count, jobName) {
        return `
            <div class="job_keyword" data-job_name="${jobName}" data-item_count="${count}">
                <input type="text" name="keyword_map" value="">
                <a name="delete_btn" data-job_name="${jobName}" class="btn">削除</a>
            </div>
        `;
    }
    $(document).on('click', 'a[name="delete_btn"]', function(e) {
        e.preventDefault();
        $(this).closest('.job_keyword').remove();
    });
});
