@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Stats ... (keep as implies) --}}

        {{-- SECTION 1: New Available Requests (From Doctors) --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8 border-l-4 border-purple-500">
            <div class="p-6">
                <h3 class="text-lg font-bold text-purple-700 mb-4">New Lab Requests (Unassigned)</h3>
                @if($newRequests->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase">Test</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                            <th class="text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($newRequests as $req)
                        <tr>
                            <td class="py-3">{{ $req->Test }} <span class="text-xs text-gray-500">({{ $req->TestType }})</span></td>
                            <td>{{ $req->patient->First_Name }}</td>
                            <td class="text-right">
                                <form action="{{ route('lab.tests.accept', $req->InvestigationID) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-xs">
                                        Accept & Start
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p class="text-gray-500 italic">No new requests from doctors.</p>
                @endif
            </div>
        </div>

        {{-- SECTION 2: My Pending Work --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8 border-l-4 border-yellow-500">
            <div class="p-6">
                <h3 class="text-lg font-bold text-yellow-700 mb-4">My Pending Work</h3>
                {{-- Table showing $myPending tests with 'Enter Result' button --}}
                 @if($myPending->count() > 0)
                    {{-- ... Table code similar to above ... --}}
                    {{-- Link to edit page to enter result --}}
                 @else
                    <p class="text-gray-500 italic">You have no pending tests.</p>
                 @endif
            </div>
        </div>
    </div>
</div>
@endsection
