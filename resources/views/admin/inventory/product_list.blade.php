@extends('layouts.admin')
@section('title', 'Product List')
@section('breadcrumb', 'Product List')
@section('body')

<product-list role="{{ auth()->user()->role }}"></product-list>

@endsection