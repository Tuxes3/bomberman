<?php
/*
 * This file is part of the bomberman project.
 *
 * @author Nicolo Singer tuxes3@outlook.com
 * @author Lukas Müller computer_bastler@hotmail.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace bomberman\components\field;

use bomberman\io\Config;

/**
 * Class Player
 * @package bomberman\components\field
 */
class Player extends BaseInCell
{

    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var int
     */
    protected $lastMoved;

    /**
     * @var float
     */
    protected $movementSpeed;

    /**
     * @var int
     */
    protected $bombCount;

    /**
     * @var int
     */
    protected $explosionSpread;

    /**
     * @var boolean
     */
    protected $alive = true;

    /**
     * @var boolean
     */
    protected $canMoveBombs = false;

    /**
     * Player constructor.
     * @param $x
     * @param $y
     * @param $uuid
     */
    public function __construct($x, $y, $uuid)
    {
        parent::__construct($x, $y);
        $this->uuid = $uuid;
        $this->lastMoved = milliseconds();
        $this->movementSpeed = Config::get(Config::MOVEMENT_SPEED);
        $this->bombCount = Config::get(Config::BOMB_COUNT);
        $this->explosionSpread = Config::get(Config::EXPLOSION_SPREAD);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'alive' => $this->alive,
            'uuid' => $this->uuid,
        ]);
    }

    /**
     * @return array
     */
    public function backup()
    {
        return array_merge(parent::backup(), [
            'lastMoved' => $this->lastMoved,
            'movementSpeed' => $this->movementSpeed,
            'bombCount' => $this->bombCount,
            'explosionSpread' => $this->explosionSpread,
            'uuid' => $this->uuid,
            'canMoveBombs' => $this->canMoveBombs,
        ]);
    }

    /**
     * @param array $data
     * @return Player
     */
    public static function restore($data)
    {
        $player = new Player($data['x'], $data['y'], $data['uuid']);
        $player->lastMoved = $data['lastMoved'];
        $player->movementSpeed = $data['movementSpeed'];
        $player->bombCount = $data['bombCount'];
        $player->explosionSpread = $data['explosionSpread'];
        $player->canMoveBombs = $data['canMoveBombs'];
        return $player;
    }

    /**
     * @return boolean
     */
    public function canPlayerEnter()
    {
        return true;
    }

    /**
     * @return boolean
     */
    public function blocksExplosion()
    {
        return false;
    }

    /**
     *
     */
    public function setCanMoveBombs()
    {
        $this->canMoveBombs = true;
    }

    /**
     * @return bool
     */
    public function isCanMoveBombs()
    {
        return $this->canMoveBombs;
    }

    /**
     * @return bool
     */
    public function canBombEnter()
    {
        return true;
    }

    /**
     * @return boolean
     */
    public function canPlayerMove()
    {
        return (milliseconds() - $this->lastMoved) > $this->movementSpeed && $this->alive;
    }

    /**
     * @return int
     */
    public function getDisplayPriority()
    {
        return BaseInCell::BASE_PRIORITY;
    }

    /**
     * @return int
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @return $this
     */
    public function setLastMoved()
    {
        $this->lastMoved = milliseconds();
        return $this;
    }

    /**
     * @return int
     */
    public function getMovementSpeed()
    {
        return $this->movementSpeed;
    }

    /**
     *
     */
    public function setDead()
    {
        $this->alive = false;
    }

    /**
     * @return int
     */
    public function getExplosionSpread()
    {
        return $this->explosionSpread;
    }

    /**
     * @return bool
     */
    public function isAlive()
    {
        return $this->alive;
    }

    /**
     * @return int
     */
    public function getBombCount()
    {
        return $this->bombCount;
    }

    /**
     *
     */
    public function incrementBombCount()
    {
        $this->bombCount++;
    }

    /**
     *
     */
    public function incrementExplosionSpread()
    {
        $this->explosionSpread++;
    }

    /**
     *
     */
    public function decreaseMovementSpeed()
    {
        if ($this->movementSpeed > Config::get(Config::MAX_MOVEMENT_SPEED)) {
            $this->movementSpeed = $this->movementSpeed - Config::get(Config::ITEM_MOVEMENT_SPEED_DECREASE);
        }
    }

}
