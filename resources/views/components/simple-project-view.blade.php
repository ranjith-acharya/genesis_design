@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUREr9JDgVEAJu_yv-LQFvWjfNDMY2NIU&libraries=places"></script>
    <script type="text/javascript">
        let map;
        document.addEventListener("DOMContentLoaded", function () {
            map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: {{$project->latitude }},
                    lng: {{$project->longitude}}
                },
                zoom: 16,
                mapTypeId: 'satellite'
            });
            let marker = new google.maps.Marker({
                map: map,
                position: map.center
            });
            map.panTo(marker.getPosition());
        });
    </script>
@endpush

<div class="row">
    <div class="col s12">
        <span class="prussian-blue-text"><b>Description</b></span>
        <blockquote class="mt-xxs">
            {{ $project->description }}
        </blockquote>
    </div>
    <div class="col s12 m6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Street 1: </b></span>
            {{ $project->street_1 }}
        </div>
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>City: </b></span>
            {{ $project->city }}
        </div>
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Zip: </b></span>
            {{ $project->zip }}
        </div>
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Latitude: </b></span>
            {{ $project->latitude }}
        </div>
    </div>
    <div class="col s12 m6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Street 2: </b></span>
            {{ $project->street_2 }}
        </div>
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>State: </b></span>
            {{ $project->state }}
        </div>
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Country: </b></span>
            {{ $project->country }}
        </div>
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Longitude: </b></span>
            {{ $project->longitude }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col s12 m6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Project Type: </b></span>
            {{ $project->type->name }}
        </div>
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Project Status: </b></span>
            {{ $project->status }}
        </div>
    </div>
    <div class="col s12 m6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Created On: </b></span>
            {{ $project->created_at }} (UTC)
        </div>
    </div>
</div>
<div class="row">
    <div class="col s12"><br>
        <div id="map" style="height: 30em"></div>
    </div>
</div><br>
<div class="row">
    <div class="col s12">
        <h4>Project File Attachments</h4>
        @component('components.list-project-files', ['fileTypes' => $fileTypes, 'project' => $project, 'path' => route('engineer.project.file.get')])@endcomponent
    </div>
</div>
