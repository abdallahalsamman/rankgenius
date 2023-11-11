<div>
    <x-header size="text-xl font-[700]" title="Batch" />
    <div class="grid grid-row-4">
        <div class="w-full flex items-center px-6 py-3  border-b-[1px] border-base-200 ">
            <div class="w-1/2 font-bold text-sm text-gray-600">STATUS</div>
            <div class="w-1/2">
                @inject('Batch', \App\Models\Batch::class)
                @php
                $STATUS = $Batch::$STATUS;
                $statusColor = [
                $STATUS['IN_PROGRESS'] => "bg-blue-100 text-info-content",
                $STATUS['DONE'] => "bg-green-100 text-success-content",
                $STATUS['CANCELLED'] => "bg-red-100 text-error-content"
                ];
                @endphp
                <div class="{{ $statusColor[$batch->status] }} rounded text-sm w-fit font-medium py-1 px-2">{{ $batch->status }}</div>
            </div>
        </div>

        <div class="w-full flex items-center px-6 py-3  border-b-[1px] border-base-200 ">
            <div class="w-1/2 font-bold text-sm text-gray-600">CONTEXT</div>
            <div class="w-1/2">
                Gorgeous Laravel blade UI components
                made for Livewire 3 and styled around daisyUI + Tailwind
            </div>
        </div>

        <div class="w-full flex items-center px-6 py-3  border-b-[1px] border-base-200 ">
            <div class="w-1/2 font-bold text-sm text-gray-600">LANGUAGE</div>
            <div class="w-1/2">
                English
            </div>
        </div>

        <div class="w-full flex items-center px-6 py-3  border-b-[1px] border-base-200 ">
            <div class="w-1/2 font-bold text-sm text-gray-600">ARTICLES</div>
            <div class="w-1/2">
                1 / 1
            </div>
        </div>

    </div>

    <div class="mt-5 flex justify-between">
        <div>
            <x-button label="asldfjk" />
        </div>
        <div>
            <x-button label="asldfjk" />
        </div>
    </div>
</div>
