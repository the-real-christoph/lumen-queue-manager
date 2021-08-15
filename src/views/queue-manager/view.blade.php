<x-lumen-queue-manager::layout :currentQueue="$currentQueue" :currentTab="$currentTab">
    <div class="container">

        <div class="row">
            <div class="col-2">
                <a class="btn btn-primary"
                   href="{{ route('queue-manager-index', ['queue' => $currentQueue]) }}">Back to overview</a>
            </div>
        </div>
        <div class="row">
            <div class="col-2">
                #ID
            </div>
            <div class="col-2">
                #{{ $job->id }}
            </div>
        </div>
        <div class="row">
            <div class="col-2">
                UUID
            </div>
            <div class="col-4">
                {{ $job->getUuid() }}
            </div>
        </div>
        <div class="row">
            <div class="col-2">
                Name
            </div>
            <div class="col">
                {{ $job->getPayload()['displayName'] }}
            </div>
        </div>
        <div class="row">
            <div class="col-2">
                Data:
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <pre><?php echo json_encode($job->getPayload()['data'], JSON_PRETTY_PRINT); ?></pre>
            </div>
        </div>
    </div>
</x-lumen-queue-manager::layout>
