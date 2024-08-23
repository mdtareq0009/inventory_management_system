@extends('layouts.admin')
@section('title', 'Sale Return Record')
@section('breadcrumb', 'Sale Return Record')
@section('body')

<sale-return-record role="{{ auth()->user()->role }}"></sale-return-record>

@endsection