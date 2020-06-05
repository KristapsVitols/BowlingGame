<?php

use PF\BowlingGame;
use PF\Exceptions\BowlingGameException;
use PHPUnit\Framework\TestCase;

class BowlingGameTest extends TestCase
{
    public function testGetScore_withAllZeros_getZero()
    {
        // set up
        $game = new BowlingGame();

        for ($i = 0; $i < 20; $i++) {
            $game->roll(0);
        }

        // test
        $score = $game->getScore();

        // asset
        self::assertEquals(0, $score);
    }

    public function testGetScore_withAllOnes_get20asScore()
    {
        // set up
        $game = new BowlingGame();

        for ($i = 0; $i < 20; $i++) {
            $game->roll(1);
        }

        // test
        $score = $game->getScore();

        // asset
        self::assertEquals(20, $score);
    }

    public function testGetScore_withASpare_returnsScoreWithSpareBonus()
    {
        // set up
        $game = new BowlingGame();

        $game->roll(2);
        $game->roll(8);
        $game->roll(5);

        for ($i = 0; $i < 17; $i++) {
            $game->roll(1);
        }

        // test
        $score = $game->getScore();

        // asset
        self::assertEquals(37, $score);
    }

    public function testGetScore_withAStrike_addsStrikeBonus()
    {
        // set up
        $game = new BowlingGame();

        $game->roll(10);
        $game->roll(5);
        $game->roll(3);
        // 10 + 5 (bonus) + 3 (bonus) + 5 + 3 + 16

        for ($i = 0; $i < 16; $i++) {
            $game->roll(1);
        }

        // test
        $score = $game->getScore();

        // asset
        self::assertEquals(42, $score);
    }

    public function testGetScore_withPerfectGame_Returns300()
    {
        // set up
        $game = new BowlingGame();

        for ($i = 0; $i < 12; $i++) {
            $game->roll(10);
        }

        // test
        $score = $game->getScore();

        // asset
        self::assertEquals(300, $score);
    }

    /**
     * @dataProvider pointsDataProvider
     * @param  int $points
     */
    public function testGetScore_withInvalidPoints_throwsException(int $points)
    {
        self::expectException(BowlingGameException::class);

        (new BowlingGame())->roll($points);
    }

    /**
     * @dataProvider rollsDataProvider
     * @param  int  $rolls
     */
    public function testGetScore_withInvalidAmountOfRolls_throwsException(int $rolls)
    {
        $game = new BowlingGame();

        for ($i = 0; $i < $rolls; $i++) {
            $game->roll(1);
        }

        self::expectException(BowlingGameException::class);

        $game->getScore();
    }

    public function pointsDataProvider(): array
    {
        return [
            'Negative points' => [-1],
            'Too many points' => [11],
        ];
    }

    public function rollsDataProvider(): array
    {
        return [
            'Too few rolls' => [11],
            'Too many rolls' => [22],
        ];
    }
}