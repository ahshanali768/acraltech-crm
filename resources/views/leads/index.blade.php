@extends('layouts.app')
@section('content')
<div class="container">
    <h1>My Leads</h1>
    <a href="{{ route('leads.create') }}">Submit New Lead</a>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact Info</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leads as $lead)
                <tr>
                    <td>{{ $lead->name }}</td>
                    <td>{{ $lead->contact_info }}</td>
                    <td>{{ $lead->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@php($slot = null)
