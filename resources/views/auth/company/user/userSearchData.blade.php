<div>
    <div class="block">
        <div class="search_header card-header py-3 d-inline-flex"
            data-toggle="collapse"
            href="#user_search_id"
            role="button"
            aria-expanded="false"
            aria-controls="user_search_id">
            <h6 class="mb-0">
                <i class="fas fa-search"></i>
                アカウント検索
            </h6>
        </div>
        <div
            @if(!empty($_GET))
            class="search_body card-body collapse show"
            @else
            class="search_body card-body collapse"
            @endif
            id="user_search_id">
            <form>
                <table>
                    <tbody>
                        <tr>
                            <th>
                                <span>名前</span>
                            </th>
                            <td>
                                <input
                                    name="user_name"
                                    type="text"
                                    value="{{ $_GET['user_name'] ?? '' }}">
                            </td>
                        </tr>
                        
                        <tr>
                            <th>
                                <span>電話番号</span>
                            </th>
                            <td>
                                <input
                                    name="user_tel"
                                    type="text"
                                    value="{{ $_GET['user_tel'] ?? '' }}">
                            </td>
                        </tr>

                        <tr>
                            <th>
                                <span>メールアドレス</span>
                            </th>
                            <td>
                                <input
                                    name="user_mail"
                                    type="text"
                                    value="{{ $_GET['user_mail'] ?? '' }}">
                            </td>
                        </tr>                        
                    </tbody>
                </table>
                <div class="btn_frame">
                    <button type="submit" name="search_btn">検索</button>
                    <a class="btn" href="{{ url('/user-list-page') }}" name="reset_btn">リセット</a>
                </div>
            </form>
        </div>
    </div>
</div>


