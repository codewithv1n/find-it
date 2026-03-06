(() => {
  "use strict";

  // ══════════════════════════════════════════════════
  //  NAVIGATION
  // ══════════════════════════════════════════════════
  const links    = document.querySelectorAll(".nav-link");
  const sections = document.querySelectorAll("section");
  const topbarTitle = document.getElementById("topbarTitle");

  const sectionTitles = {
    home:    "Home",
    search:  "Search",
    posted:  "Recent Posts",
    posting: "Create a Post",
    about:   "About",
  };

  function navigate(id) {
    sections.forEach((s) => s.classList.remove("active"));
    links.forEach((l) => l.classList.remove("active"));

    const target = document.getElementById(id);
    if (target) target.classList.add("active");

    const activeLink = document.querySelector(`.nav-link[href="#${id}"]`);
    if (activeLink) activeLink.classList.add("active");

    if (topbarTitle) topbarTitle.textContent = sectionTitles[id] || "";

    localStorage.setItem("findit_section", id);
  }

  links.forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      navigate(link.getAttribute("href").slice(1));
    });
  });

  // Restore last visited section
  const savedSection = localStorage.getItem("findit_section") || "home";
  navigate(savedSection);


  // ══════════════════════════════════════════════════
  //  TOAST NOTIFICATIONS
  // ══════════════════════════════════════════════════
  function showToast(message, type = "success") {
    // Remove existing toast if any
    const existing = document.getElementById("findit-toast");
    if (existing) existing.remove();

    const toast = document.createElement("div");
    toast.id = "findit-toast";

    const isSuccess = type === "success";
    const icon = isSuccess ? "fa-circle-check" : "fa-circle-xmark";
    const color = isSuccess ? "#166534" : "#9a2c0d";
    const bg    = isSuccess ? "#edfaf3"  : "#fff1ee";
    const border= isSuccess ? "#a7f3d0"  : "#f5c4b4";

    Object.assign(toast.style, {
      position:     "fixed",
      bottom:       "28px",
      right:        "28px",
      zIndex:       "9999",
      display:      "flex",
      alignItems:   "center",
      gap:          "10px",
      background:   bg,
      color:        color,
      border:       `1px solid ${border}`,
      borderRadius: "10px",
      padding:      "13px 18px",
      fontSize:     "0.875rem",
      fontWeight:   "500",
      fontFamily:   '"DM Sans", sans-serif',
      boxShadow:    "0 8px 24px rgba(0,0,0,0.10)",
      maxWidth:     "320px",
      opacity:      "0",
      transform:    "translateY(12px)",
      transition:   "opacity 0.25s ease, transform 0.25s ease",
    });

    toast.innerHTML = `<i class="fas ${icon}" style="font-size:1rem;flex-shrink:0;"></i><span>${escHtml(message)}</span>`;
    document.body.appendChild(toast);

    // Animate in
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        toast.style.opacity   = "1";
        toast.style.transform = "translateY(0)";
      });
    });

    // Animate out & remove
    setTimeout(() => {
      toast.style.opacity   = "0";
      toast.style.transform = "translateY(12px)";
      setTimeout(() => toast.remove(), 300);
    }, 3200);
  }

  // Handle PHP-injected notification (from signup.php / login.php)
  if (typeof notif === "string" && notif.includes(":")) {
    const [type, ...rest] = notif.split(":");
    showToast(rest.join(":"), type);
  }


  // ══════════════════════════════════════════════════
  //  UTILITIES
  // ══════════════════════════════════════════════════
  function escHtml(str) {
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;");
  }

  function formatDate(ts) {
    return new Date(ts).toLocaleDateString("en-US", {
      month: "short",
      day:   "numeric",
      year:  "numeric",
    });
  }


  // ══════════════════════════════════════════════════
  //  POSTS STORE  (localStorage)
  // ══════════════════════════════════════════════════
  function getPosts() {
    try {
      return JSON.parse(localStorage.getItem("findit_posts") || "[]");
    } catch {
      return [];
    }
  }

  function savePosts(posts) {
    localStorage.setItem("findit_posts", JSON.stringify(posts));
    updateStats(posts);
  }

  function updateStats(posts) {
    const total = posts.length;
    const found = posts.filter((p) => p.category === "found").length;
    const lost  = posts.filter((p) => p.category === "lost").length;

    const el = (id) => document.getElementById(id);
    if (el("statPosts")) el("statPosts").textContent = total;
    if (el("statFound")) el("statFound").textContent = found;
    if (el("statLost"))  el("statLost").textContent  = lost;
  }

  // Init stats on load
  updateStats(getPosts());


  // ══════════════════════════════════════════════════
  //  POST FEED RENDERER
  // ══════════════════════════════════════════════════
  function renderFeed(posts) {
    const feed = document.getElementById("postFeed");
    if (!feed) return;

    if (!posts.length) {
      feed.innerHTML = `
        <div class="empty-state">
          <i class="fas fa-inbox"></i>
          <p>No posts yet. Be the first to create one!</p>
        </div>`;
      return;
    }

    feed.innerHTML = [...posts]
      .reverse()
      .map((p) => {
        const tagClass = p.category === "found" ? "tag-found" : "tag-lost";
        return `
          <div class="post-item">
            <div class="post-item-header">
              <span class="post-item-title">${escHtml(p.title)}</span>
              <div style="display:flex;align-items:center;gap:8px;">
                <span class="tag ${tagClass}">${escHtml(p.category)}</span>
                <span class="post-item-meta">${formatDate(p.id)}</span>
              </div>
            </div>
            <div class="post-item-body">${escHtml(p.body)}</div>
            ${p.image ? `<img src="${p.image}" alt="post image" />` : ""}
          </div>`;
      })
      .join("");
  }

  renderFeed(getPosts());


  // ══════════════════════════════════════════════════
  //  CATEGORY PILLS
  // ══════════════════════════════════════════════════
  document.querySelectorAll(".pill[data-cat]").forEach((pill) => {
    pill.addEventListener("click", () => {
      document.querySelectorAll(".pill[data-cat]").forEach((p) => p.classList.remove("selected"));
      pill.classList.add("selected");
      const hidden = document.getElementById("postCategory");
      if (hidden) hidden.value = pill.dataset.cat;
    });
  });


  // ══════════════════════════════════════════════════
  //  IMAGE UPLOAD  (click + drag & drop)
  // ══════════════════════════════════════════════════
  const uploadArea  = document.getElementById("uploadArea");
  const fileInput   = document.getElementById("imageUpload");
  const imagePreview = document.getElementById("imagePreview");

  if (uploadArea && fileInput) {
    // Click to browse
    uploadArea.addEventListener("click", () => fileInput.click());

    // Drag events
    uploadArea.addEventListener("dragover", (e) => {
      e.preventDefault();
      uploadArea.classList.add("dragover");
    });

    uploadArea.addEventListener("dragleave", () => {
      uploadArea.classList.remove("dragover");
    });

    uploadArea.addEventListener("drop", (e) => {
      e.preventDefault();
      uploadArea.classList.remove("dragover");
      const file = e.dataTransfer.files[0];
      if (file && file.type.startsWith("image/")) loadPreview(file);
    });

    // File input change
    fileInput.addEventListener("change", () => {
      if (fileInput.files[0]) loadPreview(fileInput.files[0]);
    });
  }

  function loadPreview(file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      if (imagePreview) {
        imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview" />`;
      }
    };
    reader.readAsDataURL(file);
  }


  // ══════════════════════════════════════════════════
  //  POST FORM — SUBMIT
  // ══════════════════════════════════════════════════
  const postForm = document.getElementById("postForm");

  if (postForm) {
    postForm.addEventListener("submit", (e) => {
      e.preventDefault();

      const title    = document.getElementById("postTitle")?.value.trim();
      const body     = document.getElementById("postText")?.value.trim();
      const category = document.getElementById("postCategory")?.value || "found";
      const imgEl    = imagePreview?.querySelector("img");

      if (!title || !body) {
        showToast("Please fill in all required fields.", "error");
        return;
      }

      const posts = getPosts();
      posts.push({
        id:       Date.now(),
        title,
        body,
        category,
        image:    imgEl ? imgEl.src : null,
      });

      savePosts(posts);
      renderFeed(posts);

      // Reset form
      postForm.reset();
      if (imagePreview) imagePreview.innerHTML = "";

      // Reset category pills
      document.querySelectorAll(".pill[data-cat]").forEach((p) => p.classList.remove("selected"));
      const defaultPill = document.querySelector('.pill[data-cat="found"]');
      if (defaultPill) defaultPill.classList.add("selected");
      const catInput = document.getElementById("postCategory");
      if (catInput) catInput.value = "found";

      showToast("Post published successfully!", "success");
      navigate("posted");
    });
  }


  // ══════════════════════════════════════════════════
  //  SEARCH
  // ══════════════════════════════════════════════════
  const searchInput   = document.getElementById("searchInput");
  const searchBtn     = document.getElementById("searchBtn");
  const searchResults = document.getElementById("searchResults");

  function runSearch() {
    if (!searchInput || !searchResults) return;

    const q = searchInput.value.trim().toLowerCase();
    if (!q) { searchResults.innerHTML = ""; return; }

    const hits = getPosts().filter(
      (p) =>
        p.title.toLowerCase().includes(q) ||
        p.body.toLowerCase().includes(q)
    );

    if (!hits.length) {
      searchResults.innerHTML = `
        <p style="color:var(--ink-soft);font-size:.85rem;margin-top:16px;">
          No results for "<strong>${escHtml(q)}</strong>".
        </p>`;
      return;
    }

    searchResults.innerHTML = hits
      .reverse()
      .map((p) => {
        const tagClass = p.category === "found" ? "tag-found" : "tag-lost";
        return `
          <div class="post-item" style="margin-top:14px;">
            <div class="post-item-header">
              <span class="post-item-title">${escHtml(p.title)}</span>
              <span class="tag ${tagClass}">${escHtml(p.category)}</span>
            </div>
            <div class="post-item-body">${escHtml(p.body)}</div>
          </div>`;
      })
      .join("");
  }

  if (searchBtn)   searchBtn.addEventListener("click", runSearch);
  if (searchInput) searchInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") runSearch();
  });

})();