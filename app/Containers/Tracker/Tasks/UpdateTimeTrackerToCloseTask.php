<?php

namespace App\Containers\Tracker\Tasks;

use App\Containers\Tracker\Data\Repositories\TimeTrackerRepository;
use App\Containers\Tracker\Models\TimeTracker;
use App\Port\Task\Abstracts\Task;
use Carbon\Carbon;

/**
 * Class UpdateTimeTrackerToCloseTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class UpdateTimeTrackerToCloseTask extends Task
{

    /**
     * @var  \App\Containers\Tracker\Data\Repositories\TimeTrackerRepository
     */
    private $timeTrackerRepository;

    /**
     * CloseNonClosedTimeTrackerTasks constructor.
     *
     * @param \App\Containers\Tracker\Data\Repositories\TimeTrackerRepository $timeTrackerRepository
     */
    public function __construct(TimeTrackerRepository $timeTrackerRepository)
    {
        $this->timeTrackerRepository = $timeTrackerRepository;
    }

    /**
     * @param \App\Containers\Tracker\Models\TimeTracker $timeTracker
     *
     * @return  \App\Containers\Tracker\Models\TimeTracker|mixed
     */
    public function run(TimeTracker $timeTracker)
    {
        if ($timeTracker && $timeTracker->status == TimeTracker::PENDING) {

            $now = Carbon::now();

            // get the time between now and when it was opened
            $durationsSeconds = $now->diffInSeconds($timeTracker->open_at);

            $data = [
                'status'   => TimeTracker::SUCCEEDED,
                'close_at' => $now,
                'duration' => $durationsSeconds,
            ];
            $this->timeTrackerRepository->update($data, $timeTracker->id);

            return true;
        }

        return false;
    }
}
