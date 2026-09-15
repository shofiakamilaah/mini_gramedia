@extends('layout.app')
@section('content')
<div class="container mt-5">
    <h1>ADMIN DASHBOARD</h1>
    <p>Welcome, {{Auth::user()->name}}!</p>
</div>
@endsection



