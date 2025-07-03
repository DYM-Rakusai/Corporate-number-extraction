
@extends('layouts.sidemenu')

@section('content')

<script>
</script>

<div class="m-0 mt-5 mb-5">
    @include('auth.company.consumer.csSearchData',
    	[
	    	'statusList' => $statusList
    	]
    )
    <div class="">
        @include('auth.company.consumer.csList',
            [
                '$urlParam' => $urlParam,
                'csPageData' => $csPageData,
                'statusColors' => $statusColors,
                'userId' => $userId,
            ]
        )
    </div>
</div>


@endsection
