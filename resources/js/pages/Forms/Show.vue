<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Form } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

interface Props {
    form: Form;
    userRole?: 'admin' | 'applicant';
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: props.userRole === 'admin' ? 'Dashboard Administrativo' : 'Meus Formulários',
        href: props.userRole === 'admin' ? '/admin/forms' : '/forms',
    },
    {
        title: 'Detalhes da Candidatura',
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
</script>

<template>
    <Head :title="`Candidatura - ${form.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
            <div class="mx-auto w-full max-w-4xl">
                <!-- Header -->
                <div class="mb-6 flex items-start justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-foreground">Candidatura de {{ form.name }}</h1>
                        <p class="mt-2 text-sm text-muted-foreground">
                            Enviado em {{ formatDate(form.created_at) }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a
                            v-if="userRole === 'admin' && form.cv_path"
                            :href="`/admin/forms/${form.id}/download-cv`"
                            class="inline-flex items-center rounded-md bg-secondary px-3 py-2 text-sm font-medium text-secondary-foreground hover:bg-secondary/80"
                            target="_blank"
                            download
                        >
                            <svg class="mr-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download CV
                        </a>
                        <Link
                            v-if="userRole !== 'admin'"
                            :href="`/forms/${form.id}/edit`"
                            class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                        >
                            <svg class="mr-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Editar
                        </Link>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <!-- Informações Pessoais -->
                    <div class="rounded-xl border border-sidebar-border/70 bg-background p-6 dark:border-sidebar-border">
                        <h2 class="mb-4 text-lg font-medium text-foreground">Informações Pessoais</h2>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-muted-foreground">Nome Completo</dt>
                                <dd class="mt-1 text-sm text-foreground">{{ form.name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-muted-foreground">E-mail</dt>
                                <dd class="mt-1 text-sm text-foreground">{{ form.email }}</dd>
                            </div>
                            <div v-if="form.phone">
                                <dt class="text-sm font-medium text-muted-foreground">Telefone</dt>
                                <dd class="mt-1 text-sm text-foreground">{{ form.phone }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-muted-foreground">Posição de Interesse</dt>
                                <dd class="mt-1 text-sm text-foreground">{{ form.position }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-muted-foreground">Formação Acadêmica</dt>
                                <dd class="mt-1 text-sm text-foreground">{{ form.education }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Informações do Sistema -->
                    <div class="rounded-xl border border-sidebar-border/70 bg-background p-6 dark:border-sidebar-border">
                        <h2 class="mb-4 text-lg font-medium text-foreground">Informações do Sistema</h2>
                        <dl class="space-y-4">
                            <div v-if="form.user && userRole === 'admin'">
                                <dt class="text-sm font-medium text-muted-foreground">Usuário Responsável</dt>
                                <dd class="mt-1 text-sm text-foreground">
                                    <div class="font-medium">{{ form.user.name }}</div>
                                    <div class="text-muted-foreground">{{ form.user.email }}</div>
                                </dd>
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
                                <dt class="text-sm font-medium text-muted-foreground">Currículo (CV)</dt>
                                <dd class="mt-1">
                                    <span
                                        v-if="form.cv_path"
                                        class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/20 dark:text-green-400"
                                    >
                                        <svg class="mr-1 size-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Arquivo anexado
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900/20 dark:text-red-400"
                                    >
                                        <svg class="mr-1 size-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        Sem arquivo
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Observações -->
                <div v-if="form.observations" class="mt-6 rounded-xl border border-sidebar-border/70 bg-background p-6 dark:border-sidebar-border">
                    <h2 class="mb-4 text-lg font-medium text-foreground">Observações</h2>
                    <div class="rounded-lg bg-muted/30 p-4">
                        <p class="whitespace-pre-wrap text-sm text-foreground">{{ form.observations }}</p>
                    </div>
                </div>

                <!-- Ações -->
                <div class="mt-6 flex gap-4">
                    <Link
                        :href="userRole === 'admin' ? '/admin/forms' : '/forms'"
                        class="inline-flex items-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium text-foreground hover:bg-accent hover:text-accent-foreground"
                    >
                        <svg class="mr-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Voltar
                    </Link>

                    <button
                        v-if="userRole !== 'admin'"
                        @click="deleteForm"
                        class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                    >
                        <svg class="mr-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Deletar
                    </button>

                    <button
                        v-if="userRole === 'admin'"
                        @click="deleteForm"
                        class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                    >
                        <svg class="mr-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Deletar (Admin)
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script lang="ts">
export default {
    methods: {
        deleteForm() {
            if (confirm('Tem certeza que deseja deletar esta candidatura? Esta ação não pode ser desfeita.')) {
                const deleteUrl = this.userRole === 'admin'
                    ? `/admin/forms/${this.form.id}`
                    : `/forms/${this.form.id}`;

                const redirectUrl = this.userRole === 'admin'
                    ? '/admin/forms'
                    : '/forms';

                this.$inertia.delete(deleteUrl, {
                    onSuccess: () => {
                        this.$inertia.visit(redirectUrl);
                    }
                });
            }
        }
    }
};
</script>
