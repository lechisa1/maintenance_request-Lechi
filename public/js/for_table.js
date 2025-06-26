function toggleDetails(id) {
    const detailsRow = document.getElementById("details-" + id);
    const icon = document.getElementById("icon-" + id);

    if (detailsRow.classList.contains("d-none")) {
        detailsRow.classList.remove("d-none"); // Show details row
        icon.classList.remove("bi-plus-lg"); // Switch icon to minus
        icon.classList.add("bi-dash-lg");
    } else {
        detailsRow.classList.add("d-none"); // Hide details row
        icon.classList.remove("bi-dash-lg"); // Switch icon back to plus
        icon.classList.add("bi-plus-lg");
    }
}
