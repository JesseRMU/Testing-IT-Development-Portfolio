<x-main>
    <form id="filters">
        <x-widget title="Filteren op veld">
            <div class="overflow-y-auto max-h-160">
                <x-filter table="evenementen" name="schip_type" type="checkbox" />
                <x-filter table="wachthavens" name="wachthaven_id" titles="wachthaven_naam" type="checkbox" />
            </div>
        </x-widget>
    </form>
    <script type="application/javascript">
        const links = [... document.getElementById("links").querySelectorAll("a")];
        const filters = document.getElementById("filters");
        filters.addEventListener("input", () => {
          const formData = new FormData(filters);
          const params = new URLSearchParams(formData);
          window.history.replaceState(null, undefined, window.location.href.replaceAll(window.location.search, "?" + params.toString())); //  "?" + params.toString();
          for (const link of links){
            const url = new URL(link.href);
            url.search = "?" + params.toString();
            link.href = url.href;
          }

          console.log(params.toString());
        });
    </script>
</x-main>
