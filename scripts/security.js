
    <!-- ==========================
         SECURITY: DISABLE INSPECT
         ========================== -->
        document.addEventListener("contextmenu", e => e.preventDefault());
        document.addEventListener("keydown", e => {
            if (
                e.ctrlKey && ["u", "U", "s", "S"].includes(e.key) ||
                e.key === "F12" ||
                (e.ctrlKey && e.shiftKey && e.key === "I")
            ) {
                e.preventDefault();
            }
        });