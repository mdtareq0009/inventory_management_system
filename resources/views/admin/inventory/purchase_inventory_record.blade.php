@extends('layouts.admin')
@section('title', 'Purchase Record')
@section('breadcrumb', 'Purchase Record')
@section('body')

<purchase-record role="{{ auth()->user()->role }}"></purchase-record>

@endsection