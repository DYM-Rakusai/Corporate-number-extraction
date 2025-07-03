let calendar;

document.addEventListener('DOMContentLoaded', function() {
    var date = new Date();
    date.setDate(date.getDate());
    let tommorow = formatDate(date);
    date.setDate(date.getDate() + 28);
    let after2WeekAfter = formatDate(date);

    var calendarEl = document.getElementById('calendar');
    calendar       = new FullCalendar.Calendar(calendarEl, {
        initialView         : 'timeGridWeek',
        selectLongPressDelay: 0,
        editable            : true,
        selectable          : true,
        events              : calendarEvenets,
        locale              : 'ja',
        dayHeaderContent: function(arg) {
            let weekList = ['日', '月', '火', '水', '木', '金', '土'];
            let month    = arg.date.getMonth() + 1;
            let day      = arg.date.getDate();
            return month + '/' + day + '(' + weekList[arg.dow] + ')' + '【休日にする】';
            // return month + '/' + day + '(' + weekList[arg.dow] + ')';
        },
        slotMinTime: '09:00:00',
        slotMaxTime: '19:00:00',
        firstDay   : (new Date()).getDay(),
        validRange : {
            start: tommorow,
            end  : after2WeekAfter,
        },
        slotDuration: '00:30:00',
        allDaySlot  : false,
        select: function(arg) {
            var title    = '面接可能日時';
            var now      = new Date();
            var dateTime = now.getTime();
            calendar.addEvent({
                id       : 'free-' + getUniqueStr(),
                title    : title,
                start    : arg.start,
                end      : arg.end,
                allDay   : arg.allDay,
                color    : '#3490dc',
                className:'<create_time-' + dateTime + '>'
            });
        },
        eventClick: function(info) {
            console.log('イベントクリック');
            let publicScheduleId = info.event._def.publicId;
            let scheduleGroupId  = info.event._def.groupId;
            console.log(publicScheduleId);
            if (publicScheduleId.indexOf('buried-') === -1) {
                if (publicScheduleId.indexOf('free-') !== -1) {
                    info.el.addEventListener('dblclick', function(e) {
                        let removeElem = calendar.getEventById(info.event._def.publicId);
                        if (removeElem == null) {
                            return;
                        }
                        removeElem.remove();
                    });
                }
                return;
            } else {
                let scheduleId = getScheduleId(info.event._def.publicId);
                getScheduleData(scheduleId);
            }
        },
    });
    calendar.render();

    $(document).on('click', 'a.fc-col-header-cell-cushion', function() {
        // 対象列の日付文字列を取得
        let scheduleDay = $(this).text();
        let target_date = scheduleDay.replace('【休日にする】', '');
        target_date     = target_date.replace(/\(.*\)/, '');
        // 対象列内のイベント取得
        events = calendar.getEvents();
        // イベントの日付が対象列と一致していれば削除する
        for (let index = 0; index < events.length; index++) {
            let event = calendar.getEventById(events[index]._def.publicId);
            if (event.id.indexOf('free-') === -1) {
                continue; // 空き枠以外は処理しない
            }
            event_month = (new Date(event.start)).getMonth() + 1;
            event_day   = (new Date(event.start)).getDate();
            if (event_month + '/' + event_day == target_date) {
                event.remove();
            }
        }
    });
});


function getScheduleId(eventId) {
    let scheduleId = eventId.replace('buried-', '');
    return scheduleId;
}

function getScheduleData(scheduleId)  {
    let urlParams = new URLSearchParams(window.location.search);
    let userId    = urlParams.get('userId');
    request = {
        'scheduleId' : scheduleId,
        'apiToken'   : '$ik.E~~Q-N+7',
        'userId'     : userId
    };
    $.ajax({
        url: dymline.domain + '/get-schedule-data',
        type    : 'POST',
        dataType: 'json',
        data    : request,
        timeout : 3000,
        headers : {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Authorization': 'Bearer ' + $('input[name="api_token"]').data('token'),
        }
    }).done(function(data) {
        if('status' in data && data['status'] == '200') {
            if(data.consumerData.ats_consumer_id) {
                $('td[name=cs_id_td]').text(data.consumerData.ats_consumer_id);
            } else {
                $('td[name=cs_id_td]').text('-');
            }

            if(data.consumerData.name) {
                $('td[name=cs_name_td]').text(data.consumerData.name);
            } else {
                $('td[name=cs_name_td]').text('-');
            }

            if(data.consumerData.tel) {
                $('td[name=cs_tel_td]').text(data.consumerData.tel);
            } else {
                $('td[name=cs_tel_td]').text('-');
            }

            if(data.consumerData.mail) {
                $('td[name=cs_mail_td]').text(data.consumerData.mail);
            } else {
                $('td[name=cs_mail_td]').text('-');
            }

            if(data.interviewDate) {
                $('td[name=cs_interview_date_td]').text(data.interviewDate);
            } else {
                $('td[name=cs_interview_date_td]').text('-');
            }

            $('td[name=answer_data_td]').empty();
            if(data.wsAnswerHtml) {
                $('td[name=answer_data_td]').append(data.wsAnswerHtml);
            }
            $('#consumer_detail_modal').modal('toggle');

        } else {
            alert('エラーが発生しました。しばらく後再度お試しください。');
        }
    }).fail(function(XMLHttpRequest, textStatus, errorThrown) {

    });
}


function getUniqueStr(myStrong) {
    var strong = 1000;
    if (myStrong) strong = myStrong;
    return new Date().getTime().toString(16) + Math.floor(strong * Math.random()).toString(16);
}

// 日付をYYYY-MM-DDの書式で返すメソッド
function formatDate(dt) {
    var y = dt.getFullYear();
    var m = ('00' + (dt.getMonth() + 1)).slice(-2);
    var d = ('00' + dt.getDate()).slice(-2);
    return (y + '-' + m + '-' + d);
}