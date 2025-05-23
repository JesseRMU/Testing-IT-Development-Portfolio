function togglevisibility(id) {
    Array.from(document.getElementsByClassName(id)).forEach(e => {
        e.style.display = (e.style.display === 'none') ? 'block' : 'none'
    })
}
