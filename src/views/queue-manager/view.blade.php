<x-lumen-queue-manager::layout>
<div class="container">

    <div class="row">
        <div class="col-2">
            <a href="/queue-manager/">Back to overview</a>
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
            Data
        </div>
        <div class="col-10">
            <?php echo json_encode($job->getPayload()['data'], JSON_PRETTY_PRINT); ?>
        </div>
    </div>
</div>
</x-lumen-queue-manager::layout>
