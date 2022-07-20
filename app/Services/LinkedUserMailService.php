<?php

namespace App\Services;

use App\Mail\UserLinkedRelationship;
use App\Models\Relationship;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class LinkedUserMailService {

  public function sendUserEmail($data)
  {
     $user =  User::find($data['user_id']);
     $relatedUser =  User::find($data['related_user_id']);
     $relationship = Relationship::find($data['relationship_id']);

     Mail::to($user->email)->send(new UserLinkedRelationship($relatedUser, $relationship, 'parent'));
     Mail::to($relatedUser->email)->send(new UserLinkedRelationship($user, $relationship, 'related'));
  }

}
