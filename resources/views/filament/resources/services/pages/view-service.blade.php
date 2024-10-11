<x-filament-panels::page>


    <h1 class="text-lg font-bold">{{$this->data['name']}}</h1>

    <div class="grid grid-cols-5 gap-4">

        <div class="col-span-1 bg-gray-300 p-4">
            Template:
        </div>

        <div class="col-span-4 bg-white p-4">
            {{ $record->template->name }}
        </div>

        <div class="col-span-1 bg-gray-300 p-4">
            Email Format:
        </div>

        <div class="col-span-4 bg-white p-4">
            {{ $record->email_type }}
        </div>


        <div class="col-span-1 bg-gray-300 p-4">
            Contact Groups:
        </div>

        <div class="col-span-4 bg-white p-4">
            {{ $record->contactGroupsList() }}
        </div>

    </div>
    <h2 class="text-lg font-bold">Email Template:</h2>

    <div class="grid grid-cols-5 gap-4">
        <div class="col-span-1 bg-gray-300 p-4">
            Subject:
        </div>

        <div class="col-span-4 bg-white p-4">
            {{ $record->template->subject }}
        </div>

        <div class="col-span-1 bg-gray-300 p-4">
            Public Placeholders:
        </div>

        <div class="col-span-4 bg-white p-4">
            {{ $record->template->placeholders_list }}
        </div>


        <div class="col-span-1 bg-gray-300 p-4">
            Sensitive Placeholders:
        </div>

        <div class="col-span-4 bg-white p-4">
            {{ $record->template->sensitive_placeholders_list }}
        </div>
    </div>
    <h2 class="text-lg font-bold">API Endpoint:</h2>

    <div class="grid grid-cols-5 gap-4">

        <div class="col-span-1 bg-gray-300 p-4">
            URL:
        </div>

        <div class="col-span-4 bg-white p-4">
            <code>(POST) {{ env('APP_URL') }}/api/services/{{ $record->slug }}/send</code>
        </div>
        <div class="col-span-1 bg-gray-300 p-4">
            Payload:
        </div>

        <div class="col-span-4 bg-white p-4">
            <pre class="p-4 text-small">{!!  json_encode($this->getExampleJson(), JSON_PRETTY_PRINT) !!}</pre>
        </div>

    </div>


</x-filament-panels::page>