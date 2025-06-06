<x-main>
    <form id="filters">
        <x-widget title="Filteren op veld" class="max-w-[400px!important]">
            <div class="overflow-y-auto max-h-160">
                <x-filter table="evenementen" titletable="schip_types" titles="schip_type_naam" name="schip_type" type="checkbox" />
                <x-filter table="evenementen" titletable="wachthavens" name="wachthaven_id" titles="wachthaven_naam" type="checkbox" />
                <x-filter table="wachthavens" titletable="rws_objecten" name="object_id" titles="object_naam" type="checkbox" />
            </div>
        </x-widget>
    </form>
    <script type="application/javascript">
        const links = [... document.getElementById("links").querySelectorAll("a")];
        const filters = document.getElementById("filters");
        filters.addEventListener("input", () => {
          const formData = new FormData(filters);
          const params = new URLSearchParams(formData);
          const url = new URL(window.location.href);
          url.search = "?" + params.toString();
          window.history.replaceState(null, undefined, url.href);
          for (const link of links){
            const url = new URL(link.href);
            url.search = "?" + params.toString();
            link.href = url.href;
          }
        });
    </script>
</x-main>
