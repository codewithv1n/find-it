function createPost() {
  const text = document.getElementById("postText").value.trim();
  const fileInput = document.getElementById("imageUpload");
  const postFeed = document.getElementById("postFeed");
  const file = fileInput.files[0];

  // Don't post if text and image are both empty
  if (!text && !file) {
    alert("Please write something or add a photo.");
    return;
  }

  const postDiv = document.createElement("div");
  postDiv.classList.add("post");

  if (text) {
    const postText = document.createElement("p");
    postText.textContent = text;
    postDiv.appendChild(postText);
  }

  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      const img = document.createElement("img");
      img.src = e.target.result;
      postDiv.appendChild(img);
      postFeed.prepend(postDiv);
    };
    reader.readAsDataURL(file);
  } else {
    postFeed.prepend(postDiv);
  }

  // Clear input
  document.getElementById("postText").value = "";
  fileInput.value = "";
}
