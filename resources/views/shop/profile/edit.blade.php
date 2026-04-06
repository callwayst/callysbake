@extends('layouts.app')

@section('content')
<style>
:root {
  --amber:   #A65005;
  --dark:    #592202;
  --caramel: #D99C79;
  --cream:   #F2D4C2;
  --noir:    #260101;
  --crimson: #800000;
}

.profile-wrap {
  max-width: 1100px; margin: 0 auto;
  padding: 28px 24px 40px;
}

/* HERO */
.hero-banner {
  background: linear-gradient(135deg, var(--dark) 0%, var(--amber) 55%, var(--caramel) 100%);
  border-radius: 22px; padding: 30px 28px;
  position: relative; overflow: hidden;
  margin-bottom: 26px;
  animation: fadeUp 0.45s ease forwards;
}
.hero-banner::before {
  content: '';
  position: absolute; inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E");
}
.hero-banner::after {
  content: '🍰';
  position: absolute; right: 24px; bottom: -10px;
  font-size: 88px; opacity: 0.09; transform: rotate(-12deg); pointer-events: none;
}
.hero-inner {
  position: relative; z-index: 1;
  display: flex; flex-wrap: wrap; align-items: center; gap: 18px;
}
.hero-avatar-ring {
  width: 88px; height: 88px; flex-shrink: 0;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--caramel), var(--amber));
  padding: 3px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}
.hero-avatar-inner {
  width: 100%; height: 100%; border-radius: 50%;
  overflow: hidden; background: var(--cream);
  display: flex; align-items: center; justify-content: center;
  font-family: 'Playfair Display', serif;
  font-size: 2rem; font-weight: 700; color: var(--amber);
}
.hero-avatar-inner img {
  width: 100%; height: 100%; object-fit: cover;
  display: {{ $user->avatar ? 'block' : 'none' }};
}
.hero-avatar-initial { display: {{ $user->avatar ? 'none' : 'block' }}; }

