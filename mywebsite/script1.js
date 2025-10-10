// Dropdown toggles
const notifIcon = document.getElementById('notif-icon');
const notifDropdown = document.getElementById('notif-dropdown');
const settingsIcon = document.getElementById('settings-icon');
const settingsDropdown = document.getElementById('settings-dropdown');

notifIcon.addEventListener('click', () => {
    notifDropdown.style.display =
        notifDropdown.style.display === 'block' ? 'none' : 'block';
    settingsDropdown.style.display = 'none';
});

settingsIcon.addEventListener('click', () => {
    settingsDropdown.style.display =
        settingsDropdown.style.display === 'block' ? 'none' : 'block';
    notifDropdown.style.display = 'none';
});

// Edit profile modal
const editModal = document.getElementById('edit-profile-modal');
const editBtn = document.getElementById('edit-profile-btn');
const saveBtn = document.getElementById('save-profile');
const cancelBtn = document.getElementById('cancel-edit');

editBtn.addEventListener('click', () => {
    editModal.style.display = 'flex';
    document.getElementById('edit-username').value =
        document.getElementById('username-display').textContent;
    document.getElementById('edit-description').value =
        document.getElementById('description-display').textContent;
});

cancelBtn.addEventListener('click', () => {
    editModal.style.display = 'none';
});

saveBtn.addEventListener('click', () => {
    document.getElementById('username-display').textContent =
        document.getElementById('edit-username').value;
    document.getElementById('description-display').textContent =
        document.getElementById('edit-description').value;
    editModal.style.display = 'none';
});

// Close dropdowns if clicked outside
window.addEventListener('click', (e) => {
    if (!notifIcon.contains(e.target) && !notifDropdown.contains(e.target))
        notifDropdown.style.display = 'none';
    if (!settingsIcon.contains(e.target) && !settingsDropdown.contains(e.target))
        settingsDropdown.style.display = 'none';
});
