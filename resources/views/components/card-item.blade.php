@php
    $card = (isset($card))?$card: ["card" => ["brand" => "", "last4" => "No Card", "exp_month" => "xx", "exp_year" => "xxx"], "id"=>"xxx"];
    $icon = "";
    switch ($card['card']["brand"]){
        case ("amex"):
            $icon = "fab fa-cc-amex";
            break;
        case ("discover"):
            $icon = "fab fa-cc-discover";
            break;
        case ("jcb"):
            $icon = "fab fa-cc-jcb";
            break;
        case ("mastercard"):
            $icon = "fab fa-cc-mastercard";
            break;
        case ("visa"):
            $icon = "fab fa-cc-visa";
            break;
        default:
            $icon = "fal fa-credit-card";
            break;
    }
@endphp

<li class="collection-item avatar">
    <i class="prussian-blue-text white {{$icon}}"></i>
    <span class="title">Ends with: <span class="steel-blue-text"> {{$card['card']["last4"]}}</span></span>
    <p>Expires: <span class="steel-blue-text">{{$card['card']["exp_month"] }} / {{$card['card']["exp_year"] }}</span></p>
    @if ($showButton)
        <a class="secondary-content steel-blue-text make-default" data-id="{{$card['id'] }}">Make Default</a>
    @endif
</li>
