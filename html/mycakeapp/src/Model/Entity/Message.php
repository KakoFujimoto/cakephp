<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Message Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $message
 * @property int $bidinfo_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Bidinfo $bidinfo
 */
class Message extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => true,
        'message' => true,
        'bidinfo_id' => true,
        'user' => true,
        'bidinfo' => true,
    ];
}
