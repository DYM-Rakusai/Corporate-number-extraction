<div class="worksheet_confirm d-none">
      @foreach($formChoices as $formKey => $formChoice)
      <div class="choice_content work_term">
            <div class="form_title_frame">
                  <span class="form_title">{{$formChoice['title']}}</span>
            </div>
            <div class="form_confirm">
                  <span name="confirm_answer_{{$formKey}}"
                        class="confirm_answer"></span>
            </div>
      </div>
      @endforeach
    <div class="form_btn">
        <button name="return_btn">戻る</button>
        <button name="complete_btn">回答送信</button>
    </div>
</div>