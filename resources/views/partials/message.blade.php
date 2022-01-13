<div class="card-panel p-xxs">
    @if($message->userType->role === "customer")
        <img src="{{ asset('assets/images/users/customer.png') }}" height="50px" width="50px" class="rounded tooltipped" data-tooltip="Customer!" data-position="bottom">
    @elseif($message->userType->role === "manager")
        <img src="{{ asset('assets/images/users/manager.png') }}" height="50px" width="50px" class="rounded tooltipped" data-tooltip="Manager!" data-position="bottom">
    @else
        <img src="{{ asset('assets/images/users/admin.png') }}" height="50px" width="50px" class="rounded tooltipped" data-tooltip="Admin!" data-position="bottom">
    @endif
    <span style="padding-left:10px;margin-top:-14%;" class="capitalize black-text">{{$message->message}}</span>
    <div class="small blue-text right-align">
        @if($files)
            <a class="btn btn-flat btn-floating white tooltipped  mr-xxs view_files" data-id="{{$message->id}}" data-position="top" data-tooltip="View Attachments">
                <i class="fal fa-paperclip black-text" data-id="{{$message->id}}"></i></a>
        @endif
        <span class="extra-small"> {{$message->created_at->format('F dS Y - h:i A')}} (UTC)</span>@if (!$message->read && $message->sender_id !== Auth::id())<span class="new badge green " data-badge-caption="">new</span>@endif
    </div>
</div>
