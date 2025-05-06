document.getElementById("contactForm").addEventListener("submit", function(event) {
    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const mobile = document.getElementById("mobile").value.trim();
    const comment = document.getElementById("comment").value.trim();

    if (!name || !email || !mobile || !comment) {
        alert("All fields are required!");
        event.preventDefault();
    }
});
