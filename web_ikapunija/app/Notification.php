<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'senderId',
        'receiverId',
        'subject',
        'description',
        'type',
        'referenceId',
        'status',
    ];

    public function newQuery($excludeDeleted = true) {
        return parent::newQuery($excludeDeleted)
            ->where('notifications.deleted', 0);
    }
}
