@extends('layouts.admin')
@section('title', 'Supplier Entry')
@section('breadcrumb', 'Supplier Entry')
@section('body')

<supplier-entry role="{{ auth()->user()->role }}"></supplier-entry>

@endsection