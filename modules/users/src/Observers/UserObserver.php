<?php

namespace GuPes\Framework\Users\Observers;

use GuPes\Framework\Users\Models\User;

class UserObserver
{
    protected $dependency;

    public function created(User $user)
    {
        $this->log(__METHOD__);
    }

    public function creating(User $user)
    {
        $this->log(__METHOD__);
    }

    public function updated(User $user)
    {
        $this->log(__METHOD__);
    }

    public function updating(User $user)
    {
        $this->log(__METHOD__);
    }

    public function saved(User $user)
    {
        $this->log(__METHOD__);
    }

    public function saving(User $user)
    {
        $this->log(__METHOD__);
    }

    public function deleted(User $user)
    {
        $this->log(__METHOD__);
    }

    public function deleting(User $user)
    {
        $this->log(__METHOD__);
    }

    private function log($data)
    {
        $data .= PHP_EOL;
        file_put_contents(__DIR__ . '/../../../../users.log', $data, FILE_APPEND);
    }
}