<?php

namespace LumenQueueManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller;
use LumenQueueManager\Models\FailedJob;
use LumenQueueManager\Models\Job;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FailedJobsController extends Controller
{

    public function index(Request $request)
    {
        $this->checkForDatabaseQueue();

        $queueInfos = FailedJob::select('queue', DB::raw('count(queue) as numberOfJobs'))->groupBy('queue')->get()->toArray();
        $queueSelectionOptions = ['default' => 'default'];
        foreach ($queueInfos as $queueInfo) {
            $queueSelectionOptions[$queueInfo['queue']] = $queueInfo['queue'] . ' (' . $queueInfo['numberOfJobs'] . ' items)';
        }

        $currentQueue = $request->input('queue', 'default');

        /** @var LengthAwarePaginator $jobs */
        $jobs = FailedJob::where('queue', $currentQueue)->paginate(config('lumen-queue-manager.itemsPerPage', 10));
        if ($jobs->count() == 0 && $jobs->total() > 0) {
            $newPage = (int)(($jobs->total() - 1) / $jobs->perPage()) + 1;
            return redirect()->route('queue-manager-failed-index', ['page' => $newPage] + $request->all());
        }

        return view('lumen-queue-manager::queue-manager/failed-index', [
            'jobs' => $jobs,
            'queueSelectionOptions' => $queueSelectionOptions,
            'currentQueue' => $currentQueue,
            'message' => $request->input('message', ''),
            'currentTab' => 'failed'
        ]);
    }

    public function view(Request $request, $jobId)
    {
        $this->checkForDatabaseQueue();
        $currentQueue = $request->input('queue', 'default');

        /** @var FailedJob $job */
        $job = FailedJob::whereId($jobId)->first();
        if (null === $job) {
            return redirect()->route('queue-manager-index', [
                'queue' => $request->input('queue', 'default'),
                'message' => "Job cannot be found, maybe it has been deleted or already worked away."
            ]);
        }

        return view('lumen-queue-manager::queue-manager/failed-view', [
            'job' => $job,
            'currentQueue' => $currentQueue,
            'currentTab' => 'failed'
        ]);
    }

    public function delete(Request $request, $jobId)
    {
        $this->checkForDatabaseQueue();

        FailedJob::whereId($jobId)->delete();

        $currentQueue = $request->input('queue', 'default');
        return redirect()->route('queue-manager-failed-index', [
            'queue' => $currentQueue,
            'message' => 'Job was permanently removed.'
        ]);
    }

    public function retry(Request $request, $jobUuid)
    {
        $this->checkForDatabaseQueue();
        $currentQueue = $request->input('queue', 'default');

        /** @var FailedJob $failedJob */
        $failedJob = FailedJob::whereUuid($jobUuid)->first();
        if (null === $failedJob) {
            return redirect()->route('queue-manager-failed-index', [
                'queue' => $currentQueue,
                'message' => 'Job cannot be found!'
            ]);
        }

        $infoMessage = $this->retryUsingManualWay($failedJob); // deactivate once illuminate got patched
        // activate once illuminate got patched $this->retryUsingArtisan($jobUuid);

        return redirect()->route('queue-manager-failed-index', [
            'queue' => $currentQueue,
            'message' => $infoMessage ?? "Job has been rescheduled."
        ]);
    }

    protected function retryUsingManualWay(FailedJob $failedJob)
    {
        $jobData = json_decode($failedJob->payload, true);
        if (isset($jobData['retryUntil'])) {
            $jobData['retryUntil'] = null;
            $infoMessage = "Job had retryUntil attribute, this had to be removed when rescheduling.";
        }

        $rescheduledJob = Job::create([
            'queue' => $failedJob->queue,
            'payload' => json_encode($jobData),
            'attempts' => 0,
            'available_at' => time(),
            'created_at' => time(),
        ]);
        $failedJob->delete();

        return $infoMessage ?? null;
    }

    protected function retryUsingArtisan($jobUuid)
    {
        $outputBuffer = null;
        $result = Artisan::call('queue:retry', ['id' => $jobUuid], $outputBuffer);
        return Artisan::output();
    }

    protected function checkForDatabaseQueue()
    {
        // register Queue component to load related config
        app('queue');
        if (config('queue.default') !== 'database') {
            throw new NotFoundHttpException('Only installations with database queue supported');
        }
    }


}
