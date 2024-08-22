@extends('layouts.admin')
@section('title', 'Sale Record')
@section('breadcrumb', 'Sale Record')
@section('body')

<sale-record role="{{ auth()->user()->role }}"></sale-record>

@endsection