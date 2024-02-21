<div x-data="fileUpload()"  x-on:drop.prevent="handleFileDrop($event)" x-on:dragover.prevent="isDropping = true" x-on:dragleave.prevent="isDropping = false">
    <div class="z-10 top-0 w-full h-full flex" x-data="{isUploading: false, progress: 0}">
        <div class="extraOutline p-4 bg-white w-max m-auto rounded-lg  bg-gray-400">
            <div class="file_upload p-5 relative rounded-lg border border-gray-300 bg-white"
                 style="width: 800px">
                <template x-if="!isDropping">
                    <svg class="text-indigo-500 w-24 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </template>
                <template x-if="isDropping">
                    <svg class="text-indigo-500 w-24 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                </template>
                <div class="input_field flex flex-col w-max mx-auto text-center">
                    <label>
                        <input class="text-sm cursor-pointer w-36 hidden" @change="handleFileSelect" type="file"/>
                        <div class="text bg-indigo-600 text-white rounded font-semibold cursor-pointer p-1 px-3 hover:bg-indigo-500">
                            Select File or Drop Here
                        </div>
                    </label>
                </div>
            </div>
            <div class="mb-3">
                <label for="name" class="block text-sm font-medium text-gray-700">Project Name</label>
                <input type="text" wire:model.live="name" class="mt-1 p-2 w-full border rounded-md"
                       placeholder="Input name">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="pob_id" class="block text-sm font-medium text-gray-700">Post Office Box</label>
                    <select wire:model.live="post_office_box_id" class="mt-1 p-2 w-full border rounded-md">
                        <option>Select a Post Office Box</option>
                        @foreach($pobs as $id=>$pob)
                            <option value="{{$id}}">{{ucwords($pob)}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="page_num" class="block text-sm font-medium text-gray-700">Page #</label>
                    <input type="number" wire:model.live="page_number" class="mt-1 p-2 w-full border rounded-md"
                           placeholder="Input Page Number">
                </div>
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Amount</label>
                    <input type="number" wire:model.live="amount" class="mt-1 p-2 w-full border rounded-md"
                           placeholder="Input Amount">
                </div>
                <div>
                    <label for="is_paid" class="block text-sm font-medium text-gray-700">Is Paid</label>
                    <select wire:model.live="is_paid" class="mt-1 p-2 w-full border rounded-md">
                        <option>Select a Value</option>
                        <option value="1">Y</option>
                        <option value="0" selected>N</option>
                    </select>
                </div>

            </div>
            <template x-if="isUploading">
                <div class="bg-gray-200 h-[5px] w-full mt-3">
                    <div class="bg-blue-500 h-[3px]" :style="`width: ${progress}%;`">
                    </div>
                </div>
            </template>
        </div>
        <div class="">
            @if ($uploaded_file)
                <div class="p-4 border rounded mt-2  bg-gray-400">
                    <p>Name: {{$name ?? $uploaded_file->getClientOriginalName() }}</p>
                    <p>POB: {{ $pobs[$post_office_box_id] ?? '' }}</p>
                    <p>Paid: {{$is_paid ? 'Y' : 'N'}}</p>
                    <p>Paid: {{$page_number}}</p>
                    <p>Amount: {{$amount}}</p>
                    @if (in_array($uploaded_file->getClientOriginalExtension(), ['png', 'jpg', 'jpeg', 'gif']))
                        <img src="{{ $uploaded_file->temporaryUrl() }}" alt="Preview" class="mt-2 max-w-xs">
                    @endif
                    <button wire:click="saveDocument" class="mt-2 bg-blue-500 text-white p-2 rounded">Save</button>
                    <button wire:click.prevent="removeUploadedFile" class="mt-2 bg-red-500 text-white p-2 rounded">Remove</button>
                </div>
            @endif
        </div>
    </div>

    @if($userFiles->count() > 0)
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mt-4">
            <div class="flex flex-wrap">
                @foreach($userFiles as $file)
                    <div class="p-4 border rounded mt-2 bg-gray-400 w-full flex flex-row justify-between">
                        <div class="float-start flex flex-row">
                            <div>
                                @if (in_array($file->type, ['png', 'jpg', 'jpeg', 'gif']))
                                    <img src="{{ $file->url }}" alt="Preview" class="mt-2 max-w-xs">
                                @else
                                    <svg class="text-indigo-500 w-24 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg"
                                         fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p>Project Name: <strong>{{ $file->name }}</strong></p>
                                <p>Document Type: <strong>{{ $file->type }}</strong></p>
                                <p>Document Size: <strong>{{ number_format($file->size / 1024, 2) }} KB</strong></p>
                                <p>Issue Date: <strong>{{ $file->issue_date }}</strong></p>
                                <p>Paid: <strong>{{ $file->is_paid ? 'Y' : 'N' }}</strong></p>
                                <p>Amount: <strong>{{ $file->amount}}</strong></p>
                            </div>
                        </div>
                        <div class="float-end flex flex-col">
                            <a href="#" wire:click="downloadDocument({{$file->id}})"
                               class="mt-2 bg-blue-500 text-white p-2 rounded">Download</a>
                            <a href="#" wire:click="viewActivity({{$file->id}})"
                               class="mt-2 bg-blue-500 text-white p-2 rounded"
                               x-data="" x-on:click.prevent="$dispatch('open-modal', 'view-activity')">View Activity</a>
                        </div>
                    </div>
                @endforeach
            </div>
            <x-modal name="view-activity" focusable>
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
                                        Activity
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        User
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Description
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Time
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($this->logs)
                                    @foreach($this->logs as $log)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <th scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{$log->activity_type}}
                                            </th>
                                            <th scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{$log->user->name}}
                                            </th>
                                            <th scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                <span>
                                                    {{$log->description}}
                                                </span>
                                            </th>
                                            <th scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{$log->created_at}}
                                            </th>
                                        </tr>
                                    @endforeach
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
        </div>
    @endif
    <script>
        function fileUpload() {
            return {
                isDropping: false,
                isUploading: false,
                progress: 0,
                handleFileSelect(event) {
                    if (event.target.files.length) {
                        this.uploadFile(event.target.files[0]);
                    }
                },
                handleFileDrop(event) {
                    this.isDropping = false;
                    if (event.dataTransfer.files.length > 0) {
                        this.uploadFile(event.dataTransfer.files[0]);
                    }
                },
                uploadFile(file) {
                    const $this = this;
                    this.isUploading = true;
                    @this.upload('uploaded_file', file,
                        function (success) {
                            $this.isUploading = false;
                            $this.progress = 0;
                        },
                        function (error) {
                            console.log('error', error);
                        },
                        function (event) {
                            $this.progress = event.detail.progress;
                        }
                    );
                },
            }
        }
    </script>

</div>
