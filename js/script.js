document.addEventListener("DOMContentLoaded", function() {
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.querySelector('.login-container');

    if (signUpButton && signInButton && container) {
        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });
    }
});

let selectedSeats = [];

function fetchSeats(showtime_id) {
    console.log("Selected Showtime ID: " + showtime_id);
    if (!showtime_id) {
        document.getElementById("seats-container").innerHTML = "";
        document.getElementById("confirmBookingButton").style.display = "none";
        return;
    }

    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log("Response received: " + this.responseText);
            document.getElementById("seats-container").innerHTML = this.responseText;
            document.getElementById("confirmBookingButton").style.display = "block";
            addSeatEventListeners();  // Add event listeners to the newly added seats
        } else if (this.readyState == 4 && this.status != 200) {
            console.error('Failed to fetch seats:', this.statusText);
        }
    };
    xhttp.open("GET", "fetch_seats.php?showtime_id=" + showtime_id, true);
    xhttp.send();
}

function addSeatEventListeners() {
    const seatElements = document.querySelectorAll('.seat');
    seatElements.forEach(seat => {
        seat.addEventListener('click', function() {
            selectSeat(seat, seat.dataset.ticketId);
        });
    });
}

function selectSeat(seatElement, ticketId) {
    if (seatElement.classList.contains('booked')) {
        return; // Do nothing if the seat is booked
    }

    seatElement.classList.toggle('selected');

    if (seatElement.classList.contains('selected')) {
        selectedSeats.push(ticketId);
    } else {
        selectedSeats = selectedSeats.filter(id => id !== ticketId);
    }

    console.log("Selected seats:", selectedSeats);
}

function confirmBooking() {
    if (selectedSeats.length === 0) {
        alert("Please select at least one seat to confirm your booking.");
        return;
    }

    // Here you can send the selectedSeats array to the server for processing
    console.log("Confirming booking for seats:", selectedSeats);
    
    // Example of sending the data to a PHP script for processing
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const response = JSON.parse(this.responseText);
            if (response.status === 'success') {
                displayBookingDetails(response.seat_numbers, response.total_price);
            } else {
                alert(response.message); // Handle errors accordingly
            }
        } else if (this.readyState == 4 && this.status != 200) {
            console.error('Failed to confirm booking:', this.statusText);
        }
    };
    xhttp.open("POST", "confirm_booking.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("selectedSeats=" + JSON.stringify(selectedSeats));
}

function displayBookingDetails(seatNumbers, totalPrice) {
    const bookTicketsContent = document.getElementById("book-tickets-content");
    bookTicketsContent.innerHTML = `
        <div class="booking-confirmation">
            <h2>Booking Confirmed!</h2>
            <p>Seats: ${seatNumbers.join(", ")}</p>
            <p>Total Price: ${totalPrice} L.E </p>
            <button onclick="window.location.href='index.php'">Return to Homepage</button>
        </div>
    `;
}

