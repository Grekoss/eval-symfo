var like = {
    init: function()
    {
        console.log('Hello ici, test AJAX');
        document.querySelectorAll('a.js-like').forEach(function(link) {
            link.addEventListener('click', like.onClickBtnLike);
        })
    },
    onClickBtnLike: function(e)
    {
        e.preventDefault();

        const url = this.href;
        const spanCount = this.querySelector('span.js-likes');
        const icone = this.querySelector('i');
        console.log(icone);

        axios.get(url).then(function(response) {
            spanCount.textContent = response.data.likes;

            if (icone.classList.contains('fas')) {
                icone.classList.replace('fas', 'far');
            } else {
                icone.classList.replace('far', 'fas');
            }
        }).catch(function(error) {
            if (error.response.status === 403) {
                window.alert("Vous ne pouvez pas liker une question si vous n'êtes pas connecté! ");
            } else {
                window.alert("Une erreur s'est produite, réessayez plus tard");
            }
        });
    },

};

document.addEventListener('DOMContentLoaded', like.init);