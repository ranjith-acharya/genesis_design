@if (count($breadcrumbs))
    <nav class="transparent z-depth-0">
        <div class="nav-wrapper">
            <div class="col s12 custom-breadcrumb">
                @foreach ($breadcrumbs as $breadcrumb)
                    @if ($breadcrumb->url && !$loop->last)
                        <a href="{{ $breadcrumb->url }}" class="breadcrumb blue-text capitalize">{{ $breadcrumb->title }}</a>
                    @else
                        <span class="breadcrumb prussian-blue-text capitalize">{{ $breadcrumb->title }}</span>
                    @endif
                @endforeach
            </div>
        </div>
    </nav>
@endif
