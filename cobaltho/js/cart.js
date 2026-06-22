function openCart(){
  const cart = document.getElementById("cartSidebar");
  const overlay = document.getElementById("cartOverlay");

  if(!cart || !overlay){
    console.log("Cart no encontrado");
    return;
  }

  cart.classList.add("is-open");
  overlay.classList.add("is-visible");
  document.body.classList.add("cart-open");
}

function closeCart(){
  const cart = document.getElementById("cartSidebar");
  const overlay = document.getElementById("cartOverlay");

  if(!cart || !overlay){
    return;
  }

  cart.classList.remove("is-open");
  overlay.classList.remove("is-visible");
  document.body.classList.remove("cart-open");
}

document.addEventListener("keydown", function(event){
  if(event.key === "Escape"){
    closeCart();
  }
});

function formatCurrency(value){
  return "$" + Number(value || 0).toLocaleString("es-CO");
}

function showEmptyCart(){
  const body = document.querySelector(".cart-body");

  if(!body){
    return;
  }

  body.innerHTML = '<p class="cart-empty">Tu carrito esta vacio</p>';
}

document.addEventListener("click", function(event){
  const removeButton = event.target.closest("[data-remove-cart-item]");

  if(!removeButton){
    return;
  }

  if(event.defaultPrevented){
    return;
  }

  event.preventDefault();

  const item = removeButton.closest("[data-cart-item]");
  const url = removeButton.href + "&ajax=1";

  removeButton.classList.add("is-loading");

  fetch(url)
    .then(function(response){
      if(!response.ok){
        throw new Error("No se pudo eliminar el producto");
      }

      return response.json();
    })
    .then(function(data){
      const cartCounter = document.getElementById("cart-count");
      const totalValue = document.querySelector("[data-cart-total-value]");

      if(cartCounter){
        cartCounter.textContent = data.count;
      }

      if(totalValue){
        totalValue.textContent = formatCurrency(data.total);
      }

      if(item){
        item.classList.add("is-removing");
        item.addEventListener("transitionend", function(){
          item.remove();

          if(data.empty){
            showEmptyCart();
          }
        }, { once: true });
      } else if(data.empty){
        showEmptyCart();
      }
    })
    .catch(function(){
      removeButton.classList.remove("is-loading");
      window.location.href = removeButton.href;
    });
});
