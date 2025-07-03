<?php
$editConfArray = [
    'name' => [
        'formType'    => 'inputText',
        'formTitle'   => '名前',
        'placeholder' => '○○ ○○',
        'isNeed'      => 1,
        'remarks'     => ''
    ],
    'kana' => [
        'formType'    => 'inputText',
        'formTitle'   => 'フリガナ',
        'placeholder' => '○○ ○○',
        'isNeed'      => 0,
        'remarks'     => ''
    ], 
    'address' => [
        'formType'    => 'inputText',
        'formTitle'   => '住所',
        'placeholder' => '○○県△△市',
        'isNeed'      => 0,
        'remarks'     => ''
    ],
    'birthday' => [
        'formType'    => 'inputText',
        'formTitle'   => '生年月日',
        'placeholder' => '2024/01/01',
        'isNeed'      => 1,
        'remarks'     => ''
    ],
    'tel' => [
        'formType'    => 'inputText',
        'formTitle'   => '電話番号',
        'placeholder' => '080XXXX0000',
        'isNeed'      => 1,
        'remarks'     => '(電話番号・メールアドレスどちらか必須)'
    ],
    'mail' => [
        'formType'    => 'inputText',
        'formTitle'   => 'メールアドレス',
        'placeholder' => 'XXXXX@yyy.jp',
        'isNeed'      => 1,
        'remarks'     => '(電話番号・メールアドレスどちらか必須)'
    ],
    'is_sent_auto_msg' => [
        'formType'     => 'radio',
        'formTitle'    => '自動メッセージ送信を行うか',
        'choices'      => [
            'メッセージ送信する'   => 1,
            'メッセージ送信しない' => 0
        ],
        'isNeed'  => 1,
        'remarks' => ''
    ],
    'entry_date'    => [
        'formType'  => 'entryDate',
        'formTitle' => '応募日時',
        'isNeed'    => 1,
        'remarks'   => ''
    ],
    'app_media'      => [
        'formType'   => 'select',
        'formTitle'  => '応募媒体',
        'choiceConf' => 'Job.mediaSelect',
        'isNeed'     => 1,
        'remarks'    => ''
    ],
    'entry_job'       => [
        'formType'    => 'inputText',
        'formTitle'   => '応募案件名',
        'placeholder' => '',
        'isNeed'      => 1,
        'remarks'     => ''
    ],
];

return $editConfArray;

