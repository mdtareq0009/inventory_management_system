@extends('layouts.admin')
@section('title', 'User Access')
@section('breadcrumb', 'User Access')
@section('body')

<user-access user_id="{{ $id }}"></user-access>

@endsection