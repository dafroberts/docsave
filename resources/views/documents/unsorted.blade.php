@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        <unsorted-documents unsorted-path="{{ base_path('public\\storage\\documents\\unsorted') }}"></unsorted-documents>
    </div>
@endsection