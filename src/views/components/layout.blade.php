<html>
<head>
    <title>{{ $title ?? 'Queue Manager' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>
</head>
<body>
<h1>Queue Manager</h1>
@if (isset($queueSelectionOptions))
<form method="get" action="/queue-manager/">
    <div class="row">
    <div class="col-8">
        <select name="queue" class="form-select" aria-label="Default select example">
            <option selected>Select Queue</option>
            @foreach($queueSelectionOptions as $optionValue => $optionText)
                <option value="{{$optionValue}}" {{ $optionValue==$currentQueue ? 'selected' : '' }}>{{$optionText}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-4">
        <button type="submit" class="btn btn-primary mb-3">Select queue</button>
    </div>
    </div>
</form>
@endif
<hr/>
@php($currentTab = $currentTab ?? 'pending')
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ $currentTab === 'pending' ? 'active' : '' }}" aria-current="page"
           href="{{ route('queue-manager-index', ['queue' => $currentQueue]) }}">Pending jobs</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $currentTab === 'failed' ? 'active' : '' }}" aria-current="page"
           href="{{ route('queue-manager-failed-index', ['queue' => $currentQueue]) }}">Failed jobs</a>
    </li>
</ul>
<br/>
{{ $slot }}
</body>
</html>
