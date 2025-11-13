// notif
function showNotification(message, type) {
  const notif = document.getElementById('notif');
  const msg = document.getElementById('notif-message');
  const icon = notif.querySelector('.icon');

  msg.textContent = message;

  if (type === 'success') {
    notif.className = 'notification success show';
    icon.innerHTML = '<span style="font-size:16px;">&#10003;</span>'; 
  } else {
    notif.className = 'notification error show';
    icon.innerHTML = '<span style="font-size:16px;">&#10005;</span>'; 
  }

  // Auto hide after 3 seconds
  setTimeout(() => {
    notif.classList.remove('show');
  }, 3000);
}

document.addEventListener("DOMContentLoaded", () => {
  const links = document.querySelectorAll(".sidebar ul li a");
  const sections = document.querySelectorAll("section");

  // Default: show home
  let activeSection = localStorage.getItem("activeSection") || "home";
  showSection(activeSection);

  links.forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      const target = link.getAttribute("href").replace("#", "");
      showSection(target);
      localStorage.setItem("activeSection", target);
    });
  });

  function showSection(id) {
    sections.forEach((s) => s.classList.remove("active"));
    document.getElementById(id)?.classList.add("active");

    links.forEach((l) => l.classList.remove("active"));
    document
      .querySelector(`a[href="#${id}"]`)
      ?.classList.add("active");
  }

  // Image Preview
  const imageUpload = document.getElementById("imageUpload");
  const imagePreview = document.getElementById("imagePreview");

  if (imageUpload) {
    imageUpload.addEventListener("change", function () {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = () => {
          imagePreview.innerHTML = `<img src="${reader.result}" alt="Preview">`;
        };
        reader.readAsDataURL(file);
      } else {
        imagePreview.innerHTML = `<p>No image selected</p>`;
      }
    });
  }
});
