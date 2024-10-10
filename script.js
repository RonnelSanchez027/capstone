const toggleSidebar = document.getElementById('toggleSidebar');
const sidebar = document.getElementById('sidebar');
const content = document.getElementById('content');
const themeToggle = document.getElementById('themeToggle');
const userIcon = document.getElementById('userIcon');
const floatingTab = document.getElementById('floatingTab');
let isDarkTheme = localStorage.getItem('dark-theme') === 'true'; // Check stored preference

// Apply the theme on page load
if (isDarkTheme) {
    document.body.classList.add('dark-theme');
    themeToggle.classList.remove('fa-sun');
    themeToggle.classList.add('fa-moon');
}

toggleSidebar.addEventListener('click', () => {
    sidebar.classList.toggle('open');
    content.classList.toggle('expanded');
});

document.addEventListener('click', (event) => {
    if (!sidebar.contains(event.target) && !toggleSidebar.contains(event.target)) {
        sidebar.classList.remove('open');
        content.classList.remove('expanded');
    }
});

themeToggle.addEventListener('click', () => {
    isDarkTheme = !isDarkTheme;
    localStorage.setItem('dark-theme', isDarkTheme); // Save preference

    if (isDarkTheme) {
        document.body.classList.add('dark-theme');
        themeToggle.classList.remove('fa-sun');
        themeToggle.classList.add('fa-moon');
    } else {
        document.body.classList.remove('dark-theme');
        themeToggle.classList.remove('fa-moon');
        themeToggle.classList.add('fa-sun');
    }
});

userIcon.addEventListener('click', () => {
    floatingTab.style.display = floatingTab.style.display === 'none' ? 'block' : 'none';
});

// Close the floating tab if clicking outside of it
document.addEventListener('click', (event) => {
    if (!floatingTab.contains(event.target) && !userIcon.contains(event.target)) {
        floatingTab.style.display = 'none';
    }
});

function logout() {
    alert("You have logged out!");
    // Redirect to login page (example)
    window.location.href = 'login.html';
}

function toggleSubmenu(menuId) {
    const submenu = document.getElementById(menuId);
    const menuItem = submenu.parentElement;

    submenu.classList.toggle('open');
    menuItem.classList.toggle('open');

    // Disable hover effect on the parent menu item when submenu is open
    if (submenu.classList.contains('open')) {
        menuItem.classList.add('submenu-open');
    } else {
        menuItem.classList.remove('submenu-open');
    }
}

// Hover effect management
document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('mouseenter', () => {
        if (!item.classList.contains('open')) {
            item.style.backgroundColor = 'var(--dark-secondary)';
        }
    });

    item.addEventListener('mouseleave', () => {
        if (!item.classList.contains('open')) {
            item.style.backgroundColor = 'transparent';
        }
    });
});

// Disable hover background when submenu is open
document.querySelectorAll('.submenu').forEach(submenu => {
    submenu.addEventListener('mouseenter', () => {
        const menuItem = submenu.parentElement;
        menuItem.style.backgroundColor = 'transparent'; // Disable background when hovering over submenu
    });

    submenu.addEventListener('mouseleave', () => {
        const menuItem = submenu.parentElement;
        if (!submenu.classList.contains('open')) {
            menuItem.style.backgroundColor = 'var(--dark-secondary)'; // Restore background if submenu is closed
        }
    });
});
