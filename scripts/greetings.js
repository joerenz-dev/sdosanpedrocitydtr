
    function getGreeting() {
        const now = new Date();
        const hour = now.getHours();
        let greeting = "GOOD DAY, WELCOME TO SCHOOLS DIVISION OFFICE OF SAN PEDRO CITY!!!";

        if (hour < 12) {
            greeting = "GOOD MORNING, WELCOME TO SCHOOLS DIVISION OFFICE OF SAN PEDRO CITY!!!";
        } else if (hour < 18) {
            greeting = "GOOD AFTERNOON, WELCOME TO SCHOOLS DIVISION OFFICE OF SAN PEDRO CITY!!!";
        } else {
            greeting = "GOOD EVENING, WELCOME TO SCHOOLS DIVISION OFFICE OF SAN PEDRO CITY!!!";
        }

        document.getElementById("greeting").textContent = greeting;
    }

    // Run when the page loads
    window.onload = getGreeting;