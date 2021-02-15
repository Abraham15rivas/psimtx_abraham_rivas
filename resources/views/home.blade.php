@extends('layouts.app')

@section('content')
    @if(auth()->user()->role->id == 1)
        <admin-component />
    @else
        <home-component 
            status="{{ session('status') }}"
        ></home-component>
    @endif
@endsection
