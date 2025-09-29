<?php
Broadcast::channel('user.{id}.messages', function ($user, $id) {
    return (int) $user->id === (int) $id;
});