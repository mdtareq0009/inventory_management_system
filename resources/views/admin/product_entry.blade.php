@extends('layouts.admin')
@section('title', 'Product Entry')
@section('breadcrumb', 'Product Entry')
@section('body')

<product-entry role="{{ auth()->user()->role }}"></product-entry>

@endsection