import './bootstrap';
import './togglevisibility';
import "../css/custom.css";
import "flatpickr/dist/flatpickr.min.css";
import flatpickr from "flatpickr";
import rangePlugin from "flatpickr/dist/plugins/rangePlugin";

window.fp = flatpickr("#startDate", {
    plugins: [new rangePlugin({ input: "#endDate" })],
    dateFormat: "Y-m-d",
});
