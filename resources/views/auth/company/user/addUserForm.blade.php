@extends('layouts.sidemenu')

@section('content')
<script>
</script>
<div class="container m-0 mt-5">
    
    <div class="block">
        <div class="card-header py-3 d-inline-flex">
            <h6 class="mb-0">アカウント新規登録</h6>
        </div>
        <div class="card-body">
            <div>
                @foreach($userAddConf as $formKey => $userAddFormData)
                    <div class="add_form">
                        <div>
                            @if($userAddFormData['isNeed'] == 1)
                            <span class="need">*</span>
                            @endif
                            <span class="form_title">{{$userAddFormData['formTitle']}}</span><br>
                            <span class="remarks">{{$userAddFormData['remarks']}}</span>
                        </div>
                        <div>
                            <input
                                type="text"
                                name="{{$formKey}}"
                                placeholder="{{$userAddFormData['placeholder']}}">
                        </div>
                    </div>
                @endforeach
                <div class="btn_frame">
                    <button name="add_manual_user_btn">アカウント登録</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection