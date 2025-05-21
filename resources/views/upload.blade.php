<x-main>
    <div class="flex flex-row gap-5 flex-wrap">

        @if($bezig)
        <x-widget small noedit title="" bottomText="">
            <p class="text-3xl font-semibold">Er is op het moment al een import bezig. Weet je zeker dat je dit wil doen?</p>
        </x-widget>
        @endif
        <x-widget title="Upload data" bottomText="upload hier het IVS-export als xlsx bestand" small noedit>
            <form action="{{route("upload_data")}}" method="POST" enctype="multipart/form-data" id="upload_form">
                @csrf
                <input required type="file" id="spreadsheet" name="spreadsheet" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                @error("spreadsheet")<p class="text-red-500">{{$message}}</p>@enderror
            </form>
            <button id="upload_button" class="button bg-[var(--grijs-2)]" >Uploaden</button>
        </x-widget>
    </div>
    <script type="application/javascript">
        document.getElementById("upload_button").addEventListener("click", e=>{
            if(document.getElementById("spreadsheet").value !== "") {
                const loader =  document.getElementById("loader");
                loader.style.display = "flex";
                const omschrijving = document.createElement("p");
                omschrijving.innerHTML= "bestand wordt geüpload";
                loader.appendChild(omschrijving);
                document.getElementById("upload_form").submit();
            } else {
                document.getElementById("upload_form").reportValidity();
            }
        });
    </script>
</x-main>
