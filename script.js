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

document.addEventListener("DOMContentLoaded", () => {
  const postData = JSON.parse(localStorage.getItem("latestPost"));
  const postFeed = document.getElementById("postFeed");

  if (!postData) {
    postFeed.innerHTML = "<p>No post found.</p>";
    return;
  }

  const postDiv = document.createElement("div");
  postDiv.className = "post";

  if (postData.text) {
    const text = document.createElement("p");
    text.textContent = postData.text;
    postDiv.appendChild(text);
  }

  if (postData.image) {
    const img = document.createElement("img");
    img.src = postData.image;
    postDiv.appendChild(img);
  }

  postFeed.appendChild(postDiv);
});
