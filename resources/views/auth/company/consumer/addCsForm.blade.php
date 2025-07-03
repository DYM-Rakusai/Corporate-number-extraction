<div class="block">
    <div class="card-header py-3 d-inline-flex">
        <h6 class="mb-0">応募者新規登録</h6>
    </div>
    <div class="card-body">
        <div>
            @foreach($csAddConf as $formKey => $csAddFormData)
                <div class="add_form">
                    <div>
                        @if($csAddFormData['isNeed'] == 1)
                        <span class="need">*</span>
                        @endif
                        <span class="form_title">{{$csAddFormData['formTitle']}}</span><br>
                        <span class="remarks">{{$csAddFormData['remarks']}}</span>
                    </div>
                    <div>
                        @if($csAddFormData['formType'] == 'inputText')
                            <input
                                type="text"
                                name="{{$formKey}}"
                                placeholder="{{$csAddFormData['placeholder']}}">
                        @elseif($csAddFormData['formType'] == 'select')
                            <select name="{{$formKey}}">
                                <option value="">-</option>
                                @if ($formKey != 'userName')
                                    @foreach(config($csAddFormData['choiceConf']) as $choice)
                                        <option value="{{$choice}}">{{$choice}}</option>
                                    @endforeach
                                @else
                                    @foreach ($csAddFormData['choiceConf'] as $choice)
                                        <option value="{{$choice}}">{{$choice}}</option>
                                    @endforeach
                                @endif
                        </select>
                        @elseif($csAddFormData['formType'] == 'radio')
                            @foreach($csAddFormData['choices'] as $choiceText => $choiceVal)
                                <div>
                                    <label>
                                        <input
                                            type="radio"
                                            name="{{$formKey}}"
                                            value="{{$choiceVal}}">
                                        <span>{{$choiceText}}</span>
                                    </label>
                                </div>
                            @endforeach
                        @elseif($csAddFormData['formType'] == 'entryDate')
                            <input
                                type="date"
                                name="app_date">
                            <span>年/月/日</span>                        
                            <select name="entry_hour">
                                <option value="">-</option>
                                @for($hourNum = 0; $hourNum <= 23; $hourNum++)
                                    <option value="{{$hourNum}}">{{$hourNum}}</option>
                                @endfor
                            </select>
                            <span>時</span>
                            <select name="entry_minute">
                                <option value="">-</option>
                                @for($minutesNum = 0; $minutesNum <= 59; $minutesNum++)
                                    <option value="{{$minutesNum}}">{{$minutesNum}}</option>
                                @endfor
                            </select><span>分</span>
                        @endif
                    </div>
                </div>
            @endforeach
            <div class="btn_frame">
                <button name="add_manual_cs_btn">応募者登録</button>
            </div>
        </div>
    </div>
</div>