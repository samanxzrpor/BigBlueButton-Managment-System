<?php

namespace App\Repositories\MeetingRepository;

use App\Models\Meeting;


interface MeetingRepositoryInterface
{

    public function create(array $data): void;

    public function update(Meeting $meeting, mixed $data): void;

    public function makeGuestLink(Meeting $meeting): string;

}
