    // DISPLAY TODAY'S DATE
    function updateTopDate() {
		const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById("top-date").textContent = now.toLocaleDateString('en-US', options);
    }
    updateTopDate();

    // DISPLAY LIVE CLOCK
    function updateCurrentTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById("current-time").textContent = `${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateCurrentTime, 1000);
    updateCurrentTime();