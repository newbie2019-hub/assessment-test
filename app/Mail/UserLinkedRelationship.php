<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserLinkedRelationship extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user, $type, $relationship;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $relationship, $type)
    {
       $this->user = $user;
       $this->relationship = $relationship;
       $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@example.com')
                    ->markdown('emails.relationships', [
                        'user' => $this->user,
                        'relationship' => $this->relationship['type'],
                        'type' => $this->type
                    ]);
    }
}
