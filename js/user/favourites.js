import {createProductCard} from '../components/product-card.js';
import {initFavouriteButtons} from './favourite-actions.js';

let favouriteIds = [];

export async function loadFavouriteIds(){

    const response = await fetch('/FIFI/api/favourites.php',{
        method:'POST',
        headers:{
            'Content-Type':'application/json'
        },
        body:JSON.stringify({
            action:'get'
        })
    });

    const data = await response.json();

    if(!data.success){
        favouriteIds = [];
        return [];
    }

    favouriteIds = data.items.map(item => Number(item.product_id));
    return favouriteIds;
}

export function isFavourite(productId){
    return favouriteIds.includes(Number(productId));
}

export async function checkFavourite(productId, button){
    if(!button) return;

    const response = await fetch('/FIFI/api/favourites.php',{
        method:'POST',
        headers:{
            'Content-Type':'application/json'
        },
        body:JSON.stringify({
            action:'exists',
            product_id:productId
        })
    });

    const data = await response.json();

    if(!data.success){
        button.classList.remove('active');
        return;
    }

    if(data.exists){
        button.classList.add('active');
    }else{
        button.classList.remove('active');
    }
}

export async function initFavourites(){
    const favouritesList = document.getElementById('favouritesList');

    if(!favouritesList) return;

    const response = await fetch('/FIFI/api/favourites.php',{
        method:'POST',
        headers:{
            'Content-Type':'application/json'
        },
        body:JSON.stringify({
            action:'get'
        })
    });

    const data = await response.json();

    if(!data.success){
        favouritesList.innerHTML = `
            <p class="text-center text-secondary">
                ${data.error}
            </p>
        `;
        return;
    }

    if(data.items.length === 0){
        favouritesList.innerHTML = `
            <p class="text-center text-secondary">
                Избранное пусто
            </p>
        `;
        return;
    }

    favouritesList.innerHTML = '';

    data.items.forEach(item=>{
        favouritesList.innerHTML += createProductCard(
            item,
            {
                favourite:true
            }
        );
    });
    initFavouriteButtons();
}