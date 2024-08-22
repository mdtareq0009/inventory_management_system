@extends('layouts.admin')
@section('title', 'Current Stock')
@section('breadcrumb', 'Current Stock')
@section('body')

<stock-inventory role="{{ auth()->user()->role }}"></stock-inventory>

@endsection