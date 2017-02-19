<?php

return [
    'mapping' => [
        'おみくじ' => 'Lottery',
        'rank' => function() { return 'rank 対象を指定してください。'; },
        '神' => function() { return 'GOD'; },
        '新規干す0' => 'Newhost',
        '新規干す1' => 'Newhost',
        '新規干す2' => 'Newhost',
        '新規干す3' => 'Newhost',
        '新規干す4' => 'Newhost',
        '新規干す5' => 'Newhost',
        '新規干す6' => 'Newhost',
        '新規干す7' => 'Newhost',
    ],
    'irc_encoding' => 'ISO-2022-JP',
    'blob' => [
        'account' => env('BLOB_ACCOUNT'),
        'key' => env('BLOB_KEY'),
        'container' => env('BLOB_CONTAINER'),
    ],
];
