<?php

namespace LumenQueueManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller;
use LumenQueueManager\Models\Job;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class JobsController extends Controller
{

    public function index(Request $request)
    {
        $this->checkForDatabaseQueue();

        $queueInfos = Job::select('queue', DB::raw('count(queue) as numberOfJobs'))->groupBy('queue')->get()->toArray();
        $queueSelectionOptions = ['default' => 'default'];
        foreach($queueInfos as $queueInfo)
        {
            $queueSelectionOptions[$queueInfo['queue']] = $queueInfo['queue'] . ' (' . $queueInfo['numberOfJobs'] . ' items)';
        }

        $currentQueue = $request->input('queue', 'default');

        /** @var LengthAwarePaginator $jobs */
        $jobs = Job::where('queue', $currentQueue)->paginate(config('lumen-queue-manager.itemsPerPage', 10));
        if($jobs->count() == 0 && $jobs->total() > 0)
        {
            $newPage = (int)(($jobs->total()-1) / $jobs->perPage()) + 1;
            return redirect()->route('queue-manager-index', ['page' => $newPage] + $request->all());
        }

        return view('lumen-queue-manager::queue-manager/index', [
            'jobs' => $jobs,
            'queueSelectionOptions' => $queueSelectionOptions,
            'currentQueue' => $currentQueue,
            'message' => $request->input('message', '')
        ]);
    }

    public function view(Request $request, $jobId) {
        $this->checkForDatabaseQueue();
        $currentQueue = $request->input('queue', 'default');

        /** @var Job $job */
        $job = Job::whereId($jobId)->first();
        if(null === $job)
        {
            return redirect()->route('queue-manager-index', [
                'queue' => $request->input('queue', 'default'),
                'message' => "Job cannot be found, maybe it has been deleted or already worked away."
            ]);
        }

        return view('lumen-queue-manager::queue-manager/view', [
            'job' => $job,
            'currentQueue' => $currentQueue,
            'page' => $request->input('page'),
            'currentTab' => 'pending'
        ]);
    }

    public function delete(Request $request, $jobId)
    {
        $this->checkForDatabaseQueue();

        Job::whereId($jobId)->delete();

        $currentQueue = $request->input('queue', 'default');
        return redirect()->route('queue-manager-index', [
            'queue' => $currentQueue,
            'page' => $request->input('page'),
            'message' => "Pending job was deleted permanently."
        ]);
    }

    protected function checkForDatabaseQueue()
    {
        // register Queue component to load related config
        app('queue');
        if(config('queue.default') !== 'database') {
            throw new NotFoundHttpException('Only installations with database queue supported');
        }
    }


}
