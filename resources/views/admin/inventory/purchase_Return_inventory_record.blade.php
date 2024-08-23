@extends('layouts.admin')
@section('title', 'Purchase Return Record')
@section('breadcrumb', 'Purchase Return Record')
@section('body')

<purchase-return-record role="{{ auth()->user()->role }}"></purchase-return-record>

@endsection