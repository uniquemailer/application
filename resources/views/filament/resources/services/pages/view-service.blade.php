<x-filament-panels::page>

    <h3 class="text-lg font-bold">{{$this->data['name']}}</h3>


    <div class="flex">
        <div class="w-32 ">
            Template:
        </div>
        <div class="grow">
            {{ $record->template->name }}
        </div>
    </div>  

    <div class="flex">
        <div class="w-32 ">
            Email Format:
        </div>
        <div class="grow">
            {{ $record->email_type }}
        </div>
    </div>  


    <div class="flex">
        <div class="w-32 ">
            Contact Groups:
        </div>
        <div class="grow">
            {{ $record->contactGroupsList() }}
        </div>
    </div>  


    <h4 class="text-lg font-bold">Email Template:</h4>

    <div class="flex">
        <div class="w-32 ">
            Subject:
        </div>
        <div class="grow">
        {{ $record->template->subject }}
        </div>
    </div>
    <div class="flex">
        <div class="w-32 ">
            Public Placeholders:
        </div>
        <div class="grow">
            {{ $record->template->placeholders_list }}
        </div>
    </div>
    <div class="flex">
        <div class="w-32 ">
            Sensitive Placeholders:
        </div>
        <div class="grow"> 
            {{ $record->template->sensitive_placeholders_list }}
        </div>
    </div>

    <div class="flex">
        <div class="w-32 ">
            API Endpoint:
        </div>
        <div class="grow">
            <code>(POST) {{ env('APP_URL') }}/api/services/{{ $record->slug }}/send</code>
        </div>
    </div>    
    <div class="flex">
        <div class="w-32 ">
            Payload:
        </div>
        <div class="grow">
            <pre class="bg-gray-100 p-4 text-small">{!!  json_encode($this->getExampleJson(), JSON_PRETTY_PRINT) !!}</pre>
        </div>
    </div>
</x-filament-panels::page>