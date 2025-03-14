const addressInput = document.getElementById('organisation_address');
const coordonnatesInput = document.getElementById('organisation_addressCoordonates');

if (addressInput) {
    addressInput.addEventListener('keyup', (e) => {
        const keyWords = e.target.value;
        keyWords.trim();
        if (keyWords.split(' ').length > 2) {
            document.getElementById('address-container') ?
                document.getElementById('address-container').remove() :
                null;
            const addressParent = document.getElementById('organisation_address').parentNode;
            const addressContainer = document.createElement('div');
            addressContainer.classList.add('d-flex', 'flex-column');
            addressContainer.id = 'address-container';
            addressParent.appendChild(addressContainer);
            const maxAddress = 4;
            fetch(`https://data.geopf.fr/geocodage/completion/?text=${keyWords}&maximumResponses=${maxAddress}`)
                .then(response => response.json())
                .then(data => {
                    const results = data.results;
                    if (results.length !== 0) {
                        results.forEach((address) => {
                            let addressChoice = document.createElement('div');
                            addressChoice.classList.add('border-bottom', 'border-2');
                            addressChoice.innerText = address.fulltext;
                            addressContainer.appendChild(addressChoice);
                            addressChoice.addEventListener('click', () => {
                                addressInput.value = address.fulltext;
                                coordonnatesInput.value = address.x + ',' + address.y;
                                addressContainer.remove();
                            })
                        })
                    }
                })
        }
    })
}
