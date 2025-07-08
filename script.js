document.addEventListener("DOMContentLoaded", () => {
  const button = document.getElementById("postBtn");

  if (button) {
    button.addEventListener("click", () => {
      const text = document.getElementById("postText").value.trim();
      const fileInput = document.getElementById("imageUpload");
      const file = fileInput.files[0];

      if (!text && !file) {
        alert("Please enter text or upload an image.");
        return;
      }

      const post = { text };

      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          post.image = e.target.result;
          localStorage.setItem("latestPost", JSON.stringify(post));
          window.location.href = "Posted.html";
        };
        reader.readAsDataURL(file);
      } else {
        localStorage.setItem("latestPost", JSON.stringify(post));
        window.location.href = "Posted.html";
      }
    });
  }
});
