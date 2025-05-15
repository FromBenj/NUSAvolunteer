// Star buttons
function revealStar(Id1, Id2) {
    const btn1 = document.getElementById(Id1);
    const btn2 = document.getElementById(Id2);
    if (btn1.classList.contains("d-none")) {
        btn1.classList.remove("d-none");
        btn2.classList.add("d-none");
    } else {
        btn2.classList.remove("d-none");
        btn1.classList.add("d-none");
    }
}

//AJAX Call to create or delete matching
function matchingActions(volunteerId, organisationId, action, url) {
    const data = {
        'volunteerId'     : volunteerId,
        'organisationId': organisationId,
        'action'               : action,
    };
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(responseData => {
        console.log(responseData);
    })
    .catch(error => {
        console.error("Error when fetching the data:", error);
    });
}

function matchingManagement(volunteerId, organisationId, targetId, url) {
    let cardContainer = document.getElementById("card-container-" + targetId);
    cardContainer.removeAttribute('data-bs-toggle');
    cardContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains("match-star")) {
            revealStar("match-button-empty-" + targetId, "match-button-filled-" + targetId);
            if (e.target.id === "match-button-empty-" + targetId) {
                matchingActions(volunteerId, organisationId, targetId, "create", url);
            } else if (e.target.id === "match-button-filled-" + targetId) {
                matchingActions(volunteerId, organisationId, targetId, "delete", url);
            }
        } else {
            cardContainer.setAttribute('data-bs-toggle', 'modal');
            let cardBody = document.getElementById("card-body-" + targetId);
            cardBody.click();
        }
        cardContainer.removeAttribute('data-bs-toggle');
    })
}
