<style>
.avatar-card {
  background: #fff;
  border-radius: 20px;
  border: 1px solid #f0e4d8;
  box-shadow: 0 2px 14px rgba(89,34,2,0.07);
  padding: 24px;
  transition: box-shadow 0.3s, transform 0.3s;
  animation: fadeUp 0.5s ease 0.15s forwards;
  opacity: 0;
}
.avatar-card:hover {
  box-shadow: 0 12px 32px rgba(89,34,2,0.12);
  transform: translateY(-3px);
}

.card-title {
  font-family: 'Playfair Display', serif;
  color: #A65005;
  font-weight: 700;
  border-bottom: 2px solid #F2D4C2;
  padding-bottom: 12px;
  margin-bottom: 20px;
}

.av-preview-wrap {
  display: flex;
  justify-content: center;
  margin-bottom: 18px;
}

.av-circle {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  overflow: hidden;
  background: #F2D4C2;
  border: 3px solid #D99C79;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2.2rem;
  font-weight: 700;
  color: #A65005;
  position: relative;
}

.av-circle img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.upload-zone {
  border: 2px dashed #D99C79;
  border-radius: 14px;
  padding: 18px 12px;
  text-align: center;
  background: #fdf8f4;
  cursor: pointer;
  position: relative;
}

.upload-zone.dragover {
  background: #F2D4C2;
  border-color: #A65005;
}

.upload-zone input {
  position: absolute;
  inset: 0;
  opacity: 0;
  cursor: pointer;
}

.btn-av-upload {
  margin-top: 14px;
  width: 100%;
  background: linear-gradient(135deg, #A65005, #592202);
  color: white;
  border: none;
  border-radius: 12px;
  padding: 12px;
  cursor: pointer;
}

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(14px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>


<div class="avatar-card">

  <div class="card-title">Profile Photo</div>

  <!-- PREVIEW -->
  <div class="av-preview-wrap">
    <div class="av-circle">
      <span id="avInitial">{{ strtoupper(substr($user->name,0,1)) }}</span>

      <img
        id="avImg"
        src="{{ $user->avatar ? asset('storage/'.$user->avatar) : '' }}"
        style="{{ $user->avatar ? '' : 'display:none' }}"
      >
    </div>
  </div>

  <!-- FORM -->
  <form
    method="POST"
    action="{{ route('shop.profile.avatar.update') }}"
    enctype="multipart/form-data"
    id="avatarForm"
  >
    @csrf
    @method('PATCH')

    <div class="upload-zone" id="uploadZone">
      <input
        type="file"
        name="avatar"
        id="avatarInput"
        accept="image/*"
      >
      Klik atau drag foto ke sini
    </div>

    <p id="fileName" style="text-align:center;margin-top:8px;font-size:12px"></p>

    <button type="submit" class="btn-av-upload shadow-sm
                    hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
      Upload Photo
    </button>
  </form>
</div>


<script>
(function () {

  const input     = document.getElementById('avatarInput');
  const zone      = document.getElementById('uploadZone');
  const img       = document.getElementById('avImg');
  const initial   = document.getElementById('avInitial');
  const fileName  = document.getElementById('fileName');
  const form      = document.getElementById('avatarForm');


  /* =========================
     PREVIEW FUNCTION
  ========================= */
  function preview(file) {
    if (!file) return;

    fileName.textContent = file.name;

    const reader = new FileReader();
    reader.onload = e => {
      img.src = e.target.result;
      img.style.display = 'block';
      initial.style.display = 'none';
    };
    reader.readAsDataURL(file);
  }


  /* =========================
     NORMAL PICK
  ========================= */
  input.addEventListener('change', () => {
    preview(input.files[0]);
  });


  /* =========================
     DRAG EVENTS
  ========================= */
  zone.addEventListener('dragover', e => {
    e.preventDefault();
    zone.classList.add('dragover');
  });

  zone.addEventListener('dragleave', () => {
    zone.classList.remove('dragover');
  });


  /* =========================
     🔥 FIX UTAMA DI SINI
     set input.files !
  ========================= */
  zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.classList.remove('dragover');

    const files = e.dataTransfer.files;

    if (files.length) {
      input.files = files;   // ← INI YANG SEBELUMNYA HILANG
      preview(files[0]);
    }
  });


  /* =========================
     OPTIONAL UX GUARD
  ========================= */
  form.addEventListener('submit', function(e) {
    if (!input.files.length) {
      e.preventDefault();
      alert('Pilih foto dulu');
    }
  });

})();
</script>