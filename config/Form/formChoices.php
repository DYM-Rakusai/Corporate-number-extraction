<?php

use Illuminate\Support\Carbon;

return 
[
    'worksheet' => [
        'birthday' => [
            'title'  => '生年月日',
            'type'   => 'select',
            'choice' => [
                'year'  => range(Carbon::now()->year - 15, Carbon::now()->year - 80),
                'month' => range(1, 12),
                'day'   => range(1, 31),
            ],
            'label' => [
                'year'  => '年',
                'month' => '月',
                'day'   => '日'
            ]
        ],
        'gender' => [
            'title'  => '性別',
            'type'   => 'radio',
            'choice' => [
                'man'   => '男性',
                'woman' => '女性'
            ]
        ],

        'interview_way' => [
            'title' => '面接形式',
            'type' => 'radio',
            'choice' => [
                'face' => '対面',
                'web' => 'WEB',
            ]
        ],
    ],
];
