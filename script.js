function sendMessage() {
  const inputBox = document.getElementById("user-input");
  const imageInput = document.getElementById("image-input");
  const message = inputBox.value.trim();
  const chatBox = document.getElementById("chat-box");

  if (message !== "") {
    const userMessage = document.createElement("div");
    userMessage.className = "message user-message";
    userMessage.textContent = message;
    chatBox.appendChild(userMessage);
  }

  // Check if user selected an image
  if (imageInput.files.length > 0) {
    const file = imageInput.files[0];
    const reader = new FileReader();

    reader.onload = function (e) {
      const img = document.createElement("img");
      img.src = e.target.result;
      img.style.maxWidth = "150px";
      img.style.display = "block";
      img.style.marginTop = "5px";

      const imageMessage = document.createElement("div");
      imageMessage.className = "message user-message";
      imageMessage.appendChild(img);
      chatBox.appendChild(imageMessage);

      chatBox.scrollTop = chatBox.scrollHeight;
    };

    reader.readAsDataURL(file);
  }

  inputBox.value = "";
  imageInput.value = "";
  chatBox.scrollTop = chatBox.scrollHeight;
}
