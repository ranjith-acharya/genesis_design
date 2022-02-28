<div class="row">
    <div class="col s12"><br>
        <h5 class="imperial-red-text center-align">Cost Breakdown For Project: <span class="prussian-blue-text">{{$project->name}}</span></h5><br>
        <div class="row">
            <div class="col s12 m6 offset-m3">
                <ul class="collection with-header">
                    <li class="collection-header steel-blue-text"><h6>Existing Designs</h6></li>
                    @php($total = 0)
                    @if (sizeof($project->designs) > 0)
                        @foreach($project->designs as $d)
                            <li class="collection-item capitalize">{{$d->type->name}} <span class="secondary-content prussian-blue-text bold">${{$d->price}}</span></li>
                            @php($total += $d->price)
                        @endforeach
                    @else
                        <li class="collection-item">No designs exist for this project</li>
                    @endif
                    <li class="collection-header steel-blue-text"><h6>This Design</h6></li>
                    @if(is_array($design))
                    @for ($i = 0; $i < count($design); $i++)
                        @php($systemDesign = App\SystemDesignType::where('name', $design[$i])->first())
                        @php($total += $systemDesign->latestPrice->price)
                        <li class="collection-item capitalize">{{ $design[$i] }} <span class="secondary-content prussian-blue-text bold">${{$systemDesign->latestPrice->price}}</span></li>
                    @endfor     
                    @else
                    <li class="collection-item capitalize">{{$design->name}} <span class="secondary-content prussian-blue-text bold">${{$design->latestPrice->price}}</span></li>
                    @php($total += $design->latestPrice->price)
                    @endif
                    <li class="collection-header imperial-red-text"><h6>Total</h6></li>
                    <li class="collection-item capitalize">&nbsp;<span class="secondary-content prussian-blue-text bold">${{$total}}</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>
