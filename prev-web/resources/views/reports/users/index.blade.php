@extends('layouts.app')
@section('title','User Report')
@section('page-url', route('app.dashboard.report.user'))
@section('page-breadcrumb-group', 'Home')
@section('page-breadcrumb', 'Relatórios / Utilizadores')
@section('page-title', 'Relatório Utilizadores - Listagem')
@section('homepage-group-url', route('app.dashboard'))

@section('content')
<div>
    <x-dashboard.reports.users.user-report :data="$data ?? []" :active_users="$users ?? []"
    />
</div>
@endsection


