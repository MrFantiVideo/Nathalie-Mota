// Bouton pour menu de navigation mobile (Overlay)
document.getElementById("header-nav-btn").addEventListener("click", function()
{
    this.classList.toggle("active");
    toggleOverlay();
});
window.addEventListener('resize', function()
{
    const btn = document.getElementById("header-nav-btn");
    if (window.innerWidth > 782 && btn.classList.contains("active"))
    {
        btn.classList.remove("active");
        toggleOverlay();
    }
});
function toggleOverlay()
{
    const overlay = document.getElementById("header-nav-overlay");
    const isActive = overlay.style.display === "flex";
    overlay.style.display = isActive ? "none" : "flex";
    overlay.style.opacity = isActive ? 0 : 1;
}

// Bouton pour les menus déroulants
function toggleDropdown(button)
{
    var dropdownContent = button.nextElementSibling;
    var arrowContainer = button.querySelector('i');

    dropdownContent.classList.toggle("show");
    button.classList.toggle("no-radius");
    arrowContainer.classList.toggle('rotate');
}
document.querySelectorAll('.dropdown-btn').forEach(function(button)
{
    button.addEventListener('click', function(event)
    {
        toggleDropdown(event.currentTarget);
    });
});
document.querySelectorAll('i').forEach(function(arrow)
{
    arrow.addEventListener('click', function(event)
    {
        toggleDropdown(event);
        event.stopPropagation();
    });
});

document.querySelectorAll('.dropdown').forEach(function(dropdown)
{
    dropdown.addEventListener('mouseleave', function(event)
    {
        var dropdownContent = dropdown.querySelector('.dropdown-container');
        if (dropdownContent.classList.contains('show'))
        {
            dropdownContent.classList.remove('show');
            dropdown.querySelector('.dropdown-btn').classList.remove('no-radius');
            dropdown.querySelector('i').classList.remove('rotate');
        }
    });
});

// Affichage du menu Contact
document.addEventListener('DOMContentLoaded', function()
{
    var contactButtons = document.querySelectorAll('.contact-form-btn');
    contactButtons.forEach(function(button)
    {
        button.addEventListener('click', toggleContactForm);
    });
});
function toggleContactForm()
{
    var contactContent = document.getElementById('content-contact');
    var photoReferenceElement = document.getElementById('photoReference');
    if (photoReferenceElement)
    {
        var photoReference = photoReferenceElement.getAttribute('data-reference');
        var inputField = document.querySelector('input[name="photo-reference"]');
        if (inputField)
        {
            inputField.value = photoReference;
        }
    }
    if (contactContent.style.display === 'none')
    {
        contactContent.style.display = 'block';
    }
    else
    {
        contactContent.style.display = 'none';
    }
}
document.getElementById('content-background').addEventListener('click', function(event)
{
    if (event.target.id === 'content-background')
    {
        var contactContent = document.getElementById('content-contact');
        contactContent.style.display = 'none';
    }
});