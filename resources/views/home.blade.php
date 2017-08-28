@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center"><b>SIMPLE TODO LISTS</b></h1>
    <h3 class="text-center" style="margin-top: 0px;">FROM RUBY GARAGE</h3>

    <div class="row">
        <projects class="col-md-8 col-md-offset-2" :data-projects="{{ $projects }}"></projects>
    </div>
</div>
@endsection
