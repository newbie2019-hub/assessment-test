<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\RelationshipStoreRequest;
use App\Models\Relationship;
use App\Models\RelationshipUser;
use App\Services\LinkedUserMailService;
use App\Services\RelationshipService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserRelationshipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserService $service)
    {
        $users =  $service->getUsersWithRelationship();
        $relationships = $service->getRelationships();
        return inertia('Dashboard', compact(['users', 'relationships']));
    }

    public function getUsers(UserService $service, Request $request)
    {
        $modalUsers = $service->getLinkedWithUsers($request->search);
        return response()->json($modalUsers);
    }

    public function store(RelationshipStoreRequest $request, RelationshipService $service, LinkedUserMailService $mail)
    {
        $data = $service->isDuplicate($request->validated());
        if(!empty($data)) return redirect()->back()->withErrors(['message' => 'Relationships should be unique']);

        $service->store($request->validated());
        $mail->sendUserEmail($request->validated());
        return redirect()->back()->with('success', 'Relationship has been linked to the user successfully!');
    }
    
    public function destroy(RelationshipUser $relationship, UserService $service)
    {
        $relationship->delete();
        return redirect()->route('dashboard')->with('success', 'User has been unliked successfully!');
    }
}
