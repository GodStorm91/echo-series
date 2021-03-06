<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('tasks{project}', function ($user, \App\Project $project) {
    // return (int) $user->id === (int) $id;
    if ($project->participants->contains($user)){
        return ['name' => $user->name];
    }
});
