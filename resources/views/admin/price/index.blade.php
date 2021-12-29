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
                        <h3>List of System Design</h3>
                    </div>
                    <table id="zero_config" class="responsive-table display">
                        <thead>
                            <tr class="black-text">
                                <th>Design Name</th>
                                <th>Design Cost</th>
                                <th>Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($systemDesignPrice as $data)
                            <tr>
                                <td class="capitalize">{{ $data->design->name }}</td>
                                <td>{{ $data->price }}</td>
                                <td>
                                    <a href="{{ route('admin.price.update', $data->id) }}"><button type="submit" class="btn-small blue"><i class="material-icons">edit</i></button></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection