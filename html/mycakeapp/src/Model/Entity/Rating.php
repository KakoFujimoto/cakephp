<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Rating Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $rated_user_id
 * @property int $stars
 * @property string $comments
 * @property int $bidinfo_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\RatedUser $rated_user
 * @property \App\Model\Entity\Bidinfo $bidinfo
 */
class Rating extends Entity
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
        'rated_user_id' => true,
        'stars' => true,
        'comments' => true,
        'bidinfo_id' => true,
        'user' => true,
        'rated_user' => true,
        'bidinfo' => true,
    ];
}
