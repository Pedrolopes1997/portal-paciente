@extends('layouts.app')

@section('content')
    <livewire:patient-dashboard :tenant_slug="$tenant->slug" lazy />
@endsection