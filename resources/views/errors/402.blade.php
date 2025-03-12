@extends('errors::minimal')

@section('title', __('error.402'))
@section('code', '402')
@section('message', __($exception->getMessage() ?: 'error.402'))
