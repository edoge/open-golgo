<?php

namespace App\Console\Commands;

use App\Models\Players;

class TeamGenerator
{
    public static function make($names)
    {
        if (count($names) !== 8) {
            return 'players are short.';
        }
        $notfound = [];
        foreach($names as $name) {
            $player = Players::where('name', strtolower(trim($name)))->first();
            if (is_null($player)) {
                $notfound[] = $name;
            }
        }
        if (count($notfound) > 0) {
            return implode(',', $notfound) . ' not found.';
        }
        $players = Players::whereIn('name', $names)->orderBy('rank', 'desc')->get();
        $team1 = $team2 = $team1_cnt = $team2_cnt = 0;
        foreach($players as $player) {
            if ($team1 > $team2) {
                $team2 += $player->rank;
                $player->team = 2;
                $team2_cnt++;
            } else {
                $team1 += $player->rank;
                $player->team = 1;
                $team1_cnt++;
            }
        }
        $result = array_reduce($players->all(), function($carry, $player) {
            if ($player->team === 1) {
                $carry['team1_member'] .= $player->name . $player->rank;
                $carry['team1_total'] += $player->rank;
            } else {
                $carry['team2_member'] .= $player->name . $player->rank;
                $carry['team2_total'] += $player->rank;
            }
            return $carry;
        }, ['team1_member'=>'', 'team1_total'=>0, 'team2_member'=>'', 'team2_total'=>0]);
        return $result['team1_member'] . ' VS ' . $result['team2_member'] . '__' .
               $result['team1_total']  . ' VS ' . $result['team2_total'];
    }
}
