window.addEventListener("load", () => {

    document.getElementById("back-button").addEventListener("click", function() {
        window.location.href = "../articles";
    });

    setupFormSubmission();
    setupReferralLinkGeneration();
});


function setupFormSubmission() {

    const form = document.forms["article-form"];

    form.addEventListener("submit", async function(event) {
        event.preventDefault();

        const formData = new FormData(form);

        const response = await fetch("../", {
            method: "POST",
            body: formData
        });

        window.location.href = "../articles";
    });

}

function setupReferralLinkGeneration() {

    const utmInput = document.getElementById("utm-input");
    utmInput.value = "";
    const referralLink = document.getElementById("referral-link");

    const utmPattern = /[a-z0-9]{1,64}/;
    utmInput.addEventListener("input", function() {

        if (utmInput.value.length == 0 || !utmInput.value.match(utmPattern)) {
            referralLink.style.visibility = "hidden";
        }
        else {
            const referralUrl = new URL(window.location.href);
            referralUrl.searchParams.set("utm_source", utmInput.value);
            referralLink.setAttribute("href", referralUrl.toString());
            referralLink.style.visibility = 'visible';
        }
    });

}