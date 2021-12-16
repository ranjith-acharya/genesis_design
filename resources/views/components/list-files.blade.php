<ul class="collection">
    @foreach($files as $file)
        <li class="collection-item avatar">
            <i class="fal fa-file circle prussian-blue"></i>
            <span class="title prussian-blue-text">{{last(explode("_", $file->path))}}</span>
            <p class="steel-blue-text">{{$file->created_at->format('F dS Y - h:i A')}} (UTC)</p>
            <a class="secondary-content steel-blue-text tooltipped" data-position="left" data-tooltip="Open in a new tab" target="_blank"
               href="{{$path}}&file={{$file->id}}">View</a>
        </li>
    @endforeach
</ul>
