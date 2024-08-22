@extends('layouts.admin')
@section('title', 'Customer Entry')
@section('breadcrumb', 'Customer Entry')
@section('body')

<customer-entry role="{{ auth()->user()->role }}"></customer-entry>

@endsection