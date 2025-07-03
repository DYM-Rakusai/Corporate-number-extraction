<?php
$statusList = [
    '未対応',
    '自動対応中',
    '重複応募',
    '不採用',
    '担当者日程調整待ち',
    '電話対応待ち',
    '面接日程調整済み',
    'コールセンター対応',
    '面接キャンセル'
];

$statusColors = [
    '未対応'                => '#ff0000',
    '自動対応中'            => '#0000ff',
    '重複応募'              => '#0000ff',
    '不採用'                => '#808080',
    '担当者日程調整待ち'     => '#ff0000',
    '電話対応待ち'           => '#ff0000',
    '面接日程調整済み'       => '#008000',
    'コールセンター対応'     => '#0000ff',
    '面接キャンセル'         => '#0000ff',
];

$patternList = [
    'failure'           => '不採用',
    'pattern1adjust'    => '担当者日程調整待ち',
    'phone_call'        => '電話対応待ち',
    'schedule_date'     => '面接日程調整済み',
    'pattern1decide'    => '面接日程調整済み',
    'callcenter'        => 'コールセンター対応',
];

return [
    'statusList'    => $statusList,
    'statusColors'  => $statusColors,
    'patternList'   => $patternList
];
