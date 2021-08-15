<x-lumen-queue-manager::layout :queueSelectionOptions="$queueSelectionOptions" :currentQueue="$currentQueue"
                               :currentTab="$currentTab">
    <div class="container">
        @if (isset($message) && $message)
            <div class="alert alert-warning" role="alert">
                {{ $message }}
            </div>
        @endif
        <div class="row">
            <div class="col-12">Items in "{{ $currentQueue }}":</div>
        </div>
        <div class="row">
            <div class="col-1">
                #ID
            </div>
            <div class="col-2">
                Name
            </div>
            <div class="col-4">
                Exception message
            </div>
            <div class="col-2">
                Failed at
            </div>
            <div class="col">
                Actions
            </div>
        </div>
        <?php /**
         * @var \LumenQueueManager\Models\FailedJob $job
         * @var \Illuminate\Pagination\LengthAwarePaginator $jobs
         */ ?>
        @foreach ($jobs as $job)
            <hr/>
            <div class="row">
                <div class="col-1">
                    #{{ $job->id }}
                </div>
                <div class="col-2">
                    {{ $job->getDisplayName() }}
                </div>
                <div class="col-4">
                    {{ $job->getExceptionPreviewText() }}
                    <a href="{{ route('queue-manager-failed-view', ['jobId' => $job->id, 'queue' => $currentQueue, 'page' => $jobs->currentPage()]) }}">View
                        Details</a>
                </div>
                <div class="col-2">
                    {{ $job->failed_at }}
                </div>
                <div class="col-1">
                    <a href="{{ route('queue-manager-failed-retry', ['jobUuid' => $job->uuid, 'queue' => $currentQueue, 'page' => $jobs->currentPage()]) }}">Retry
                        Job</a>
                </div>
                <div class="col-2">
                    <a href="{{ route('queue-manager-failed-delete', ['jobId' => $job->id, 'queue' => $currentQueue, 'page' => $jobs->currentPage()]) }}">Delete
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
    </div>

</x-lumen-queue-manager::layout>
