$('button[name=update_schedule]').on('click', function(e) {
	let ableSchedule = getAbleSchedule();
	console.log(ableSchedule);
    let confirmSaveSchedule = window.confirm('スケジュールの変更を反映してよろしいですか？');
    if(!confirmSaveSchedule) {
        console.log('保存しない');
        return;
    }
    let url      = new URL(window.location.href);
    let queryStr = url.search;
    var userId   = queryStr.split('=')[1]
    let postData = {
        'ableScheduleData': ableSchedule,
        'apiToken'        : '$ik.E~~Q-N+7',
        'userId'          : userId
    };
    apiRequest('update-schedule', postData).done(function(data) {
        location.reload();
	}).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        location.reload();
	});
});



function getAbleSchedule() {
    let schedules     = [];
    let calendarArray = calendar.getEvents();
    for (let key in calendarArray) {
        let calendarInfo = calendarArray[key];
        console.log(calendarInfo.id);
        if (!calendarInfo.id.match(/free-/)) {
            continue;
        }
        var formStartDate  = formatDate2(calendarInfo.start);
        var formStartTime  = formatTime(calendarInfo.start);
        var formEndDate    = formatDate2(calendarInfo.end);
        var formEndTime    = formatTime(calendarInfo.end);
        if (formStartDate != formEndDate) {
            var termDay    = (new Date(formEndDate) - new Date(formStartDate)) / 86400000;
            if (termDay == 1) {
                var getSchedule1 = formStartDate + '_' + formStartTime + '~17:00';
                var getSchedule2 = formEndDate + '_09:00~' + formEndTime;
                schedules.push(getSchedule1);
                schedules.push(getSchedule2);
            } else {
                for (let index = 0; index <= termDay; index++) {
                    var getSchedule3 = '';
                    if (index == 0) {
                        getSchedule3 = formStartDate + '_' + formStartTime + '~17:00';
                    } else if (index == termDay) {
                        getSchedule3 = formEndDate + '_09:00~' + formEndTime;
                    } else {
                        let startDateObj = new Date(formStartDate);
                        startDateObj.setDate(startDateObj.getDate() + index);
                        var middeDate = formatDate2(startDateObj);
                        getSchedule3  = middeDate + '_09:00~17:00';
                    }
                    schedules.push(getSchedule3);
                }
            }
        } else {
            var getSchedule = formStartDate + '_' + formStartTime + '~' + formEndTime;
            schedules.push(getSchedule);
        }
    }
    return schedules;
}

// 日付をYYYY/MM/DDの書式で返すメソッド
function formatDate2(dt) {
    var y = dt.getFullYear();
    var m = ('00' + (dt.getMonth() + 1)).slice(-2);
    var d = ('00' + dt.getDate()).slice(-2);
    return (y + '/' + m + '/' + d);
}

function formatTime(dt) {
    var hours   = ('00' + dt.getHours()).slice(-2);
    var minutes = ('00' + dt.getMinutes()).slice(-2);
    return hours + ':' + minutes;
}



