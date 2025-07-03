
@extends('layouts.sidemenu')

@section('content')

<script>
</script>

<div class="m-0 mt-5">
    
    <div>
        <div>
            <u>
                <a class="" href="{{ url('/user-list-page') }}">
                    アカウント一覧へ戻る
                </a>
            </u>
        </div>

        <table class="user_detail">
            <tbody>
                @foreach($userkeys as $userKey => $userName)
                <tr>
                    <th>
                        {{ $userName }}
                    </th>
                    <td>
                        @if(array_key_exists($userKey, $userEditConf))
                            @if($userEditConf[$userKey] == 'inputText')
                                <input
                                    name="{{$userKey}}"
                                    type="text"
                                    value="{{$userData[$userKey] ?? ''}}">
                            @endif
                        @else
                            {{ $userData[$userKey] ?? '-' }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="btn_area">
            <button name="update_user_data">アカウント情報更新</button>
        </div>
    </div>

</div>
@endsection

