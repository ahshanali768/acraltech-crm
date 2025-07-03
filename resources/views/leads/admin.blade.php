@extends('layouts.app')
@section('content')
<div class="container">
    <h1>All Leads</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact Info</th>
                <th>Status</th>
                <th>Agent</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leads as $lead)
                <tr>
                    <td>{{ $lead->name }}</td>
                    <td>{{ $lead->contact_info }}</td>
                    <td>{{ $lead->status }}</td>
                    <td>{{ $lead->user->name ?? 'N/A' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.leads.updateStatus', $lead) }}">
                            @csrf
                            <select name="status">
                                <option value="pending" @if($lead->status=='pending') selected @endif>Pending</option>
                                <option value="approved" @if($lead->status=='approved') selected @endif>Approved</option>
                                <option value="rejected" @if($lead->status=='rejected') selected @endif>Rejected</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@php($slot = null)
