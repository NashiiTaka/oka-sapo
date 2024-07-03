function initFavorites(selector) {
    const favorites = document.querySelectorAll(selector);

    for (const favorite of favorites) {
        favorite.addEventListener('click', () => {
            const isTurnedOff = !favorite.src.endsWith('off.png');
            favorite.src = '/img/favorite-' + (isTurnedOff ? 'off' : 'on') + '.png';

            isTurnedOff ?
                removeFavorite(favorite.dataset.productId) :
                addFavorite(favorite.dataset.productId)
            ;
        });

        if (existsFavorite(favorite.dataset.productId)) {
            favorite.src = '/img/favorite-on.png';
        }
    }
}

function getArrayFromLocalStorage(key) {
    const jsonString = localStorage.getItem(key);
    return jsonString ? JSON.parse(jsonString) : [];
}

function addFavorite(productId) {
    const favorites = getArrayFromLocalStorage('favorites');
    favorites.push(productId);
    localStorage.setItem('favorites', JSON.stringify(favorites));
}

function removeFavorite(productId) {
    const favorites = getArrayFromLocalStorage('favorites');
    const index = favorites.indexOf(productId);
    if (index !== -1) {
        favorites.splice(index, 1);
        localStorage.setItem('favorites', JSON.stringify(favorites));
    }
}

function existsFavorite(productId) {
    const favorites = getArrayFromLocalStorage('favorites');
    return favorites.includes(productId);
}