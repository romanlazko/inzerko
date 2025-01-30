@extends('errors::minimal')

@section('title', __('error.404'))
@section('code', '404')
@section('message', __($exception->getMessage() ?: 'error.404'))
