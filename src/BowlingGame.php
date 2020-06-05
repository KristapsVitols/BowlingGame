<?php

namespace PF;

use PF\Exceptions\BowlingGameException;

class BowlingGame
{
    public array $rolls = [];

    public function roll(int $points): void
    {
        $this->validatePoints($points);

        $this->rolls[] = $points;
    }

    public function getScore(): int
    {
        $this->validateRolls();

        $score = 0;
        $roll = 0;

        for ($frame = 0; $frame < 10; $frame++) {
            if ($this->isStrike($roll)) {
                $score += $this->getScoreForStrike($roll);
                $roll++;
                continue;
            }

            if ($this->isSpare($roll)) {
                $score += $this->getSpareBonus($roll);
            }

            $score += $this->getNormalScore($roll);
            $roll += 2;
        }

        return $score;
    }

    /**
     * @param  int  $roll
     *
     * @return int
     */
    public function getNormalScore(int $roll): int
    {
        return $this->rolls[$roll] + $this->rolls[$roll + 1];
    }

    /**
     * @param  int  $roll
     *
     * @return bool
     */
    public function isSpare(int $roll): bool
    {
        return $this->getNormalScore($roll) === 10;
    }

    /**
     * @param  int  $roll
     *
     * @return int
     */
    public function getSpareBonus(int $roll): int
    {
        return $this->rolls[$roll + 2];
    }

    /**
     * @param  int  $roll
     *
     * @return bool
     */
    public function isStrike(int $roll): bool
    {
        return $this->rolls[$roll] === 10;
    }

    /**
     * @param  int  $roll
     *
     * @return int
     */
    public function getScoreForStrike(int $roll): int
    {
        return 10 + $this->rolls[$roll + 1] + $this->rolls[$roll + 2];
    }

    /**
     * @param  int  $points
     *
     * @throws BowlingGameException
     */
    private function validatePoints(int $points): void
    {
        if ($points < 0 || $points > 10) {
            throw new BowlingGameException();
        }
    }

    /**
     * @throws BowlingGameException
     */
    private function validateRolls(): void
    {
        if (count($this->rolls) > 21 || count($this->rolls) < 12) {
            throw new BowlingGameException();
        }
    }
}