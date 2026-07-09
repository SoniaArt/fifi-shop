let initialized = false;

export function initFavouriteButtons(){
    
    if(initialized) return;

    initialized = true;

    document.addEventListener('click', async function(e){

        const btn = e.target.closest('.favourite-btn');

        if(!btn) return;

        const productId = btn.dataset.id;
        const inFavouriteWindow = btn.closest('#favouritesList');

        if(inFavouriteWindow){
            e.preventDefault();
            e.stopPropagation();

            const response = await fetch('/FIFI/api/favourites.php',{
                method:'POST',
                headers:{ 'Content-Type':'application/json' },
                body: JSON.stringify({
                    action: 'remove',
                    product_id: productId
                })
            });
            
            const data = await response.json();

            if(data.success){
                const card = btn.closest('.side-product-card');
                if(card) card.remove();

                try {
                    const { loadFavouriteIds } = await import('./favourites.js');
                    await loadFavouriteIds();
                    document.dispatchEvent(new CustomEvent('favouritesUpdated'));
                } catch(err) {
                    console.error('Failed to refresh favourite ids:', err);
                }   

                const favouritesList = document.getElementById('favouritesList');
                if(favouritesList && favouritesList.querySelectorAll('.side-product-card').length === 0){
                    favouritesList.innerHTML = `
                        <p class="text-center text-secondary">
                            Избранное пусто
                        </p>
                    `;
                }
            }
            return;
        }

        e.preventDefault();
        e.stopPropagation();

        const wasActive = btn.classList.contains('active');

        if(wasActive){
            btn.classList.remove('active');
        }else{
            btn.classList.add('active');
        }

        const response = await fetch('/FIFI/api/favourites.php',{
            method:'POST',
            headers:{
                'Content-Type':'application/json'
            },
            body:JSON.stringify({
                action: wasActive ? 'remove' : 'add',
                product_id: productId

            })
        });

        const data = await response.json();

        if(!data.success){
            if(data.auth === false){
                btn.classList.remove('active');
                alert('Сначала войдите в аккаунт');
                return;
            }

            if(wasActive){
                btn.classList.add('active');
            }else{
                btn.classList.remove('active');
            }

        } else {
            try {
                const { loadFavouriteIds } = await import('./favourites.js');
                await loadFavouriteIds();

                const favouritesList = document.getElementById('favouritesList');
                const isFavouritesOpen = favouritesList && favouritesList.closest('.favourites-panel')?.classList.contains('active');
                
                if (isFavouritesOpen || favouritesList) {
                    const { initFavourites } = await import('./favourites.js');
                    await initFavourites();
                }
                document.dispatchEvent(new CustomEvent('favouritesUpdated'));
            } catch(err) {
                console.error('Failed to refresh favourite ids:', err);
            }
        }
    });
}