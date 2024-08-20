@extends('layouts.admin')
@section('title', 'Company Profile')
@section('breadcrumb', 'Company Profile')
@section('body')

<company-profile role="{{ auth()->user()->role }}"></company-profile>

@endsection