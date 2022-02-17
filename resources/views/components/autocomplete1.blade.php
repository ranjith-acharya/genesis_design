@push('scripts')
    <script type='text/javascript'>
        document.addEventListener('DOMContentLoaded', function () {
            M.Autocomplete.init(document.getElementById('{{$name}}1'), {
                data: JSON.parse('{!! json_encode($data) !!}')
            });
        });
    </script>
@endpush
<div class="row">
    <div class="input-field col s12">
        <input type="text" id="{{$name}}1" name="{{$name}}" class="autocomplete required" onchange="equipment1(this.value, {{$name}});">
        <label for="{{$name}}1" class="capitalize">{{$name}}</label>
        <span class="helper-text red-text">Required</span>
    </div>
</div>
