import './bootstrap';
import './togglevisibility';
import "../css/custom.css";
import "flatpickr/dist/flatpickr.min.css";
import flatpickr from "flatpickr";
import rangePlugin from "flatpickr/dist/plugins/rangePlugin";

let occupiedDates = [];

document.addEventListener('DOMContentLoaded', () => {

    const startDatePicker = flatpickr("#startDate", {
        plugins: [new rangePlugin({ input: "#endDate" })],
        dateFormat: "Y-m-d",
        mode: "range",
        // Disable alle datums die niet in occupiedDates zitten
        disable: [
            function(date) {
                const d = date.toISOString().slice(0, 10);
                return !occupiedDates.includes(d);
            }
        ],
        // Haal bij checken van kalender datums op uit api
        onOpen: function(selectedDates, dateStr, instance) {
            const form = document.getElementById('filters');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);

            const start = instance.config.minDate;
            const end = instance.config.maxDate;

            params.set('start', start);
            params.set('end', end);

            // Fetch beschikbare datums van de server
            fetch(`/api/evenementen/dates?${params.toString()}`)
                .then(res => res.json())
                .then(data => {
                    occupiedDates = data;
                    instance.set('disable', [
                        function(date) {
                            const d = date.toISOString().slice(0, 10);
                            return !occupiedDates.includes(d);
                        }
                    ]);
                    instance.redraw();
                });
        }
    });

    flatpickr("#startTime", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
    });

    flatpickr("#endTime", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
    });

});
