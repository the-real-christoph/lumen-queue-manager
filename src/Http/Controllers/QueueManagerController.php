<?php

namespace LumenQueueManager\Http\Controllers;

use LumenQueueManager\Models\Job;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Laravel\Lumen\Routing\Controller;

class QueueManagerController extends Controller
{

    public function index(Request $request)
    {
        $this->checkForDatabaseQueue();

        $jobs = Job::where('queue', 'default')->paginate(config('lumen-queue-manager.itemsPerPage', 10));

        return view('lumen-queue-manager::queue-manager/index', ['jobs' => $jobs]);
    }

    public function view($jobId) {
        $this->checkForDatabaseQueue();

        /** @var Job $job */
        $job = Job::whereId($jobId)->first();

        return view('lumen-queue-manager::queue-manager/view', ['job' => $job]);
    }

    public function delete($jobId)
    {
        $this->checkForDatabaseQueue();

        Job::whereId($jobId)->delete();

        return redirect()->route('queue-manager-index');
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
