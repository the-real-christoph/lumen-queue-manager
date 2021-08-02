<x-lumen-queue-manager::layout>
    <div class="container">
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
                    <a href="/queue-manager/view/{{ $job->id }}">View</a>
                </div>
                <div class="col-1">
                    <a href="/queue-manager/delete/{{ $job->id }}">Delete</a>
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
