@extends('layouts.app')

@section('content')

<div class="container">

    <h1>
        Dashboard
    </h1>

    <p>
        سلام {{ Auth::user()->name }}
    </p>

    <p>
        ایمیل:
        {{ Auth::user()->email }}
    </p>

</div>

@endsection