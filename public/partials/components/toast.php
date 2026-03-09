<div x-data="toastManager()" class="fixed bottom-4 right-4 z-50 flex flex-col gap-2">
  <template x-for="toast in toasts" :key="toast.id">
    <div class="toast flex items-center gap-3 px-4 py-3 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg min-w-[260px]">
      <ion-icon :name="toast.type==='success' ? 'checkmark-circle' : (toast.type==='error' ? 'close-circle' : 'information-circle')"></ion-icon>
      <p class="text-sm flex-1" x-text="toast.message"></p>
      <button @click="remove(toast.id)"><ion-icon name="close"></ion-icon></button>
    </div>
  </template>
</div>
