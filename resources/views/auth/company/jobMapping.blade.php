
@extends('layouts.sidemenu')

@section('content')
<script>
</script>
<div class="container m-0 mt-5">
    <div class="block">
        <div class="card-header py-3 d-inline-flex">
            <h6 class="mb-0">キーワードを入力してください</h6>
        </div>
        <div class="card-body">
            <div>
                <div>
                    <div>
                        @if($userName != '')
                            <h5 class="user_name">{{$userName}}</h5>
                        @endif
                    </div>
                    <div class="dropdown dropdown_store">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            ユーザー選択
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @foreach($userDataArray as $userData)
                                @if ($userData->authority != 'master')
                                    <a class="dropdown-item" href="{{ url('/job-mapping-page') }}?userId={{$userData->id}}">{{$userData->name}}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @if ($userId != 0)
                    <div class="keyword_frame">
                        @if ($targetKeywords != '')
                            @foreach($targetKeywords as $targetKeyword)
                            <div class="job_keyword">
                                <input type="text" name="keyword_map" value="{{$targetKeyword}}">
                                <a name="delete_btn" data-job_name="{{$targetKeyword}}" class="btn">削除</a>
                            </div>
                            @endforeach
                        @endif
                        <div class="add_btn_frame">
                            <a name="add_btn" data-job_name="" class="btn">キーワード追加</a>
                        </div>
                    </div>
                    <div class="btn_frame">
                        <button name="update_job_keywords_btn">更新</button>
                    </div>
                </div>
                @endif
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
