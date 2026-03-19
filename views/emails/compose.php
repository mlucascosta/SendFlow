<?php $currentPage='compose'; ?>
<section class="grid gap-6 xl:grid-cols-[1.3fr_0.7fr]">
  <form class="surface-card space-y-5 p-6">
    <div>
      <span class="metric-badge bg-brand-primary/10 text-brand-primary dark:bg-brand-primaryDark/10 dark:text-brand-primaryDark">
        <ion-icon name="create-outline"></ion-icon>
        Composer renovado
      </span>
      <h1 class="mt-4 text-3xl font-semibold tracking-tight">Escreva com clareza, foco visual e pré-visualização contextual.</h1>
    </div>
    <div class="grid gap-4 md:grid-cols-2">
      <label class="block md:col-span-2">
        <span class="mb-2 block text-sm font-medium">Para</span>
        <input class="input-field" placeholder="cliente@empresa.com">
      </label>
      <label class="block md:col-span-2">
        <span class="mb-2 block text-sm font-medium">Assunto</span>
        <input class="input-field" placeholder="Atualização do projeto SendFlow">
      </label>
      <label class="block">
        <span class="mb-2 block text-sm font-medium">Prioridade</span>
        <select class="input-field"><option>Normal</option><option>Alta</option><option>Baixa</option></select>
      </label>
      <label class="block">
        <span class="mb-2 block text-sm font-medium">Resumo IA</span>
        <select class="input-field"><option>Gerar ao enviar</option><option>Desabilitado</option></select>
      </label>
    </div>
    <label class="block">
      <span class="mb-2 block text-sm font-medium">Mensagem</span>
      <textarea class="input-field min-h-72" placeholder="Escreva sua mensagem..."></textarea>
    </label>
    <div class="flex flex-wrap gap-3">
      <button class="btn-primary"><ion-icon name="paper-plane-outline"></ion-icon>Enviar</button>
      <button class="btn-secondary" type="button">Salvar rascunho</button>
      <button class="btn-ghost-carbon" type="button">Pré-visualizar HTML</button>
    </div>
  </form>

  <aside class="grid gap-6">
    <section class="surface-card p-6">
      <h2 class="text-2xl font-semibold">Prévia operacional</h2>
      <p class="mt-2 text-sm leading-6 text-carbon-textSubtle dark:text-carbon-textSubtleDark">Veja antes do envio como o conteúdo será processado por regras, auditoria e IA.</p>
      <div class="mt-5 rounded-3xl border border-dashed border-carbon-border p-5 dark:border-carbon-borderDark">
        <p class="text-sm font-semibold">Destinatário: cliente@empresa.com</p>
        <p class="mt-2 text-sm text-carbon-textSubtle dark:text-carbon-textSubtleDark">Resumo automático habilitado. Classificação sugerida: atualização de projeto.</p>
      </div>
    </section>
    <section class="surface-card p-6">
      <h2 class="text-2xl font-semibold">Checklist de envio</h2>
      <div class="mt-5 space-y-3 text-sm">
        <div class="rounded-2xl bg-carbon-surface p-4 dark:bg-carbon-surfaceDark">Assunto com contexto suficiente.</div>
        <div class="rounded-2xl bg-carbon-surface p-4 dark:bg-carbon-surfaceDark">Remetente autorizado na política atual.</div>
        <div class="rounded-2xl bg-carbon-surface p-4 dark:bg-carbon-surfaceDark">Integrações prontas para auditoria e métricas.</div>
      </div>
    </section>
  </aside>
</section>
