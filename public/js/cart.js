function addToCart(buttonElement, articleId, doReload) {

    buttonElement.disabled = true;
    var buttonText = buttonElement.innerText;
    buttonElement.innerText = 'Un instant...';

    ajax.post('/add', {
        articleId: articleId,
        rand: Math.random()
    }, function () {
        buttonElement.disabled = false;
        buttonElement.innerText = buttonText;
        if (doReload) {
            document.location.reload();
        }
    })
}

function removeToCart(buttonElement, articleId) {

    buttonElement.disabled = true;
    buttonElement.innerText = 'Un instant...';
    document.location.href = '/remove?articleId='+articleId;
}