function smoothScroll(target) {
    document.getElementById(target).scrollIntoView({
        behavior: 'smooth'
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.querySelector('#searchForm');
    const recommendedSection = document.querySelector('#recommended-section');

    // Smooth scrolling
    function smoothScroll(target) {
        document.getElementById(target).scrollIntoView({
            behavior: 'smooth'
        });
    }

    // Form submission
    if (searchForm) {
        searchForm.addEventListener('submit', function (event) {
            event.preventDefault();
            // You can add additional logic here if needed
            smoothScroll('recommended-section');
        });
    }

    // Interactive card hover effect
    const cards = document.querySelectorAll('.card');
    if (cards) {
        cards.forEach(card => {
            card.addEventListener('mouseenter', function () {
                this.classList.add('hovered');
            });

            card.addEventListener('mouseleave', function () {
                this.classList.remove('hovered');
            });
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const locationInput = document.querySelector('input[placeholder="Search Places"]');
    const locationsList = document.querySelector('.locations');

    // Sample locations data
    const locations = ['Kathmandu', 'Chitwan', 'Pokhara'];

    locationInput.addEventListener('input', function (event) {
        const inputValue = event.target.value.toLowerCase();
        
        // Clear previous suggestions
        locationsList.innerHTML = '';

        // Display suggestions based on user input
        locations.forEach(location => {
            if (location.toLowerCase().includes(inputValue)) {
                const suggestion = document.createElement('span');
                suggestion.classList.add('location');
                suggestion.textContent = `Now in ${location}`;
                locationsList.appendChild(suggestion);
            }
        });
    });
});


