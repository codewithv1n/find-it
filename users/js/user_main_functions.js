// simpleng logout function na gawa ko hehe :>
async function logout() {
    try {
        const response = await fetch('../controllers/logout_process.php'); 
        if (response.ok) {
            window.location.href = 'login.php';
        }
    } catch (error) {
        console.error("Connection error:", error);
    }
}