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

        onDayCreate: function(dObj, dStr, fp, dayElem) {
            const date = dayElem.dateObj.toISOString().slice(0, 10);
            if (!occupiedDates.includes(date)) {
                dayElem.classList.add("unavailable-date");
            }
        },

        // Als kalender geopend word, maakt alle datums zonder data grijs
        onOpen: function(_, __, instance) {
            const form = document.getElementById('filters');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);

            fetch(`/api/evenementen/dates?${params.toString()}`)
                .then(res => res.json())
                .then(data => {
                    occupiedDates = data;
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
