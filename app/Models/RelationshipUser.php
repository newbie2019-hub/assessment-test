<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationshipUser extends Model
{
    use HasFactory;
    public $fillable = ['relationship_id', 'user_id', 'related_user_id'];

    public function type()
    {
        return $this->belongsTo(Relationship::class, 'relationship_id', 'id');
    }

    public function relatedUser()
    {
        return $this->belongsTo(User::class, 'related_user_id', 'id');
    }

    public function userRelated()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
