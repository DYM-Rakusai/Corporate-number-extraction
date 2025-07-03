
@extends('layouts.sidemenu')

@section('content')

<script>
</script>

<div class="m-0 mt-5 mb-5">
    @if($authority == 'master')
        @include('auth.company.user.userSearchData')
    @endif
    <div class="">
        @include('auth.company.user.userList',
            [
                '$urlParam' => $urlParam,
                'userPageData' => $userPageData,
                'userId' => $userId,
            ]
        )
    </div>
</div>

@endsection
