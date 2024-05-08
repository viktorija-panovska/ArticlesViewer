window.addEventListener("load", () => {

    setupPageinationButtons();
    setupArticleCreationDialog();
    setupDeleteButtons();

});


function setupPageinationButtons() {

    const nextButton = document.getElementById("next-button");

    if (nextButton)
        nextButton.addEventListener("click", function() { switchPage(1); });

    const previousButton = document.getElementById("previous-button");

    if (previousButton)
        previousButton.addEventListener("click", function() { switchPage(-1); });

}


function switchPage(direction) {

    let pageId = window.location.pathname.split("/").pop();

    if (!isNaN(pageId)) {
        pageId = parseInt(pageId) + direction;
        window.location.href = "../articles/" + pageId;
    }

}


function setupArticleCreationDialog() {

    const createArticleDialog = document.getElementById("article-create-dialog");
    const createArticleForm = document.forms["article-create-form"];


    document.getElementById("create-button").addEventListener("click", function() {
        createArticleDialog.showModal();
    });


    createArticleForm.addEventListener("submit", async function(event) {
        event.preventDefault();

        const formData = new FormData(createArticleForm);
        const response = await fetch("../", {
            method: "POST",
            body: formData
        });

        const id = await response.text();

        createArticleForm.reset();
        createArticleDialog.close();

        window.location.href = "../article-edit/" + id;

    });


    document.getElementById("back-button").addEventListener("click", function() {
        createArticleForm.reset();
        createArticleDialog.close();
    });

}


function setupDeleteButtons() {

    const deleteButtons = document.getElementsByClassName("article-delete");

    for (const deleteButton of deleteButtons) {
        deleteButton.addEventListener("click", async function() {
            const articleId = parseInt(deleteButton["id"].split("-")[1]);

            const url = new URL(window.location.href);
            url.searchParams.set("deleteId", articleId);
            const response = await fetch(url.toString(), {
               method: "DELETE",
            });

            const articleListHtml = await response.text();

            if (articleListHtml.length == 0) {
                const pageId = parseInt(window.location.pathname.split("/").pop()) - 1;
                window.location.href = "../articles/" + pageId;
            }
            else
                document.getElementById("article-list").innerHTML = articleListHtml;
        });
    }

}