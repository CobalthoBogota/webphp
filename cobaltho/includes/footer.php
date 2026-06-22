<?php require_once __DIR__ . "/app.php"; ?>

<footer>
  <p>COBALTHO &copy; 2026</p>
</footer>

<script src="<?php echo app_url('js/cart.js?v=3'); ?>"></script>

<script>
function updateCartCount() {
  fetch('<?php echo app_url('cart_count.php'); ?>')
    .then(response => response.text())
    .then(count => {
      const cartCounter = document.getElementById('cart-count');
      if (cartCounter) {
        cartCounter.textContent = count;
      }
    })
    .catch(error => console.log(error));
}

updateCartCount();
</script>

</body>
</html>
