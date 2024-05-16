document.querySelectorAll('.seat:not(.booked)').forEach(seat => {
    seat.addEventListener('click', () => {
        seat.classList.toggle('selected');
    });
});
