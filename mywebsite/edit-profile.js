// Get elements
const saveBtn = document.getElementById('save-profile');
const cancelBtn = document.getElementById('cancel-edit');
const fileInput = document.getElementById('profile-pic');
const usernameInput = document.getElementById('username');
const descInput = document.getElementById('description');

// Save button click
saveBtn.addEventListener('click', () => {
    const username = usernameInput.value.trim();
    const description = descInput.value.trim();

    // If user selected an image, convert it to Base64 for saving
    const file = fileInput.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            localStorage.setItem('profilePic', e.target.result);
            saveAndRedirect(username, description);
        };
        reader.readAsDataURL(file);
    } else {
        saveAndRedirect(username, description);
    }
});

// Cancel button just goes back
cancelBtn.addEventListener('click', () => {
    window.location.href = "profile.php";
});

function saveAndRedirect(username, description) {
    localStorage.setItem('username', username);
    localStorage.setItem('description', description);
    window.location.href = "profile.php";
}
