<div>
    <div class="block">
        <div class="search_header card-header py-3 d-inline-flex"
            data-toggle="collapse"
            href="#cs_search_id"
            role="button"
            aria-expanded="false"
            aria-controls="cs_search_id">
            <h6 class="mb-0">
                <i class="fas fa-search"></i>
                応募者検索
            </h6>
        </div>
        <div
            @if(!empty($_GET))
            class="search_body card-body collapse show"
            @else
            class="search_body card-body collapse"
            @endif
            id="cs_search_id">
            <form>
                <table>
                    <tbody>
                        <tr>
                            <th>
                                <span>応募日時</span>
                            </th>
                            <td>
                                <input
                                    name="start_app_date"
                                    type="date"
                                    value="{{ $_GET['start_app_date'] ?? '' }}">
                                    〜
                                <input
                                    name="end_app_date"
                                    type="date"
                                    value="{{ $_GET['end_app_date'] ?? '' }}">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <span>面接予定日</span>
                            </th>
                            <td>
                                <input
                                    name="start_interview_date"
                                    type="date"
                                    value="{{ $_GET['start_interview_date'] ?? '' }}">
                                    〜
                                <input
                                    name="end_interview_date"
                                    type="date"
                                    value="{{ $_GET['end_interview_date'] ?? '' }}">
                            </td>
                        </tr>

                        <tr>
                            <th>
                                <span>名前</span>
                            </th>
                            <td>
                                <input
                                    name="app_name"
                                    type="text"
                                    value="{{ $_GET['app_name'] ?? '' }}">
                            </td>
                        </tr>
                        
                        <tr>
                            <th>
                                <span>電話番号</span>
                            </th>
                            <td>
                                <input
                                    name="app_tel"
                                    type="text"
                                    value="{{ $_GET['app_tel'] ?? '' }}">
                            </td>
                        </tr>

                        <tr>
                            <th>
                                <span>メールアドレス</span>
                            </th>
                            <td>
                                <input
                                    name="app_mail"
                                    type="text"
                                    value="{{ $_GET['app_mail'] ?? '' }}">
                            </td>
                        </tr>

                        <tr>
                            <th>
                                <span>ステータス</span>
                            </th>
                            <td>
                                <select name="app_status">
                                    <option value="">-</option>
                                    @foreach($statusList as $status)
                                        @if(!empty($_GET['app_status']) && $_GET['app_status'] == $status)
                                            <option value="{{$status}}" selected>{{$status}}</option>
                                        @else
                                            <option value="{{$status}}">{{$status}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                面接設定
                            </th>
                            <td>
                                <div>
                                    <label class="">
                                        <input
                                            name="interview_set" type="radio" value="set"
                                            @if(isset($_GET["interview_set"]) && $_GET["interview_set"] == 'set') checked @endif
                                            >
                                        <span>面接設定済み</span>
                                    </label><br>
                                    <label class="">
                                        <input
                                            name="interview_set" type="radio" value="not_set"
                                            @if(isset($_GET["interview_set"]) && $_GET["interview_set"] == 'not_set') checked @endif
                                            >
                                        <span>面接未設定</span>
                                    </label><br>
                                    <label class="">
                                        <input
                                            name="interview_set" type="radio" value="unspecified"
                                            @if(isset($_GET["interview_set"]) && $_GET["interview_set"] == 'unspecified') checked @endif
                                            >
                                        <span>指定なし</span>
                                    </label>
                                </div>
                            </td>                            
                        </tr>
                        
                    </tbody>
                </table>
                <div class="btn_frame">
                    <button type="submit" name="search_btn">検索</button>
                    <a class="btn" href="{{ url('/cs-list-page') }}" name="reset_btn">リセット</a>
                </div>
            </form>
        </div>
    </div>
</div>


