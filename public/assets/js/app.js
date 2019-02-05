var app = {
    init: function()
    {
        document.querySelectorAll('a.js-like').forEach(function(link) {
            link.addEventListener('click', app.onClickBtnLike);
        });

        document.querySelectorAll('a.js-delete-reponse').forEach(function (link) {
            link.addEventListener('click', app.onClickBtnDeleteReponse);
        });
    },

    // => Action for like/unlike. Use "axio", switch the icon when the user click the link
    onClickBtnLike: function(e)
    {
        e.preventDefault();

        const url = this.href;
        const spanCount = this.querySelector('span.js-likes');
        const icone = this.querySelector('i');
        console.log(spanCount);

        axios
            .get(url).then(function(response) {
            spanCount.textContent = response.data.likes;

            if (icone.classList.contains('fas')) {
                icone.classList.replace('fas', 'far');
            } else {
                icone.classList.replace('far', 'fas');
            }
        })
            .catch(function(error) {
            if (error.response.status === 403) {
                window.alert("Vous ne pouvez pas liker une question si vous n'êtes pas connecté! ");
            } else {
                window.alert("Une erreure s'est produite, réessayez plus tard");
            }
        });
    },

    // => Action for Remove a Reponse. Use "axio", refresh the total of response for the question.
    onClickBtnDeleteReponse: function(e)
    {
        e.preventDefault();

        const userId = $(this).data('id');
        const token = $(this).data('token');
        const url = Routing.generate('reponse_delete', {'id': userId, 'token': token});
        const block = $(this).parent().parent().parent();
        const spanCountReponse = document.querySelector('.js-span-reponses');

        if (confirm('Voulez-vous supprimer votre réponse ?'))
        {
            axios({
                method: 'delete',
                url: url,
            })
                .then(function(response) {
                    spanCountReponse.textContent = response.data.nbReponse;
                    block.hide(1000);
                })
                .catch(function(error) {
                    if (error.response.status === 403) {
                        window.alert('Vous n\'avez pas les droits pour la suppression de la réponse!');
                    } else {
                        window.alert('Une erreure s\'est produite, réessayez plus tard!');
                    }
                });
        }
    },
};

document.addEventListener('DOMContentLoaded', app.init);