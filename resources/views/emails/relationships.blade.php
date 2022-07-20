@component('mail::message')
# User Relation Notification

@if ($type == 'parent')
You have been linked by the admin as a {{ $relationship->type }} of {{ $user->last_name }}, {{ $user->first_name }}.
@else
A user: {{ $user->last_name }}, {{ $user->first_name }} - has been linked to you by the admin with the relation of {{ $relationship->type }}.
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
