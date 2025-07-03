
@extends('layouts.sidemenu')

@section('content')

<script>
</script>

<div class="container m-0 mt-5">
    <div class="">
        @include('auth.company.blackList.addBlackList', [])
        @include('auth.company.blackList.blackListTable',
        	[
        		'blackListPageData' => $blackListPageData
        	]
        )
    </div>
</div>


@endsection
