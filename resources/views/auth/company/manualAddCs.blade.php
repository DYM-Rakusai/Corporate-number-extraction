
@extends('layouts.sidemenu')

@section('content')
<script>
</script>
<div class="container m-0 mt-5">
    @include('auth.company.consumer.addCsForm',
    [
        'csAddConf' => $csAddConf
    ])
</div>
@endsection
