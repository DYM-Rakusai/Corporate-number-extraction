<div class="block">
    <div class="card-header py-3 d-inline-flex">
        <h6 class="add_black_list_title mb-0">ブラックリスト</h6>
    </div>
    <div class="card-body">
        <div>
            {{ $blackListPageData->links() }}
        </div>
        <table class="black_list_table" border>
            <thead>
                <tr>
                    <th>お名前</th>
                    <th>電話番号</th>
                    <th>メールアドレス</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
                @foreach($blackListPageData as $blackListData)
                <tr>
                    <td>{{ $blackListData->name }}</td>
                    <td>{{ $blackListData->tel }}</td>
                    <td>{{ $blackListData->mail }}</td>
                    <td>
                        <button
                            name="delete_black_list_btn"
                            data-name="{{$blackListData->name}}"
                            data-id="{{$blackListData->id}}">削除</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
<div>


