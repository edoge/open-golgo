<?php

return [
    'mapping' => [
        'おみくじ' => 'Lottery',
        'rank' => function() { return 'rank 対象を指定してください。'; },
        '神' => function() { return 'GOD'; },
    ],
    'irc_encoding' => 'ISO-2022-JP',
];
