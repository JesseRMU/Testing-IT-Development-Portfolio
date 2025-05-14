<x-main>
    <div class="flex flex-row gap-5 flex-wrap">
        <x-widget title="Upload data" bottomText="upload hier het IVS-export als xlsx bestand" small noedit>
            <form action="{{route("upload_data")}}" method="POST" enctype="multipart/form-data" id="upload_form">
                @csrf
                <input type="file" id="spreadsheet" name="spreadsheet" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
            </form>
            <button id="upload_button" class="button bg-[var(--grijs-2)]" >Uploaden</button>
        </x-widget>
    </div>
    <script type="application/javascript">
        document.getElementById("upload_button").addEventListener("click", e=>{
            const loader =  document.getElementById("loader");
            loader.style.display = "flex";
            const omschrijving = document.createElement("p");
            omschrijving.innerHTML= "bestand wordt geüpload";
            loader.appendChild(omschrijving);

            document.getElementById("upload_form").submit();
        });
    </script>
</x-main>
