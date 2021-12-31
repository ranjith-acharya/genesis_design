@extends('layouts.app')

@section('title')
Set Price for System Design - Genesis Design
@endsection

@section('content')
<div class="container-fluid black-text">
    <div class="row">
        <div class="col s12">
            <div class="card">
                @if ($message = Session::get('success'))
                <script>
                    toastr.success('{{$message}}', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                </script>
	            @endif
                <div class="card-content">
                    <div class="row">
                        <h3>Update cost for <span class="capitalize">{{ $data->design->name }}</span></h3>
                    </div>
                    <form method="post" action="{{ route('admin.price.update', $data->id) }}">
                    @csrf
                    @method('PUT')
                        <div class="row">
                            <div class="col s6">
                                <input type="text" name="price" class="validate @error('price') invalid @enderror" value="{{ $data->price }}">
                                @error('price')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>    
                        </div>
                        <div class="row">
                            <button type="submit" class="m-t-20 btn waves-effect waves-light teal">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection