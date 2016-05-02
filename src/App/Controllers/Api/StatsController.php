<?php
namespace App\Controllers\Api;

use App\Models\Phrase;
use App\Models\Stat;
use App\Models\User;
use Core\BaseApiController;
use Core\Http\Exception\BadRequestException;
use Core\Http\Exception\NotFoundException;

class StatsController extends BaseApiController
{
    public function record()
    {
        $phraseId = $this->json('phrase_id');
        $userId = $this->json('user_id');
        $username = $this->json('username');
        $milliseconds = $this->json('milliseconds', 0);
        $wpm = $this->json('wpm', 0);
        $nwpm = $this->json('nwpm', 0);
        $errors = $this->json('errors', 0);
        $color = $this->json('color', 'black');
        $isMobile = $this->json('isMobile', false);
        $isTablet = $this->json('isTablet', false);
        $isDNQ = $this->json('isDNQ', false);

        if (! $userId) {
            // Generate UserId:
            $username = User::generateUsername();
            $userId = User::createNew(['username' => $username]);
        } else {
            // Update Username
            User::update($userId, ['username' => $username]);
        }

        $statId = Stat::createNew([
            'phrase_id' => $phraseId,
            'user_id' => $userId,
            'milliseconds' => $milliseconds,
            'wpm' => $wpm,
            'nwpm' => $nwpm,
            'errors' => $errors,
            'color' => $color,
            'isMobile' => $isMobile,
            'isTablet' => $isTablet,
            'isDNQ' => $isDNQ,
        ]);

        $stats = Stat::findById($statId);
        $transform = Stat::transform($stats, ['username' => $username]);

        return $this->success($transform);
    }
}