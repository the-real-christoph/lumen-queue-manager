<x-lumen-queue-manager::layout :queueSelectionOptions="$queueSelectionOptions" :currentQueue="$currentQueue">
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
                Status
            </div>
            <div class="col-1">
                Attempts
            </div>
            <div class="col">
                Actions
            </div>
        </div>
        <?php /** @var \LumenQueueManager\Models\Job $job */ ?>
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
                    {{ $job->getStatusText() }}
                </div>
                <div class="col-1">
                    {{ $job->attempts }}
                </div>
                <div class="col-1">
                    <a href="{{ route('queue-manager-view', ['jobId' => $job->id, 'queue' => $currentQueue]) }}">View</a>
                </div>
                <div class="col-1">
                    <a href="{{ route('queue-manager-delete', ['jobId' => $job->id, 'queue' => $currentQueue]) }}">Delete</a>
                </div>
            </div>
        @endforeach
        <hr/>
        <div class="row">
            <div class="col-3">
                Showing {{ $jobs->count() }} of total {{ $jobs->total() }}
            </div>
        </div>
        <div class="row">
            @if ($jobs->hasMorePages())
                <div class="col-2">
                    @if ($jobs->currentPage() > 1)
                    <a href="{!! $jobs->previousPageUrl() !!}"><< Previous</a>
                    @endif
                </div>
                <div class="col-8">
                </div>
                <div class="col-2">
                    <a href="{!! $jobs->nextPageUrl() !!}">Next >></a>
                </div>
            @endif
        </div>
    </div>

</x-lumen-queue-manager::layout>
