// Open the modal
function openModal() {
    document.getElementById("contactModal").style.display = "block";
}

// Close the modal
function closeModal() {
    document.getElementById("contactModal").style.display = "none";
}

// Close modal when clicking outside of the modal
window.onclick = function (event) {
    var modal = document.getElementById("contactModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
}