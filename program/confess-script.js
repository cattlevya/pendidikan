document.addEventListener('DOMContentLoaded', function() {
    const yesBtn = document.getElementById('yesBtn');
    const noBtn = document.getElementById('noBtn');
    const resultModal = document.getElementById('resultModal');
    const closeModal = document.getElementById('closeModal');
    const floatingHearts = document.getElementById('floatingHearts');

    // Create floating hearts animation
    function createFloatingHeart() {
        const heart = document.createElement('div');
        heart.className = 'floating-heart';
        heart.innerHTML = '❤️';
        heart.style.left = Math.random() * 100 + '%';
        heart.style.animationDuration = (Math.random() * 3 + 3) + 's';
        heart.style.animationDelay = Math.random() * 2 + 's';
        
        floatingHearts.appendChild(heart);
        
        // Remove heart after animation
        setTimeout(() => {
            if (heart.parentNode) {
                heart.parentNode.removeChild(heart);
            }
        }, 6000);
    }

    // Start floating hearts animation
    setInterval(createFloatingHeart, 500);

    // Make NO button move when hovered
    noBtn.addEventListener('mouseenter', function() {
        moveNoButton();
    });

    noBtn.addEventListener('click', function(e) {
        e.preventDefault();
        moveNoButton();
    });

    function moveNoButton() {
        const container = document.querySelector('.buttons-container');
        const containerRect = container.getBoundingClientRect();
        const buttonRect = noBtn.getBoundingClientRect();
        
        // Calculate new position
        let newX, newY;
        let attempts = 0;
        const maxAttempts = 10;
        
        do {
            newX = Math.random() * (containerRect.width - buttonRect.width);
            newY = Math.random() * (containerRect.height - buttonRect.height);
            attempts++;
        } while (
            attempts < maxAttempts && 
            Math.abs(newX - (buttonRect.left - containerRect.left)) < 50 &&
            Math.abs(newY - (buttonRect.top - containerRect.top)) < 50
        );
        
        // Apply new position with smooth transition
        noBtn.style.transition = 'all 0.3s ease';
        noBtn.style.position = 'absolute';
        noBtn.style.left = newX + 'px';
        noBtn.style.top = newY + 'px';
        
        // Reset transition after animation
        setTimeout(() => {
            noBtn.style.transition = '';
        }, 300);
    }

    // Handle YES button click
    yesBtn.addEventListener('click', function() {
        // Create celebration effect
        createCelebrationEffect();
        
        // Show result modal after a short delay
        setTimeout(() => {
            resultModal.style.display = 'block';
        }, 1000);
    });

    // Handle modal close -> go to pixel gallery
    closeModal.addEventListener('click', function() {
        window.location.href = 'gallery.html';
    });

    // Close modal if clicked outside
    resultModal.addEventListener('click', function(e) {
        if (e.target === resultModal) {
            resultModal.style.display = 'none';
        }
    });

    // Create celebration effect
    function createCelebrationEffect() {
        // Create multiple hearts explosion
        for (let i = 0; i < 20; i++) {
            setTimeout(() => {
                const heart = document.createElement('div');
                heart.innerHTML = '💖';
                heart.style.position = 'fixed';
                heart.style.fontSize = '30px';
                heart.style.left = '50%';
                heart.style.top = '50%';
                heart.style.transform = 'translate(-50%, -50%)';
                heart.style.pointerEvents = 'none';
                heart.style.zIndex = '999';
                
                document.body.appendChild(heart);
                
                // Animate heart explosion
                const angle = (i / 20) * 2 * Math.PI;
                const distance = 200 + Math.random() * 100;
                const finalX = Math.cos(angle) * distance;
                const finalY = Math.sin(angle) * distance;
                
                heart.animate([
                    { 
                        transform: 'translate(-50%, -50%) scale(0)',
                        opacity: 0
                    },
                    { 
                        transform: 'translate(-50%, -50%) scale(1)',
                        opacity: 1
                    },
                    { 
                        transform: `translate(calc(-50% + ${finalX}px), calc(-50% + ${finalY}px)) scale(0)`,
                        opacity: 0
                    }
                ], {
                    duration: 2000,
                    easing: 'ease-out'
                });
                
                // Remove heart after animation
                setTimeout(() => {
                    if (heart.parentNode) {
                        heart.parentNode.removeChild(heart);
                    }
                }, 2000);
            }, i * 50);
        }
    }

    // Add some interactive effects
    yesBtn.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.1)';
    });
    
    yesBtn.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
    });

    // Add keyboard support
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            resultModal.style.display = 'none';
        }
    });

    // Add touch support for mobile
    noBtn.addEventListener('touchstart', function(e) {
        e.preventDefault();
        moveNoButton();
    });

    // Prevent context menu on right click for NO button
    noBtn.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        moveNoButton();
    });
}); 