
window.addEventListener("load", () => {

    document.getElementById("back-button").addEventListener("click", function() {
        window.location.href = "../articles";
    });

    document.getElementById("edit-button").addEventListener("click", function() {
        window.location.href = "../article-edit/" + window.location.pathname.split("/").pop();
    });

});
