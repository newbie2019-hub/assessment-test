<?php

namespace App\Services;

use App\Models\RelationshipUser;

class RelationshipService {

  public function store($data)
  {
        RelationshipUser::create($data);
  }

  public function isDuplicate($data)
  {
    return $user = RelationshipUser::where('user_id', $data['user_id'])
            ->where('related_user_id', $data['related_user_id'])
            ->where('relationship_id', $data['relationship_id'])
            ->first();
  }
}
