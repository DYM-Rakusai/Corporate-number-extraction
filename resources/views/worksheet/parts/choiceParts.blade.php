<div class="worksheet_form">
    @foreach($formChoices as $formKey => $formChoice)
        <div class="choice_content work_term">
            <div class="form_title_frame">
                <span class="form_title">{{$formChoice['title']}}</span>
                <span class="hisuu">必須</span>
            </div>
            <div class="form_choices">
                <div class="form_content_item"
                    data-type="{{$formChoice['type']}}"
                    data-form_name="{{$formKey}}"
                    data-form_title="{{$formChoice['title']}}">
                    @if($formKey == 'birthday')
                        @foreach($formChoice['choice'] as $selectName => $options)
                            <select name="{{$selectName}}">
                                <option value="-">-</option>
                                @foreach($options as $optionVal)
                                    <option value="{{$optionVal}}">{{$optionVal}}</option>
                                @endforeach
                            </select>
                            <span>{{$formChoice['label'][$selectName]}}</span>
                        @endforeach

                    @elseif($formChoice['type'] == 'radio')
                        @foreach($formChoice['choice'] as $choiceKey => $choiceVal)
                            <label class="w-100 text-break">
                                <input name="{{$formKey}}" type="radio" data-key="{{$choiceKey}}" value="{{$choiceVal}}">
                                <span>{{$choiceVal}}</span>
                            </label>
                            @if($choiceVal == 'その他')
                                <input name="{{$formKey}}_other" type="text" placeholder="その他を選択の場合記載" class="form-control  d-none" id="{{$formKey}}_other">
                            @endif
                        @endforeach

                    @elseif($formChoice['type'] == 'text')
                        <input name="{{$formKey}}" type="text">
                    @endif
                </div>
            </div>
        </div>
    @endforeach
    <div class="form_btn">
        <button name="confirm_btn">確認ページへ</button>
    </div>
</div>