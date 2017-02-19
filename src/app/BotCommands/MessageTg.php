<?php

namespace App\BotCommands;

use App\Models\Players;

class MessageTg
{
    public function handle($words)
    {
        if (count($words) !== 9) {
            return 'players are short.';
        }
        $names = array_map(function($elem){
            return strtolower(trim($elem));
        }, $words);
        array_shift($names);
        $notfound = [];
        $players = [];
        $ranks = [];
        foreach($names as $name) {
            $player = Players::where('name', $name)->first();
            if (is_null($player)) {
                $notfound[] = $name;
            } else {
                $players[] = $player;
                $ranks[] = $player->rank;
            }
        }
        if (count($notfound) > 0) {
            return implode(',', $notfound) . ' not found.';
        }
        $team = $this->getTeam($ranks);
        foreach($players as $index => $player) {
            $player->team = $team[$index];
        }
        $result = array_reduce($players, function($carry, $player) {
            if ($player->team === 1) {
                $carry['team1_member'] .= $player->name . $player->rank;
                $carry['team1_total'] += $player->rank;
            } else {
                $carry['team2_member'] .= $player->name . $player->rank;
                $carry['team2_total'] += $player->rank;
            }
            return $carry;
        }, ['team1_member'=>'', 'team1_total'=>0, 'team2_member'=>'', 'team2_total'=>0]);
        return $result['team1_member'] . '(' . $result['team1_total'] . ') VS (' . $result['team2_total'] . ')' . $result['team2_member'];
    }

    private function getTeam($ranks)
    {
        $patterns = [
            [1,1,1,1,2,2,2,2],
            [1,1,1,2,1,2,2,2],
            [1,1,1,2,2,1,2,2],
            [1,1,1,2,2,2,1,2],
            [1,1,1,2,2,2,2,1],
            [1,1,2,1,1,2,2,2],
            [1,1,2,1,2,1,2,2],
            [1,1,2,1,2,2,1,2],
            [1,1,2,1,2,2,2,1],
            [1,1,2,2,1,1,2,2],
            [1,1,2,2,1,2,2,1],
            [1,1,2,2,2,1,1,2],
            [1,1,2,2,2,2,1,1],
            [1,2,1,1,1,2,2,2],
            [1,2,1,1,2,1,2,2],
            [1,2,1,1,2,2,1,2],
            [1,2,1,1,2,2,2,1],
            [1,2,1,2,1,1,2,2],
            [1,2,1,2,1,2,2,1],
            [1,2,1,2,2,1,1,2],
            [1,2,1,2,2,2,1,1],
            [1,2,2,1,1,1,2,2],
            [1,2,2,1,1,2,2,1],
            [1,2,2,1,2,1,1,2],
            [1,2,2,1,2,2,1,1],
            [1,2,2,2,1,1,1,2],
            [1,2,2,2,1,1,2,1],
            [1,2,2,2,1,2,1,1],
            [1,2,2,2,2,1,1,1],
        ];
        $diff_min = 99;
        $order_min = 0;
        foreach($patterns as $order => $pattern) {
            $sum1 = $sum2 = 0;
            foreach($pattern as $index => $team) {
                if ($team === 1) {
                    $sum1 += $ranks[$index];
                } else {
                    $sum2 += $ranks[$index];
                }
            }
            if ($diff_min > abs($sum1 - $sum2)) {
                $diff_min = abs($sum1 - $sum2);
                $order_min = $order;
            }
        }
        return $patterns[$order_min];
    }
}
