// Add a new keyword or link
function addNewItem(addButton) {
    addButton.addEventListener("click", (event) => {
        const itemsList = event.target.closest('ul');
        const itemsListChildren = itemsList.children;
        const lastItem = itemsListChildren[itemsListChildren.length - 2];
        const lastItemValue = lastItem.querySelector("input").value;
        if (noSpaceNoEmpty(lastItemValue)) {
            const newItem = lastItem.cloneNode(true);
            let newRemoveIcon = newItem.querySelector("span");
            newRemoveIcon.classList.replace("d-none", "d-block");
            let newItemInput = newItem.querySelector("input");
            newItemInput.id = newInputId(lastItem);
            newItemInput.name = newInputName(lastItem);
            newItemInput.value = "";
            lastItem.after(newItem);
        }
    })
}

function noSpaceNoEmpty(value) {
    const removeSpace = value.replace(/\s/g, '');
    return removeSpace.length;
}

function newInputId(lastKeyword) {
    const lastInputId = lastKeyword.querySelector("input").id;
    const lastIdNumber = lastInputId.match(/\d+$/)[0];
    const idBase = lastInputId.substring(0, lastInputId.length - lastIdNumber.length);
    const newIdNumber = parseInt(lastIdNumber) + 1;

    return `${idBase}${newIdNumber}`;
}

function newInputName(lastKeyword) {
    const lasInputName = lastKeyword.querySelector("input").name;
    const lastInputBracket = lasInputName.match(/\[(\d+)\]$/)[0];
    const nameBase = lasInputName.substring(0, lasInputName.length - lastInputBracket.length);
    const lastNameNumber = lasInputName.match(/\[(\d+)\]$/)[1];
    const newInputNumber = parseInt(lastNameNumber) + 1;

    return `${nameBase}[${newInputNumber}]`;
}

//Add a keyword
const keywordAddButton = document.getElementById("keyword-add-button");
addNewItem(keywordAddButton);
//Add a link
const linkAddButton = document.getElementById("link-add-button");
addNewItem(linkAddButton);


//Remove a keyword or link
function removeItem(itemsList, itemType) {
    const removeIcons = itemsList.getElementsByClassName("organisation-remove-item");
    for (let i = 0; i < removeIcons.length; i++) {
        removeIcons[i].addEventListener("click", (event) => {
            if (itemsList.children.length > 2) {
                const item = event.target.closest("li");
                item.remove();
            }
            // make it impossible to remove all the keywords or links
            if (itemsList.children.length === 2) {
                let newItem = document.createElement('input');
                newItem.classList.add("my-3", "position-relative");
                itemsList.prepend(newItem);
                let newItemInput = document.createElement('input');
                newItemInput.type = "text";
                newItemInput.id = `organisation_${itemType}_0`;
                newItemInput.name = `organisation[${itemType}][0]`;
                newItemInput.value = "";
                newItemInput.classList.add('form-control');
                newItem.append(newItemInput);
                let newRemoveContainer = document.createElement('span');
                newRemoveContainer.classList.add("organisation-remove-link");
                newRemoveContainer.append(newItem);
                let NewRemoveIcon = document.createElement('i');
                NewRemoveIcon.classList.add("fa-solid", "fa-xmark");
                newRemoveContainer.append(NewRemoveIcon);
            }
        })
    }
}

// improve the first keyword and first link input style
let keywordsList = document.getElementById('organisation-keywords-list');
let firstRemoveIcon = keywordsList.children[0].querySelector("span");
firstRemoveIcon.classList.add("d-none");

let linksList = document.getElementById('organisation-links-list');
let firstLinkRemoveIcon = linksList.children[0].querySelector("span");
firstLinkRemoveIcon.classList.add("d-none");

//Remove a keyword
keywordAddButton.addEventListener("click", () => {
    let keywordsList = document.getElementById('organisation-keywords-list');
    removeItem(keywordsList, "keywords");
})
//Remove a link
linkAddButton.addEventListener("click", () => {
    let linksList = document.getElementById('organisation-links-list');
    removeItem(linksList, "links");
})

// Change Download Link style
const downloadLinks = document.querySelectorAll('a[download]');
downloadLinks.forEach(link => {
    link.classList.add("form-download-link");
})
