@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Welcome, {{ auth()->user()->name }}!</h2>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</div>
@endsection
