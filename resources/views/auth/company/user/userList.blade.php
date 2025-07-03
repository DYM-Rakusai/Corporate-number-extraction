<div>
    <table class="user_list">
        <thead>
            <th>一覧</th>
        </thead>
        <tbody>
            @foreach($userPageData as $cnt => $userData)
                <td>
                    <div class="user_detail">
                        <u>
                            <a href="{{ url('/edit-user-page') }}?id={{$userData->id}}" target="_blank">編集</a>
                        </u><br>
                        氏名：{{$userData->name}}<br>
                        メールアドレス：{{$userData->mail}}<br>
                        電話番号：{{$userData->tel}}<br>
                        面接URL：{{$userData->interview_url}}<br>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="page-nation-bottom">
        {{ $userPageData->links() }}
    </div>
</div>
