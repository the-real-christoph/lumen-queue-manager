<x-lumen-queue-manager::layout :queueSelectionOptions="$queueSelectionOptions" :currentQueue="$currentQueue">
    @if (isset($message) && $message)
        <div class="alert alert-warning" role="alert">
            {{ $message }}
        </div>
    @endif
    <div class="row">
        <div class="col-12">Items in "{{ $currentQueue }}":</div>
    </div>
    <div class="row">
        <div class="col-3">
            #ID - Name
        </div>
        <div class="col-4">
            Status
        </div>
        <div class="col-1">
            Attempts
        </div>
        <div class="col-4">
            Actions
        </div>
    </div>
    <?php /**
     * @var \LumenQueueManager\Models\Job $job
     * @var \Illuminate\Pagination\LengthAwarePaginator $jobs
     */ ?>
    @foreach ($jobs as $job)
        <hr/>
        <div class="row">
            <div class="col-3">
                <a class=""
                   href="{{ route('queue-manager-view', ['jobId' => $job->id, 'queue' => $currentQueue, 'page' => $jobs->currentPage()]) }}">
                #{{ $job->id }} -
                {{ $job->getDisplayName() }}</a>
            </div>
            <div class="col-4">
                {{ $job->getStatusText() }}
            </div>
            <div class="col-1">
                {{ $job->attempts }}
            </div>
            <div class="col-4">
                <a class="btn btn-primary btn-danger"
                   href="{{ route('queue-manager-delete', ['jobId' => $job->id, 'queue' => $currentQueue, 'page' => $jobs->currentPage()]) }}">Delete
                    permanently</a>
            </div>
        </div>
    @endforeach
    <hr/>
    <div class="row">
        <div class="col">
            {{ $jobs->links() }}
        </div>
    </div>
</x-lumen-queue-manager::layout>