.hero-info h1 {
  font-family: 'Playfair Display', serif;
  color: #fff; font-size: clamp(1.3rem,3vw,1.85rem);
  text-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.hero-info .meta { color: var(--cream); font-size: 0.84rem; margin-top: 4px; opacity: 0.9; }
.hero-info .joined { color: var(--caramel); font-size: 0.74rem; margin-top: 3px; opacity: 0.85; }

.hero-stats { display: flex; gap: 12px; margin-left: auto; }
.stat-pill {
  background: rgba(255,255,255,0.14); backdrop-filter: blur(8px);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 14px; padding: 13px 20px; text-align: center; min-width: 70px;
}
.stat-pill .num {
  font-family: 'Playfair Display', serif;
  font-size: 1.5rem; font-weight: 700; color: #fff; line-height: 1;
}
.stat-pill .lbl {
  font-size: 0.67rem; text-transform: uppercase;
  letter-spacing: 0.08em; color: var(--cream); margin-top: 4px;
}

/* GRID */
.profile-grid {
  display: grid; grid-template-columns: 1fr 310px; gap: 22px;
}
@media (max-width: 820px) {
  .profile-grid { grid-template-columns: 1fr; }
  .col-right { order: -1; }
}

/* CARD */
.card {
  background: #fff; border-radius: 20px;
  border: 1px solid #f0e4d8;
  box-shadow: 0 2px 14px rgba(89,34,2,0.07);
  padding: 24px;
  transition: box-shadow 0.3s, transform 0.3s;
  animation: fadeUp 0.5s ease forwards; opacity: 0;
}
.card:hover { box-shadow: 0 12px 32px rgba(89,34,2,0.12); transform: translateY(-3px); }
.card-title {
  font-family: 'Playfair Display', serif;
  color: var(--amber); font-size: 1.05rem; font-weight: 700;
  display: flex; align-items: center; gap: 8px;
  border-bottom: 2px solid var(--cream);
  padding-bottom: 12px; margin-bottom: 20px;
}

/* FORM */
.form-grid { display: grid; gap: 14px; }
.form-grid.cols-2 { grid-template-columns: 1fr 1fr; }
@media (max-width: 540px) { .form-grid.cols-2 { grid-template-columns: 1fr; } }
.span-2 { grid-column: 1 / -1; }
.field { display: flex; flex-direction: column; gap: 5px; }
.field label {
  font-size: 0.69rem; font-weight: 600;
  text-transform: uppercase; letter-spacing: 0.07em; color: var(--caramel);
}
.field input, .field textarea {
  border: 1.5px solid #f0e4d8; border-radius: 12px;
  padding: 11px 15px; font-family: 'DM Sans', sans-serif;
  font-size: 0.88rem; color: var(--noir); background: #fdfaf8;
  transition: border-color 0.2s, box-shadow 0.2s; outline: none; width: 100%;
}
.field input:focus, .field textarea:focus {
  border-color: var(--caramel);
  box-shadow: 0 0 0 3px rgba(217,156,121,0.18); background: #fff;
}
.field textarea { resize: vertical; min-height: 88px; }
.form-footer { display: flex; justify-content: flex-end; }

/* BUTTONS */
.btn {
  border: none; border-radius: 12px; font-family: 'DM Sans', sans-serif;
  font-size: 0.88rem; font-weight: 600; cursor: pointer;
  padding: 12px 24px; display: inline-flex; align-items: center;
  gap: 7px; transition: opacity 0.2s, transform 0.15s;
}
.btn:hover { opacity: 0.88; transform: translateY(-1px); }
.btn-primary { background: linear-gradient(135deg, var(--amber), var(--dark)); color: #fff; }
.btn-danger  { background: linear-gradient(135deg, var(--crimson), #4a0000); color: #fff; width: 100%; justify-content: center; }

/* DANGER ZONE */
.danger-zone {
  background: #fff7f7; border: 1.5px solid #ffd0d0;
  border-radius: 20px; padding: 22px;
  animation: fadeUp 0.5s ease 0.35s forwards; opacity: 0;
}
.danger-title {
  font-family: 'Playfair Display', serif;
  color: var(--crimson); font-size: 1rem;
  display: flex; align-items: center; gap: 7px; margin-bottom: 8px;
}
.danger-desc { font-size: 0.78rem; color: #b06060; line-height: 1.55; margin-bottom: 16px; }

/* STAGGER DELAYS */
.col-left .card:nth-child(1) { animation-delay: 0.1s; }
.col-left .card:nth-child(2) { animation-delay: 0.2s; }
.col-right .card:nth-child(1) { animation-delay: 0.15s; }

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(14px); }
  to   { opacity: 1; transform: translateY(0); }
}

@media (max-width: 640px) {
  .hero-stats { width: 100%; margin-left: 0; }
  .stat-pill { flex: 1; padding: 10px 8px; }
  .profile-wrap { padding: 18px 16px 40px; }
}
</style>

<div class="profile-wrap">

  {{-- HERO --}}
  <div class="hero-banner">
    <div class="hero-inner">
      <div class="hero-avatar-ring">
        <div class="hero-avatar-inner">
          <span class="hero-avatar-initial">{{ strtoupper(substr($user->name,0,1)) }}</span>
          <img id="heroImg" src="{{ $user->avatar ? asset('storage/'.$user->avatar) : '' }}" alt="avatar">
        </div>
      </div>
      <div class="hero-info">
        <h1>{{ $user->name }}</h1>
        <p class="meta"><i class='bx bx-envelope'></i> {{ $user->email }}</p>
        @if($user->phone)
          <p class="meta"><i class='bx bx-phone'></i> {{ $user->phone }}</p>
        @endif
        <p class="joined"><i class='bx bx-calendar-alt'></i> Member since {{ $user->created_at->format('d M Y') }}</p>
      </div>
      <div class="hero-stats">
        <div class="stat-pill">
          <div class="num">{{ $user->orders()->count() }}</div>
          <div class="lbl">Orders</div>
        </div>
        <div class="stat-pill">
          <div class="num">{{ $user->vouchers()->count() }}</div>
          <div class="lbl">Vouchers</div>
        </div>
      </div>
    </div>
  </div>

  {{-- GRID --}}
  <div class="profile-grid">

    {{-- LEFT --}}
    <div class="col-left" style="display:flex;flex-direction:column;gap:22px;">

      {{-- PROFILE INFO --}}
      <div class="card">
        <div class="card-title"><i class='bx bx-id-card'></i> Profile Info</div>
        <form method="POST" action="{{ route('shop.profile.info.update') }}">
          @csrf @method('PATCH')
          <div class="form-grid cols-2">
            <div class="field">
              <label>Full Name</label>
              <input name="name" value="{{ old('name', $user->name) }}" placeholder="Full name">
              @error('name')<span style="color:var(--crimson);font-size:0.75rem;">{{ $message }}</span>@enderror
            </div>
            <div class="field">
              <label>Email Address</label>
              <input name="email" type="email" value="{{ old('email', $user->email) }}" placeholder="email@example.com">
              @error('email')<span style="color:var(--crimson);font-size:0.75rem;">{{ $message }}</span>@enderror
            </div>
            <div class="field">
              <label>Phone Number</label>
              <input name="phone" type="tel" value="{{ old('phone', $user->phone) }}" placeholder="+62 xxx xxxx xxxx">
              @error('phone')<span style="color:var(--crimson);font-size:0.75rem;">{{ $message }}</span>@enderror
            </div>
            <div class="field span-2">
              <label>Delivery Address</label>
              <textarea name="address" placeholder="Alamat lengkap...">{{ old('address', $user->address) }}</textarea>
              @error('address')<span style="color:var(--crimson);font-size:0.75rem;">{{ $message }}</span>@enderror
            </div>
            <div class="span-2 form-footer">
              <button type="submit" class="btn btn-primary shadow-sm
                    hover:shadow-xl hover:-translate-y-1 transition-all duration-300"><i class='bx bx-save'></i> Save Changes</button>
            </div>
          </div>
        </form>
      </div>

      {{-- CHANGE PASSWORD --}}
      <div class="card">
        <div class="card-title"><i class='bx bx-lock-alt'></i> Change Password</div>
        <form method="POST" action="{{ route('shop.profile.password.update') }}">
          @csrf @method('PATCH')
          <div class="form-grid">
            <div class="field">
              <label>Current Password</label>
              <input type="password" name="current_password" placeholder="••••••••">
              @error('current_password')<span style="color:var(--crimson);font-size:0.75rem;">{{ $message }}</span>@enderror
            </div>
            <div class="field">
              <label>New Password</label>
              <input type="password" name="password" placeholder="Minimum 8 characters">
              @error('password')<span style="color:var(--crimson);font-size:0.75rem;">{{ $message }}</span>@enderror
            </div>
            <div class="field">
              <label>Confirm New Password</label>
              <input type="password" name="password_confirmation" placeholder="Repeat new password">
            </div>
            <div class="form-footer">
              <button type="submit" class="btn btn-primary shadow-sm
                    hover:shadow-xl hover:-translate-y-1 transition-all duration-300"><i class='bx bx-key'></i> Update Password</button>
            </div>
          </div>
        </form>
      </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-right" style="display:flex;flex-direction:column;gap:22px;">

      {{-- AVATAR — include partial --}}
      @include('shop.profile.avatar')

      {{-- DANGER ZONE --}}
      <div class="danger-zone">
        <div class="danger-title"><i class='bx bx-error-circle'></i> Danger Zone</div>
        <p class="danger-desc">Menghapus akun bersifat permanen dan tidak dapat dibatalkan. Seluruh data kamu akan dihapus.</p>
        <form method="POST" action="{{ route('shop.profile.delete') }}"
              onsubmit="return confirm('Yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan.');">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-danger shadow-sm
                    hover:shadow-xl hover:-translate-y-1 transition-all duration-300"><i class='bx bx-trash'></i> Delete My Account</button>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
// Sync avatar preview from avatar partial ke hero
document.addEventListener('avatarPreviewUpdated', function(e) {
  const heroImg = document.getElementById('heroImg');
  const heroInitial = document.querySelector('.hero-avatar-initial');
  if (heroImg) { heroImg.src = e.detail.src; heroImg.style.display = 'block'; }
  if (heroInitial) heroInitial.style.display = 'none';
});
</script>

@endsection