function togglevisibility(className) {
    Array.from(document.getElementsByClassName(className)).forEach(e => {
        e.style.display = (e.style.display === 'none') ? 'block' : 'none'
    })
}

window.togglevisibility = togglevisibility;
