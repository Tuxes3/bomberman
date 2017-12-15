<?php
/*
 * Copyright (c) 2017, whatwedo GmbH
 * All rights reserved
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace bomberman\io;

use bomberman\components\field\FieldCell;
use bomberman\components\field\Player;
use bomberman\components\Room;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * Class DataCollection
 * @package bomberman\io
 */
class RoomCollection extends ArrayCollection implements \JsonSerializable
{

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @param string $uniqueId
     */
    public function removeUniqueId($uniqueId)
    {
        /** @var Room $room */
        foreach ($this as $key => $room) {
            if ($room->getUniqueId() == $uniqueId) {
                $this->remove($key);
                break;
            }
        }
    }

    /**
     * @return string
     */
    public function getFreeUniqueId()
    {
        $mayNextId = substr(md5(openssl_random_pseudo_bytes(128)), 0, 8);
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('uniqueId', $mayNextId));
        $tmp = $this->matching($criteria);
        while ($tmp->count() > 0) {
            $mayNextId = substr(md5(openssl_random_pseudo_bytes(128)), 0, 8);
            $criteria = Criteria::create()
                ->where(Criteria::expr()->eq('uniqueId', $mayNextId));
            $tmp = $this->matching($criteria);
        }
        return $mayNextId;
    }

    /**
     * @param $uniqueId
     * @return Room|null
     */
    public function findRoomByUniqueId($uniqueId)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('uniqueId', $uniqueId));
        $match = $this->matching($criteria);
        if ($match->count() > 0) {
            return $match->first();
        }
        return null;
    }

    /**
     * @param string $uuid
     * @return Player
     */
    public function findPlayerBySender($uuid)
    {
        $room = $this->findRoomBySender($uuid);
        if ($room) {
            return $room->getField()->getFieldCollection()->findPlayerBySender($uuid);
        }
        return null;
    }

    /**
     * @param string $uuid
     * @return Room|null
     */
    public function findRoomBySender($uuid)
    {
        $room = $this->filter(function (Room $room) use ($uuid) {
            return in_array($uuid, $room->getConnectedPlayers());
        });
        /** @var Room|bool $room */
        $room = $room->first();
        if ($room) {
            return $room;
        }
        return null;
    }

}