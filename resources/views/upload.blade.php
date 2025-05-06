<x-main>
    <div class="flex flex-row gap-5 flex-wrap">
        <x-widget title="Upload data" bottomText="upload hier het IVS-export als xlsx bestand" small noedit>
            <form action="{{route("upload_data")}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" id="spreadsheet" name="spreadsheet" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                <input type="submit" class="button bg-white" >
            </form>
        </x-widget>
    </div>
</x-main>
