@extends('layouts.app')

@section('content')
    <livewire:patient-profile :tenant_slug="$tenant->slug" lazy />
@endsection