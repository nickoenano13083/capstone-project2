@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="w-full px-4 sm:px-6">
        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h2 class="text-2xl font-semibold text-gray-800">Invitation Codes</h2>
                    <a href="{{ route('invitations.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 border border-blue-700 rounded-lg font-medium text-sm text-gray uppercase tracking-wider shadow-md hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5">
                        <i class="fas fa-plus-circle mr-2 text-lg"></i> Generate New Code
                    </a>
                </div>

                @if (session('success'))
                    <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 px-4 py-2 bg-red-100 border border-red-200 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="bg-white rounded-lg">
                    @if($invitations->isEmpty())
                        <div class="p-6 text-center text-gray-500">
                            No invitation codes have been generated yet.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires At</th>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Used By</th>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($invitations as $invitation)
                                        <tr>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                                {{ $invitation->code }}
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $invitation->email ?? 'Any email' }}
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                                @if($invitation->isExpired())
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Expired
                                                    </span>
                                                @elseif($invitation->isUsed())
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Used
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $invitation->expires_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $invitation->user->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                @if(!$invitation->isUsed() && !$invitation->isExpired())
                                                    <form action="{{ route('invitations.revoke', $invitation) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to revoke this invitation code?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                                            <i class="fas fa-ban mr-1"></i> Revoke
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 sm:px-6 py-4">
                            {{ $invitations->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
