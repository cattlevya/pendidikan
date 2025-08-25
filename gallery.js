document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('uploadForm');
  const fileInput = document.getElementById('photoInput');
  const captionInput = document.getElementById('captionInput');
  const feed = document.getElementById('feed');
  const chooseFolderBtn = document.getElementById('chooseFolder');

  // File System Access API handle
  let dirHandle = null;
  async function chooseFolder(){
    if (!window.showDirectoryPicker){
      alert('Browser tidak mendukung File System Access API. Coba Chrome/Edge terbaru.');
      return;
    }
    try{
      dirHandle = await window.showDirectoryPicker();
      // Simpan permission di session (opsional)
      alert('Folder terpilih. Foto baru akan disalin ke folder tersebut.');
    }catch(e){ /* user canceled */ }
  }
  if (chooseFolderBtn) chooseFolderBtn.addEventListener('click', chooseFolder);

  // Load saved posts from localStorage
  const KEY = 'pixelgram_posts_v1';
  function loadPosts(){
    try{
      const raw = localStorage.getItem(KEY);
      if(!raw) return [];
      return JSON.parse(raw);
    }catch(e){ return []; }
  }
  function savePosts(arr){
    localStorage.setItem(KEY, JSON.stringify(arr));
  }

  function renderPost({dataURL, caption, ts}){
    const article = document.createElement('article');
    article.className = 'px-post';
    article.innerHTML = `
      <figure class="px-card"><img src="${dataURL}" alt="post"></figure>
      <figcaption class="px-caption"></figcaption>
      <div class="px-actions"><button class="px-del">Delete</button></div>
    `;
    article.querySelector('.px-caption').textContent = caption || '';
    article.querySelector('.px-del').addEventListener('click', () => {
      const posts = loadPosts();
      const idx = posts.findIndex(p => p.ts === ts && p.dataURL === dataURL);
      if (idx !== -1) { posts.splice(idx,1); savePosts(posts); }
      article.remove();
    });
    feed.prepend(article);
  }

  // initial render
  const initial = loadPosts();
  initial.forEach(renderPost);

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const file = fileInput.files[0];
    if(!file) return;
    const caption = captionInput.value.trim();

    // Read file as DataURL
    const dataURL = await new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.onload = () => resolve(reader.result);
      reader.onerror = reject;
      reader.readAsDataURL(file);
    });

    // Save and render
    const posts = loadPosts();
    const post = { dataURL, caption, ts: Date.now() };
    posts.push(post); savePosts(posts);
    renderPost(post);

    // reset
    form.reset();

    // If folder chosen, also write file to disk (best-effort)
    if (dirHandle){
      try{
        const ext = (file.type && file.type.includes('png')) ? 'png' : 'jpg';
        const name = `pixelgram_${post.ts}.${ext}`;
        const fileHandle = await dirHandle.getFileHandle(name, { create:true });
        const writable = await fileHandle.createWritable();
        await writable.write(file);
        await writable.close();
      }catch(e){ console.warn('Write to disk failed:', e); }
    }
  });
});

