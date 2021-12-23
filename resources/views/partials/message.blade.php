<div class="card-panel p-xxs">
    @if($message->userType->role === "customer")
        <i class="fal fa-user circle green darken-1 white-text honeydew-text mr-xxs" style="font-size: 1.8em;padding: 12px;"></i>
    @else
        <i class="fal fa-user-hard-hat circle blue-grey darken-4 white-text honeydew-text mr-xxs" style="font-size: 1.8em;padding: 12px"></i>
    @endif
    {{$message->message}}
    <div class="small imperial-red-text right-align">
        @if($files)
            <a class="btn steel-blue-outline-button tooltipped  mr-xxs view_files" data-id="{{$message->id}}" data-position="top" data-tooltip="View Attachments"><i class="fal fa-paperclip" style="line-height: 1.6;"
                                                                                                                                                                     data-id="{{$message->id}}"></i></a>
        @endif
        <span class="extra-small"> {{$message->created_at->format('F dS Y - h:i A')}} (UTC)</span>@if (!$message->read && $message->sender_id !== Auth::id())<span class="new badge imperial-red mt-xxs" data-badge-caption="">new</span>@endif
    </div>
</div>
