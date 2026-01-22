
let idleTime = 0;
const maxIdleTime = 15;

const timerIncrement = () => {
    idleTime++; //1min
    console.log(`Idle time: ${idleTime} minute(s)`);
    if (idleTime >= maxIdleTime) {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
        }).then(() => {
            window.location.href = "/login?timeout=1";
        });
    }
};

const resetTimer = () => {
    idleTime = 0;
};

const activityEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart'];

activityEvents.forEach(event => {
    window.addEventListener(event, resetTimer, false);
});

setInterval(timerIncrement, 60000); // 1 minutshi ne milisekonda