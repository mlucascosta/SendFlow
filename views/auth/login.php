<div class="min-h-[70vh] flex items-center justify-center p-4">
  <div class="w-full max-w-md">
    <div class="text-center mb-6"><h1 class="text-2xl font-bold">SendFlow</h1><p class="text-neutral-500">Acesse sua conta para continuar</p></div>
    <div class="card">
      <form class="space-y-4" hx-post="/login" hx-target="#login-error" hx-swap="innerHTML">
        <input class="input-field" type="email" name="email" placeholder="seu@email.com" required>
        <input class="input-field" type="password" name="password" placeholder="••••••••" required>
        <div id="login-error" class="text-sm text-red-500"></div>
        <button type="submit" class="btn-primary w-full justify-center">Entrar <ion-icon name="arrow-forward-outline"></ion-icon></button>
      </form>
    </div>
  </div>
</div>
