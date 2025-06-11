document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu');
    const navMenu = document.querySelector('nav ul');
    
    mobileMenuBtn.addEventListener('click', function() {
        navMenu.classList.toggle('show');
    });
    
    // Load products from JSON
    loadProducts();
    
    async function loadProducts() {
        try {
            const response = await fetch('products.json');
            const products = await response.json();
            displayProducts(products);
        } catch (error) {
            console.error('Error loading products:', error);
        }
    }
    
    function displayProducts(products) {
        const productContainer = document.getElementById('productContainer');
        productContainer.innerHTML = '';
        
        products.forEach(product => {
            const productCard = document.createElement('div');
            productCard.className = 'product-card';
            
            const availabilityClass = product.available ? 'available' : 'unavailable';
            const availabilityText = product.available ? 'In Stock' : 'Out of Stock';
            
            productCard.innerHTML = `
                <div class="product-image" style="background-image: url('${product.image || 'https://via.placeholder.com/300x200?text=Construction+Material'}')"></div>
                <div class="product-info">
                    <h3>${product.name}</h3>
                    <p class="price">₹${product.price}/ton</p>
                    <p>Min. Quantity: ${product.minQuantity} tons</p>
                    <p>Max. Quantity: ${product.maxQuantity} tons</p>
                    <span class="availability ${availabilityClass}">${availabilityText}</span>
                    <a href="tel:+919828544454" class="order-btn">Order Now</a>
                </div>
            `;
            
            productContainer.appendChild(productCard);
        });
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 70,
                    behavior: 'smooth'
                });
                
                // Close mobile menu if open
                navMenu.classList.remove('show');
            }
        });
    });
});