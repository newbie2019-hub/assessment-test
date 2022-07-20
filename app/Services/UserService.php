<?php

namespace App\Services;

use App\Models\Relationship;
use App\Models\User;

class UserService {
 
 public function getUsersWithRelationship($search = '')
 {
      return User::notAdmin()->with([
          'relationships', 
          'relationships.type', 
          'relationships.userRelated',
          'relatedUsers', 
          'relatedUsers.type', 
          'relatedUsers.relatedUser',
      ])->paginate(10);
 }

 public function getRelationships()
 {
      return Relationship::get(['id', 'type']);
 }

 public function getLinkedWithUsers($search = '')
 {
      return User::notAdmin()->with('relationships')->when($search, fn($query) => 
                   $query->whereLike('first_name', $search)
                   ->orWhereLike('last_name', $search)
               )->latest()->take(15)->get(['id','first_name','last_name','email']);
 }



}
