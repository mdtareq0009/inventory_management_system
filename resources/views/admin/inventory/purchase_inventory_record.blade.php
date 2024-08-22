@extends('layouts.admin')
@section('title', 'Purchase Record')
@section('breadcrumb', 'Purchase Record')
@section('body')

<purchase-inventory-record role="{{ auth()->user()->role }}"></purchase-inventory-record>

@endsection