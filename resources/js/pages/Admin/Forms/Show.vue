<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Form } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';

interface Props {
    form: Form;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Administração',
        href: '/admin/forms',
    },
    {
        title: 'Formulários',
        href: '/admin/forms',
    },
    {
        title: `Candidatura - ${props.form.name}`,
        href: '#',
    },
];

const formatDate = (date: string) => {
    return new Intl.DateTimeFormat('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(new Date(date));
};

const deleteForm = () => {
    if (confirm('Tem certeza que deseja deletar esta candidatura? Esta ação não pode ser desfeita.')) {
        router.delete(`/admin/forms/${props.form.id}`, {
            onSuccess: () => {
                router.visit('/admin/forms');
            }
        });
    }
};
</script>

<template>
    <Head :title="`Admin - Candidatura de ${form.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
            <div class="mx-auto w-full max-w-6xl">
                <!-- Header -->
                <div class="mb-6 flex items-start justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-foreground">Candidatura de {{ form.name }}</h1>
                        <p class="mt-2 text-sm text-muted-foreground">
                            Enviado em {{ formatDate(form.created_at) }}
                        </p>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                                Visualização Administrativa
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a
                            v-if="form.cv_path"
                            :href="`/admin/forms/${form.id}/download-cv`"
                            class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-medium text-white hover:bg-green-700"
                            target="_blank"
                            download
                        >
                            <svg class="mr-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download CV
                        </a>
                        <button
                            @click="deleteForm"
                            class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700"
                        >
                            <svg class="mr-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Deletar
                        </button>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <!-- Informações da Candidatura -->
                    <div class="rounded-xl border border-sidebar-border/70 bg-background p-6 dark:border-sidebar-border">
                        <h2 class="mb-4 text-lg font-medium text-foreground">Informações da Candidatura</h2>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-muted-foreground">Nome Completo</dt>
                                <dd class="mt-1 text-sm text-foreground">{{ form.name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-muted-foreground">E-mail de Contato</dt>
                                <dd class="mt-1 text-sm text-foreground">
                                    <a :href="`mailto:${form.email}`" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        {{ form.email }}
                                    </a>
                                </dd>
                            </div>
                            <div v-if="form.phone">
                                <dt class="text-sm font-medium text-muted-foreground">Telefone de Contato</dt>
                                <dd class="mt-1 text-sm text-foreground">
                                    <a :href="`tel:${form.phone}`" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        {{ form.phone }}
                                    </a>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-muted-foreground">Posição de Interesse</dt>
                                <dd class="mt-1 text-sm text-foreground">
                                    <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary">
                                        {{ form.position }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-muted-foreground">Formação Acadêmica</dt>
                                <dd class="mt-1 text-sm text-foreground">{{ form.education }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-muted-foreground">Status do Currículo</dt>
                                <dd class="mt-1">
                                    <span
                                        v-if="form.cv_path"
                                        class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/20 dark:text-green-400"
                                    >
                                        <svg class="mr-1 size-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        CV Anexado - Pronto para download
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900/20 dark:text-red-400"
                                    >
                                        <svg class="mr-1 size-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        Sem CV anexado
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Informações do Sistema e Usuário -->
                    <div class="rounded-xl border border-sidebar-border/70 bg-background p-6 dark:border-sidebar-border">
                        <h2 class="mb-4 text-lg font-medium text-foreground">Informações do Sistema</h2>
                        <dl class="space-y-4">
                            <div v-if="form.user">
                                <dt class="text-sm font-medium text-muted-foreground">Usuário Responsável</dt>
                                <dd class="mt-1">
                                    <div class="flex items-center gap-3">
                                        <div class="flex size-8 items-center justify-center rounded-full bg-primary/10 text-sm font-medium text-primary">
                                            {{ form.user.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-foreground">{{ form.user.name }}</div>
                                            <div class="text-sm text-muted-foreground">{{ form.user.email }}</div>
                                            <div class="text-xs text-muted-foreground">
                                                Tipo: {{ form.user.role === 'admin' ? 'Administrador' : 'Candidato' }}
                                            </div>
                                        </div>
                                    </div>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-muted-foreground">ID da Candidatura</dt>
                                <dd class="mt-1 text-sm text-foreground font-mono">#{{ form.id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-muted-foreground">Data de Criação</dt>
                                <dd class="mt-1 text-sm text-foreground">{{ formatDate(form.created_at) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-muted-foreground">Última Atualização</dt>
                                <dd class="mt-1 text-sm text-foreground">{{ formatDate(form.updated_at) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-muted-foreground">Tempo desde criação</dt>
                                <dd class="mt-1 text-sm text-foreground">
                                    {{ Math.floor((new Date().getTime() - new Date(form.created_at).getTime()) / (1000 * 60 * 60 * 24)) }} dias
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Observações -->
                <div v-if="form.observations" class="mt-6 rounded-xl border border-sidebar-border/70 bg-background p-6 dark:border-sidebar-border">
                    <h2 class="mb-4 text-lg font-medium text-foreground">Observações do Candidato</h2>
                    <div class="rounded-lg bg-muted/30 p-4">
                        <p class="whitespace-pre-wrap text-sm text-foreground">{{ form.observations }}</p>
                    </div>
                </div>

                <!-- Ações Administrativas -->
                <div class="mt-6 rounded-xl border border-sidebar-border/70 bg-background p-6 dark:border-sidebar-border">
                    <h2 class="mb-4 text-lg font-medium text-foreground">Ações Administrativas</h2>
                    <div class="flex flex-wrap gap-4">
                        <Link
                            :href="`mailto:${form.email}?subject=Sobre sua candidatura para ${form.position}`"
                            class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        >
                            <svg class="mr-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Enviar E-mail
                        </Link>

                        <a
                            v-if="form.cv_path"
                            :href="`/admin/forms/${form.id}/download-cv`"
                            class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-medium text-white hover:bg-green-700"
                            target="_blank"
                            download
                        >
                            <svg class="mr-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download CV
                        </a>

                        <Link
                            href="/admin/forms/export"
                            class="inline-flex items-center rounded-md bg-secondary px-3 py-2 text-sm font-medium text-secondary-foreground hover:bg-secondary/80"
                        >
                            <svg class="mr-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M4 7h16" />
                            </svg>
                            Exportar Dados
                        </Link>
                    </div>
                </div>

                <!-- Navegação -->
                <div class="mt-6 flex justify-between">
                    <Link
                        href="/admin/forms"
                        class="inline-flex items-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium text-foreground hover:bg-accent hover:text-accent-foreground"
                    >
                        <svg class="mr-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Voltar à Lista
                    </Link>

                    <button
                        @click="deleteForm"
                        class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                    >
                        <svg class="mr-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Deletar Candidatura
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
