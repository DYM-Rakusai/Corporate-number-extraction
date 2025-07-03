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
    'tel' => [
        'formType'    => 'inputText',
        'formTitle'   => '電話番号',
        'placeholder' => '080XXXX0000',
        'isNeed'      => 1,
        'remarks'     => ''
    ],
    'mail' => [
        'formType'    => 'inputText',
        'formTitle'   => 'メールアドレス',
        'placeholder' => 'XXXXX@yyy.jp',
        'isNeed'      => 1,
        'remarks'     => ''
    ],
    'interview_url' => [
        'formType'    => 'inputText',
        'formTitle'   => '面接URL',
        'placeholder' => 'Web面接のURL',
        'isNeed'      => 0,
        'remarks'     => ''
    ]
];

return $editConfArray;

