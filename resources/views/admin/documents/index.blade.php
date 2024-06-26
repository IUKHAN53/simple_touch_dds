<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Documents Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Documents</h3>
                    @if(auth()->user()->isAdmin())
                        <div>
                            <form class="inline" action="{{ route('admin.documents.delete-all') }}"
                                  method="POST">
                                @csrf
                                <button type="submit"
                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-500"
                                        onclick="return confirm('All the documents will be deleted. Are you sure?')">Delete All Documents
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                <div
                    class="p-6 bg-white dark:bg-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-600">
                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400">
                            {{ session('error') }}
                        </div>
                    @endif
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600 w-100" id="documents_table">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-gray-900 dark:text-gray-300">
                                Project Name
                            </th>
                            <th class="px-6 py-4 text-gray-900 dark:text-gray-300">
                                Size
                            </th>
                            <th class="px-6 py-4 text-gray-900 dark:text-gray-300">
                                Paid
                            </th>
                            <th class="px-6 py-4 text-gray-900 dark:text-gray-300">
                                Amount
                            </th>
                            <th class="px-6 py-4 text-gray-900 dark:text-gray-300">
                                Activity Logs
                            </th>
                            <th class="px-6 py-4 text-gray-900 dark:text-gray-300 no-sort">
                                Action
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($documents as $document)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    {{$document->name}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{$document->size}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{$document->is_paid ? 'Yes' : 'No'}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{$document->amount}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <a href="#" class="mt-2 bg-blue-500 text-white p-2 rounded" x-data=""
                                       x-on:click.prevent="$dispatch('open-modal', 'view-activity-{{$document->id}}')">
                                        View Activity
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <form class="inline" action="{{ route('admin.documents.destroy', $document->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-500"
                                                onclick="return confirm('Are you sure?')">Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @foreach ($documents as $document)
        <x-modal name="view-activity-{{$document->id}}" focusable>
            <div class="p-5">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Activities related to the document') }}
                </h2>
                <div class="mt-6">
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Time
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    User
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Activity
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Description
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($document->activityLogs)
                                @foreach($document->activityLogs as $log)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{$log->created_at}}                                </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{$log->user->name}}                                </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ucfirst($log->activity_type)}}ed                               </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{optional($log->document)->name}}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                No activity found
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>
                </div>
            </div>
        </x-modal>
    @endforeach
</x-app-layout>
