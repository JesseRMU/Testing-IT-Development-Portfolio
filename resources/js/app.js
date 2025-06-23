import './bootstrap';
import './togglevisibility';
import "../css/custom.css";
import "flatpickr/dist/flatpickr.min.css";
import flatpickr from "flatpickr";
import rangePlugin from "flatpickr/dist/plugins/rangePlugin";

flatpickr("#startDate", {
    plugins: [new rangePlugin({ input: "#endDate" })],
    dateFormat: "Y-m-d",
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
